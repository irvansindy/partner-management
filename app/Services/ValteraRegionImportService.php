<?php

namespace App\Services;

use App\Models\PostalCode;
use App\Models\Provinces;
use App\Models\Regencies;
use Illuminate\Support\Facades\DB;

class ValteraRegionImportService
{
    private const FILE_HEADERS = [
        'provinces.csv' => ['code', 'name'],
        'regencies.csv' => ['code', 'province_code', 'name'],
        'districts.csv' => ['code', 'regency_code', 'name'],
        'villages.csv' => ['code', 'district_code', 'name', 'postal_code'],
    ];

    private const SOUTHWEST_PAPUA_REGENCIES = [
        '9201' => ['code' => '9601', 'name' => 'Kab. Sorong'],
        '9204' => ['code' => '9602', 'name' => 'Kab. Sorong Selatan'],
        '9205' => ['code' => '9603', 'name' => 'Kab. Raja Ampat'],
        '9209' => ['code' => '9604', 'name' => 'Kab. Tambrauw'],
        '9210' => ['code' => '9605', 'name' => 'Kab. Maybrat'],
        '9271' => ['code' => '9671', 'name' => 'Kota Sorong'],
    ];

    private const SUPPLEMENT_HEADER = [
        'no', 'kode_pos', 'kode_wilayah', 'prov_code', 'kab_code', 'kec_code', 'desa_code',
        'full_code', 'is_kelurahan', 'is_desa', 'desa', 'kecamatan', 'dt2', 'kabupaten',
        'provinsi', 'source_page',
    ];

    public function import(string $directory, bool $dryRun = false, ?string $supplementCsv = null): array
    {
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        $this->assertSourceFiles($directory);

        $supplement = $this->readSupplement($supplementCsv);
        $provinceRows = $this->readAll($directory.'/provinces.csv', self::FILE_HEADERS['provinces.csv']);
        $regencyRows = $this->prepareRegencies($this->readAll($directory.'/regencies.csv', self::FILE_HEADERS['regencies.csv'], true));
        $districtRows = $this->prepareDistricts(
            $this->readAll($directory.'/districts.csv', self::FILE_HEADERS['districts.csv'], true),
            $supplement['districts']
        );

        $provinceRows['96'] = ['code' => '96', 'name' => 'Papua Barat Daya'];
        $this->validateHierarchy($provinceRows, $regencyRows, $districtRows);

        $result = [
            'dry_run' => $dryRun,
            'provinces' => ['processed' => count($provinceRows)],
            'regencies' => ['processed' => count($regencyRows)],
            'districts' => ['processed' => count($districtRows)],
            'postal_codes' => ['inserted' => 0, 'existing' => 0, 'without_postal_code' => 0],
        ];

        $operation = function () use ($directory, $dryRun, $provinceRows, $regencyRows, $districtRows, $supplement, $result) {
            $provinceModels = $dryRun ? [] : $this->persistProvinces($provinceRows);
            $regencyModels = $dryRun ? [] : $this->persistRegencies($regencyRows, $provinceModels);
            $batch = [];

            foreach ($this->rows($directory.'/villages.csv', self::FILE_HEADERS['villages.csv']) as $line => $row) {
                if (!preg_match('/^[0-9]{5}$/', $row['postal_code']) && isset($supplement['villages'][$row['code']])) {
                    $row['postal_code'] = $supplement['villages'][$row['code']];
                }
                $districtCode = $this->remapChildCode($row['district_code']);
                $district = $districtRows[$districtCode] ?? null;
                if (!$district || trim($row['name']) === '') {
                    throw new \InvalidArgumentException("villages.csv baris {$line}: parent tidak ditemukan atau nama kosong.");
                }
                if (!preg_match('/^[0-9]{5}$/', $row['postal_code'])) {
                    $result['postal_codes']['without_postal_code']++;
                    continue;
                }
                if ($dryRun) {
                    $result['postal_codes']['inserted']++;
                    continue;
                }

                $regency = $regencyModels[$district['regency_code']];
                $batch[] = [
                    'province_id' => $regency->province_id,
                    'regency_id' => $regency->id,
                    'district' => $district['name'],
                    'village' => $row['name'],
                    'postal_code' => $row['postal_code'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    $this->persistPostalBatch($batch, $result);
                    $batch = [];
                }
            }
            if ($batch) {
                $this->persistPostalBatch($batch, $result);
            }

            return $result;
        };

        return $dryRun ? $operation() : DB::transaction($operation);
    }

    private function assertSourceFiles(string $directory): void
    {
        foreach (array_keys(self::FILE_HEADERS) as $file) {
            if (!is_readable($directory.'/'.$file)) {
                throw new \InvalidArgumentException("File sumber tidak ditemukan atau tidak dapat dibaca: {$file}");
            }
        }
    }

    private function readAll(string $path, array $header, bool $allowBlankNames = false): array
    {
        $result = [];
        foreach ($this->rows($path, $header) as $line => $row) {
            if (trim($row['code']) === '' || (!$allowBlankNames && trim($row['name']) === '')) {
                throw new \InvalidArgumentException(basename($path)." baris {$line}: kode dan nama wajib diisi.");
            }
            $row['_line'] = $line;
            $result[$row['code']] = $row;
        }

        return $result;
    }

    private function rows(string $path, array $expectedHeader): \Generator
    {
        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $header = $file->fgetcsv();
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) ($header[0] ?? ''));
        if ($header !== $expectedHeader) {
            throw new \InvalidArgumentException(basename($path).': header CSV tidak sesuai.');
        }

