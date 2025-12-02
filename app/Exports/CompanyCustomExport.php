<?php

namespace App\Exports;

use App\Models\CompanyInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyCustomExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $selectedFields;
    protected $fieldMapping;

    public function __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;

        // Mapping field ke label Indonesia
        $this->fieldMapping = [
            'name' => 'nama_perusahaan',
            'group_name' => 'group_perusahaan',
            'type' => 'jenis_perusahaan_vendorcustomer',
            'established_year' => 'tahun_berdiri',
            'total_employee' => 'jumlah_karyawan',
            'business_classification' => 'klasifikasi_bisnis',
            'other_business' => 'other_bisnis',
            'business_classification_detail' => 'detail_bisnis',
            'npwp' => 'npwp',
            'website_address' => 'website',
            'system_management' => 'jenis_sertifikat',
            'email_address' => 'email_koresponden',
            'credit_limit' => 'batas_kredit_dalam_rupiah',
            'term_of_payment' => 'jangga_waktu_pembayaran_dalam_hari',
            'contact_name' => 'nama_kontak',
            'contact_position' => 'jabatan_kontak',
            'contact_department' => 'departemen_kontak',
            'contact_email' => 'e_mail_kontak',
            'contact_telephone' => 'telepon_kontak',
            'address' => 'alamat_npwp',
            'zip_code' => 'kode_pos',
            'telephone_address' => 'telepon_alamat',
            'fax' => 'fax_alamat',
            'bank_name' => 'nama_bank',
            'account_name' => 'nama_akun_bank',
            'account_number' => 'nomor_akun_bank',
            'liable_name' => 'nama_penanggung_jawab',
            'liable_nik' => 'nik_penanggung_jawab',
            'liable_position' => 'jabatan_penanggung_jawab',
        ];
    }

    public function collection()
    {
        return CompanyInformation::with(['contact', 'address', 'bank', 'liablePeople'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($company): array
    {
        $row = [];

        foreach ($this->selectedFields as $field) {
            switch ($field) {
                // Company fields
                case 'name':
                    $row[] = $company->name ?? '';
                    break;
                case 'group_name':
                    $row[] = $company->group_name ?? '';
                    break;
                case 'type':
                    $row[] = $company->type ?? '';
                    break;
                case 'established_year':
                    $row[] = $company->established_year ?? '';
                    break;
                case 'total_employee':
                    $row[] = $company->total_employee ?? '';
                    break;
                case 'business_classification':
                    $row[] = $company->business_classification ?? '';
                    break;
                case 'other_business':
                    $row[] = $company->other_business ?? '';
                    break;
                case 'business_classification_detail':
                    $row[] = $company->business_classification_detail ?? '';
                    break;
                case 'npwp':
                    $row[] = $company->npwp ?? '';
                    break;
                case 'website_address':
                    $row[] = $company->website_address ?? '';
                    break;
                case 'system_management':
                    $row[] = $company->system_management ?? '';
                    break;
                case 'email_address':
                    $row[] = $company->email_address ?? '';
                    break;
                case 'credit_limit':
                    $row[] = $company->credit_limit ?? '';
                    break;
                case 'term_of_payment':
                    $row[] = $company->term_of_payment ?? '';
                    break;

                // Contact fields
                case 'contact_name':
                    $row[] = $company->contact->first()->name ?? '';
                    break;
                case 'contact_position':
                    $row[] = $company->contact->first()->position ?? '';
                    break;
                case 'contact_department':
                    $row[] = $company->contact->first()->department ?? '';
                    break;
                case 'contact_email':
                    $row[] = $company->contact->first()->email ?? '';
                    break;
                case 'contact_telephone':
                    $row[] = $company->contact->first()->telephone ?? '';
                    break;

                // Address fields
                case 'address':
                    $row[] = $company->address->pluck('address')->filter()->implode(' | ');
                    break;
                case 'zip_code':
                    $row[] = $company->address->pluck('zip_code')->filter()->implode(' | ');
                    break;
                case 'telephone_address':
                    $row[] = $company->address->pluck('telephone')->filter()->implode(' | ');
                    break;
                case 'fax':
                    $row[] = $company->address->pluck('fax')->filter()->implode(' | ');
                    break;

                // Bank fields
                case 'bank_name':
                    $row[] = $company->bank->pluck('name')->filter()->implode(' | ');
                    break;
                case 'account_name':
                    $row[] = $company->bank->pluck('account_name')->filter()->implode(' | ');
                    break;
                case 'account_number':
                    $row[] = $company->bank->pluck('account_number')->filter()->implode(' | ');
                    break;

                // Liable People fields
                case 'liable_name':
                    $row[] = $company->liablePeople->pluck('name')->filter()->implode(' | ');
                    break;
                case 'liable_nik':
                    $row[] = $company->liablePeople->pluck('nik')->filter()->implode(' | ');
                    break;
                case 'liable_position':
                    $row[] = $company->liablePeople->pluck('position')->filter()->implode(' | ');
                    break;
            }
        }

        return $row;
    }

    public function headings(): array
    {
        $headings = [];

        foreach ($this->selectedFields as $field) {
            $headings[] = $this->fieldMapping[$field] ?? $field;
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        $widths = [];
        $columns = range('A', 'Z');
        $columnsExtended = array_merge($columns, ['AA', 'AB', 'AC', 'AD', 'AE']);

        foreach ($this->selectedFields as $index => $field) {
            $column = $columnsExtended[$index] ?? 'A';

            // Set width based on field type
            if (in_array($field, ['address', 'business_classification_detail'])) {
                $widths[$column] = 40;
            } elseif (in_array($field, ['name', 'email_address', 'contact_email'])) {
                $widths[$column] = 30;
            } elseif (in_array($field, ['liable_name', 'contact_name'])) {
                $widths[$column] = 25;
            } else {
                $widths[$column] = 20;
            }
        }

        return $widths;
    }
}