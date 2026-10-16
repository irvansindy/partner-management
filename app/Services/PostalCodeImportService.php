<?php

namespace App\Services;

use App\Models\PostalCode;
use App\Models\Provinces;
use App\Models\Regencies;
use Illuminate\Support\Facades\DB;

class PostalCodeImportService
{
    private const HEADER = ['province', 'regency', 'district', 'village', 'postal_code'];

    public function import(string $path): array
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException('CSV tidak dapat dibaca.');
        }

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        $header = $file->fgetcsv();
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) ($header[0] ?? ''));
        if ($header !== self::HEADER) {
            throw new \InvalidArgumentException('Header CSV tidak sesuai.');
        }

        return DB::transaction(function () use ($file) {
            $result = ['inserted' => 0, 'existing' => 0, 'rejected' => 0, 'errors' => []];
            $rowNumber = 1;
            while (!$file->eof()) {
                $row = $file->fgetcsv();
                $rowNumber++;
                if ($row === false || count(array_filter($row, function ($value) { return trim((string) $value) !== ''; })) === 0) continue;
                $row = array_map(function ($value) { return preg_replace('/\s+/u', ' ', trim((string) $value)); }, $row);
                $reason = $this->validateRow($row);
                $province = $reason ? null : Provinces::whereRaw('LOWER(name) = ?', [mb_strtolower($row[0])])->first();
                $regency = $province ? Regencies::where('province_id', $province->id)->whereRaw('LOWER(name) = ?', [mb_strtolower($row[1])])->first() : null;
                if (!$reason && (!$province || !$regency)) $reason = 'Provinsi atau kabupaten/kota tidak ditemukan atau tidak berelasi.';
                if ($reason) {
                    $result['rejected']++;
                    $result['errors'][] = ['row' => $rowNumber, 'values' => $row, 'reason' => $reason];
                    continue;
                }
                $attributes = ['province_id' => $province->id, 'regency_id' => $regency->id, 'district' => $row[2], 'village' => $row[3], 'postal_code' => $row[4]];
                $model = PostalCode::firstOrCreate($attributes);
                $result[$model->wasRecentlyCreated ? 'inserted' : 'existing']++;
            }
            return $result;
        });
    }

    private function validateRow(array $row): ?string
    {
        if (count($row) !== 5 || in_array('', $row, true)) return 'Semua kolom wajib diisi.';
        if (!mb_check_encoding(implode('', $row), 'UTF-8')) return 'Baris bukan UTF-8 yang valid.';
        return preg_match('/^[0-9]{5}$/', $row[4]) ? null : 'Kode pos harus terdiri dari lima digit.';
    }
}