        $line = 1;
        while (!$file->eof()) {
            $values = $file->fgetcsv();
            $line++;
            if ($values === false || $values === [null] || count(array_filter($values, function ($value) {
                return trim((string) $value) !== '';
            })) === 0) {
                continue;
            }
            if (count($values) !== count($header)) {
                throw new \InvalidArgumentException(basename($path)." baris {$line}: jumlah kolom tidak sesuai.");
            }
            yield $line => array_combine($header, array_map(function ($value) {
                return preg_replace('/\s+/u', ' ', trim((string) $value));
            }, $values));
        }
    }

    private function prepareRegencies(array $rows): array
    {
        $prepared = [];
        foreach ($rows as $oldCode => $row) {
            if (isset(self::SOUTHWEST_PAPUA_REGENCIES[$oldCode])) {
                $replacement = self::SOUTHWEST_PAPUA_REGENCIES[$oldCode];
                $row['code'] = $replacement['code'];
                $row['province_code'] = '96';
                $row['name'] = $replacement['name'];
            }
            if (trim($row['name']) === '') {
                throw new \InvalidArgumentException("regencies.csv baris {$row['_line']}: kode dan nama wajib diisi.");
            }
            $prepared[$row['code']] = $row;
        }

        return $prepared;
    }

    private function prepareDistricts(array $rows, array $supplement): array
    {
        $prepared = [];
        foreach ($rows as $row) {
            if (trim($row['name']) === '' && isset($supplement[$row['code']])) {
                $row['name'] = $supplement[$row['code']];
            }
            $row['code'] = $this->remapChildCode($row['code']);
            $row['regency_code'] = $this->remapRegencyCode($row['regency_code']);
            $prepared[$row['code']] = $row;
        }

        return $prepared;
    }

    private function readSupplement(?string $path): array
    {
        $result = ['districts' => [], 'villages' => []];
        if ($path === null || $path === '') {
            return $result;
        }
        if (!is_readable($path)) {
            throw new \InvalidArgumentException('File pelengkap tidak ditemukan atau tidak dapat dibaca.');
        }

        foreach ($this->rows($path, self::SUPPLEMENT_HEADER) as $row) {
            if ($row['provinsi'] !== 'Papua Barat Daya') {
                continue;
            }
            $result['districts'][substr($row['full_code'], 0, 6)] = $row['kecamatan'];
            if (preg_match('/^[0-9]{5}$/', $row['kode_pos'])) {
                $result['villages'][$row['full_code']] = $row['kode_pos'];
            }
        }

        return $result;
    }

    private function validateHierarchy(array $provinces, array $regencies, array $districts): void
    {
        foreach ($regencies as $row) {
            if (!isset($provinces[$row['province_code']]) || trim($row['name']) === '') {
                throw new \InvalidArgumentException('regencies.csv: parent tidak ditemukan atau nama kosong untuk kode '.$row['code'].'.');
            }
        }
        foreach ($districts as $row) {
            if (!isset($regencies[$row['regency_code']]) || trim($row['name']) === '') {
                throw new \InvalidArgumentException('districts.csv: parent tidak ditemukan atau nama kosong untuk kode '.$row['code'].'.');
            }
        }
    }

    private function persistProvinces(array $rows): array
    {
        $models = [];
        foreach ($rows as $code => $row) {
            $model = Provinces::where('code', $code)->first() ?: $this->findProvinceByName($row['name']) ?: new Provinces();
            $model->code = $code;
            if (!$model->exists || !$model->name) {
                $model->name = $row['name'];
            }
            $model->save();
            $models[$code] = $model;
        }

        return $models;
    }

    private function persistRegencies(array $rows, array $provinces): array
    {
        $models = [];
        foreach ($rows as $code => $row) {
            $province = $provinces[$row['province_code']];
            $model = Regencies::where('code', $code)->first();
            if (!$model) {
                $model = $this->findRegencyByName($province->id, $row['name']) ?: new Regencies();
            }
            $model->code = $code;
            $model->province_id = $province->id;
            if (!$model->exists || !$model->name) {
                $model->name = $row['name'];
            }
            $model->save();
            $models[$code] = $model;
        }

        return $models;
    }

    private function findProvinceByName(string $name): ?Provinces
    {
        $aliases = ['Kepulauan Bangka Belitung' => 'Bangka Belitung', 'Yogyakarta' => 'DI Yogyakarta'];
        $name = $aliases[$name] ?? $name;

        return Provinces::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
    }

    private function findRegencyByName(int $provinceId, string $sourceName): ?Regencies
    {
        $baseName = $this->baseRegionName($sourceName);
        $candidates = Regencies::where('province_id', $provinceId)->get()->filter(function ($regency) use ($baseName) {
            return $this->baseRegionName($regency->name) === $baseName;
        });
        if ($candidates->count() === 0) {
            return null;
        }
        if ($candidates->count() === 1) {
            $candidate = $candidates->first();
            $sourceType = $this->regionType($sourceName);
            $candidateType = $this->regionType($candidate->name);
            return $sourceType && $candidateType && $sourceType !== $candidateType ? null : $candidate;
        }

        $sourceType = $this->regionType($sourceName);
        $typed = $candidates->filter(function ($regency) use ($sourceType) {
            $candidateType = $this->regionType($regency->name);
            if ($sourceType === 1) {
                return $candidateType === 1;
            }
            if ($sourceType === 2) {
                return $candidateType !== 1;
            }

            return $candidateType === 0;
        });

        return $typed->count() === 1 ? $typed->first() : null;
    }

    private function baseRegionName(string $name): string
    {
        $name = preg_replace('/^(Kab\.|Kabupaten|Kota)\s+/i', '', trim($name));
        return mb_strtolower(preg_replace('/\s+/u', ' ', $name));
    }

    private function regionType(string $name): int
    {
        if (preg_match('/^(Kab\.|Kabupaten)\s+/i', trim($name))) {
            return 1;
        }
        if (preg_match('/^Kota\s+/i', trim($name))) {
            return 2;
        }

        return 0;
    }

    private function remapRegencyCode(string $code): string
    {
        return self::SOUTHWEST_PAPUA_REGENCIES[$code]['code'] ?? $code;
    }

    private function remapChildCode(string $code): string
    {
        $regencyCode = substr($code, 0, 4);
        if (!isset(self::SOUTHWEST_PAPUA_REGENCIES[$regencyCode])) {
            return $code;
        }

        return self::SOUTHWEST_PAPUA_REGENCIES[$regencyCode]['code'].substr($code, 4);
    }

    private function persistPostalBatch(array $batch, array &$result): void
    {
        $inserted = DB::table('postal_codes')->insertOrIgnore($batch);
        $result['postal_codes']['inserted'] += $inserted;
        $result['postal_codes']['existing'] += count($batch) - $inserted;
    }
}
