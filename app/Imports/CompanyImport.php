<?php

namespace App\Imports;

use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyContact;
use App\Models\CompanyInformation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class CompanyImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    private bool $previewOnly;
    private array $errors = [];

    public function __construct($previewOnly = false)
    {
        $this->previewOnly = $previewOnly;
    }

     /**
     * ← TAMBAH METHOD INI: Hanya import sheet pertama
     */
    public function sheets(): array
    {
        return [
            0 => $this, // Hanya sheet index 0 (sheet pertama)
        ];
    }

    /**
     * 🔥 MAP KOLOM INDONESIA KE ENGLISH
     */
    private function mapRowKeys($row)
    {
        $mapping = [
            // Company Information
            'nama_perusahaan' => 'name',
            'group_perusahaan' => 'group_name',
            'jenis_perusahaan_vendorcustomer' => 'type',
            'tahun_berdiri' => 'established_year',
            'jumlah_karyawan' => 'total_employee',
            'klasifikasi_bisnis' => 'business_classification',
            'detail_bisnis' => 'business_classification_detail',
            'other_bisnis' => 'other_business',
            'npwp' => 'npwp',
            'website' => 'website_address',
            'jenis_sertifikat' => 'system_management',
            'email_koresponden' => 'email_address',
            'batas_kredit_dalam_rupiah' => 'credit_limit',
            'jangga_waktu_pembayaran_dalam_hari' => 'term_of_payment',

            // Contact Person
            'nama_kontak' => 'contact_name',
            'jabatan_kontak' => 'contact_position',
            'departemen_kontak' => 'contact_department',
            'e_mail_kontak' => 'contact_email',
            'telepon_kontak' => 'contact_telephone',

            // Address
            'alamat_npwp' => 'address',
            'kode_pos' => 'zip_code',
            'telepon_alamat' => 'telephone_address',
            'fax_alamat' => 'fax',

            // Bank
            'nama_bank' => 'bank_name',
            'nama_akun_bank' => 'account_name',
            'nomor_akun_bank' => 'account_number',
        ];

        $mapped = [];
        foreach ($row as $key => $value) {
            $mappedKey = $mapping[$key] ?? $key;
            $mapped[$mappedKey] = $value;
        }

        return $mapped;
    }

    public function collection(Collection $rows)
    {
        $user = Auth::user();

        // Jangan mulai transaction untuk preview
        if (!$this->previewOnly) {
            DB::beginTransaction();
        }

        try {
            $rowNumber = 1; // header = 1
            $importedCount = 0;

            foreach ($rows as $row) {
                $rowNumber++;

                // 🔥 MAP KOLOM INDONESIA KE INGGRIS - INI YANG PENTING!
                $originalRow = $row->toArray();
                $row = $this->mapRowKeys($originalRow);

                // Debug untuk row pertama
                if ($rowNumber == 2) {
                    Log::info("Excel Column Keys (Original): " . json_encode(array_keys($originalRow)));
                    Log::info("Excel Column Keys (Mapped): " . json_encode(array_keys($row)));
                    Log::info("Row $rowNumber Data (Mapped): " . json_encode($row));
                }

                // Skip BENAR-BENAR empty rows
                $hasData = false;
                foreach ($row as $value) {
                    if (!empty($value)) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    Log::info("Row $rowNumber: Skipped (completely empty)");
                    continue;
                }

                Log::info("Row $rowNumber: Processing - Name: " . ($row['name'] ?? 'NULL') . ", Email: " . ($row['email_address'] ?? 'NULL'));

                /** VALIDASI PER KOLOM */
                $currentErrorCount = count($this->errors);
                $this->validateRow($row, $rowNumber);

                // Jika ada error BARU pada row ini, skip
                if (count($this->errors) > $currentErrorCount) {
                    Log::warning("Row $rowNumber: Validation failed - " . implode(', ', array_slice($this->errors, $currentErrorCount)));
                    continue;
                }

                // Jika preview only, skip insert
                if ($this->previewOnly) {
                    Log::info("Row $rowNumber: Preview mode - skipping insert");
                    continue;
                }

                try {
                    /** INSERT DATA COMPANY */
                    $companyData = [
                        'user_id' => $user->id,
                        'name'           => $row['name'] ?? null,
                        'group_name'     => $row['group_name'] ?? null,
                        'type'           => $row['type'] ?? null,
                        'established_year' => $row['established_year'] ?? null,
                        'total_employee'   => $row['total_employee'] ?? null,
                        'npwp'             => $row['npwp'] ?? null,
                        'business_classification'        => $row['business_classification'] ?? null,
                        'business_classification_detail' => $row['business_classification_detail'] ?? null,
                        'other_business'  => $row['other_business'] ?? null,
                        'website_address' => $row['website_address'] ?? null,
                        'system_management'=> $row['system_management'] ?? null,
                        'email_address'   => $row['email_address'] ?? null,
                        'term_of_payment' => $row['term_of_payment'] ?? null,
                        'credit_limit'    => $row['credit_limit'] ?? null,
                        'location_id'     => $user->office_id ?? null,
                        'department_id'   => $user->department_id ?? null,
                    ];

                    Log::info("Row $rowNumber: Creating company...");

                    $company = CompanyInformation::create($companyData);

                    Log::info("Row $rowNumber: ✅ Company created successfully with ID: " . $company->id);
                    $importedCount++;

                    /** CONTACT PERSON */
                    if (!empty($row['contact_name'])) {
                        $contactData = [
                            'company_informations_id' => $company->id,  // Perhatikan 's' di sini
                            'name'       => $row['contact_name'],
                            'department' => $row['contact_department'] ?? null,
                            'position'   => $row['contact_position'] ?? null,
                            'email'      => $row['contact_email'] ?? null,
                            'telephone'  => $row['contact_telephone'] ?? null,
                        ];

                        CompanyContact::create($contactData);
                        Log::info("Row $rowNumber: ✅ Contact created for company ID: " . $company->id);
                    }

                    /** MULTIPLE ADDRESS */
                    if (!empty($row['address'])) {
                        $addrs = explode('|', $row['address']);
                        $zips  = explode('|', $row['zip_code'] ?? '');
                        $tels  = explode('|', $row['telephone_address'] ?? '');
                        $faxes = explode('|', $row['fax'] ?? '');

                        foreach ($addrs as $i => $addr) {
                            if (trim($addr)) {
                                CompanyAddress::create([
                                    'company_id' => $company->id,  // Tanpa 's'
                                    'address'   => trim($addr),
                                    'zip_code'  => $zips[$i] ?? null,
                                    'telephone' => $tels[$i] ?? null,
                                    'fax'       => $faxes[$i] ?? null,
                                ]);
                            }
                        }
                        Log::info("Row $rowNumber: ✅ Address(es) created for company ID: " . $company->id);
                    }

                    /** MULTIPLE BANK */
                    if (!empty($row['bank_name'])) {
                        $names  = explode('|', $row['bank_name']);
                        $accNM  = explode('|', $row['account_name'] ?? '');
                        $accNO  = explode('|', $row['account_number'] ?? '');

                        foreach ($names as $i => $name) {
                            if (trim($name)) {
                                CompanyBank::create([
                                    'company_id' => $company->id,  // Tanpa 's'
                                    'name'          => trim($name),
                                    'account_name'  => $accNM[$i] ?? null,
                                    'account_number'=> $accNO[$i] ?? null,
                                ]);
                            }
                        }
                        Log::info("Row $rowNumber: ✅ Bank(s) created for company ID: " . $company->id);
                    }

                } catch (\Exception $e) {
                    $this->errors[] = "Row $rowNumber: Database error - " . $e->getMessage();
                    Log::error("Row $rowNumber: ❌ Failed to insert - " . $e->getMessage());
                    Log::error($e->getTraceAsString());
                }
            }

            // Jika ada error validasi, rollback
            if (count($this->errors) > 0) {
                if (!$this->previewOnly) {
                    DB::rollBack();
                    Log::warning("❌ Import rolled back due to errors");
                }
                throw new \Exception("Validation failed: " . implode(', ', $this->errors));
            }

            // Commit jika bukan preview
            if (!$this->previewOnly) {
                DB::commit();
                Log::info("✅ Import committed successfully. Total imported: $importedCount rows");
            } else {
                Log::info("Preview completed. Would import: $importedCount rows");
            }

        } catch (\Exception $e) {
            if (!$this->previewOnly) {
                DB::rollBack();
                Log::error("Transaction rolled back");
            }

            Log::error("Import failed: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * === VALIDASI PER BARIS ===
     */
    private function validateRow($row, $rowNumber)
    {
        if (empty($row['name'])) {
            $this->errors[] = "Baris $rowNumber: Kolom 'nama_perusahaan' wajib diisi";
        }

        if (!empty($row['email_address']) && !filter_var($row['email_address'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Baris $rowNumber: Format email_koresponden tidak valid";
        }

        if (!empty($row['established_year']) && !is_numeric($row['established_year'])) {
            $this->errors[] = "Baris $rowNumber: tahun_berdiri harus berupa angka";
        }

        if (!empty($row['contact_email']) && !filter_var($row['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Baris $rowNumber: Format e_mail_kontak tidak valid";
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
