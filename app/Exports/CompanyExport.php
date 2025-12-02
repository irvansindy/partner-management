<?php

namespace App\Exports;

use App\Models\CompanyInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * Ambil data dari database dengan semua relasi
     */
    public function collection()
    {
        return CompanyInformation::with([
            'contact',      // Contact Person
            'address',      // Addresses
            'bank',         // Banks
            'liablePeople'  // Liable People (jika ada)
        ])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Mapping data ke format Excel (dalam Bahasa Indonesia)
     */
    public function map($company): array
    {
        // ===== CONTACT PERSON (ambil yang pertama) =====
        $contact = $company->contact->first();

        // ===== MULTIPLE ADDRESSES (gabung dengan separator |) =====
        $addresses = $company->address->pluck('address')->filter()->implode(' | ');
        $zipCodes = $company->address->pluck('zip_code')->filter()->implode(' | ');
        $telephones = $company->address->pluck('telephone')->filter()->implode(' | ');
        $faxes = $company->address->pluck('fax')->filter()->implode(' | ');

        // ===== MULTIPLE BANKS (gabung dengan separator |) =====
        $bankNames = $company->bank->pluck('name')->filter()->implode(' | ');
        $accountNames = $company->bank->pluck('account_name')->filter()->implode(' | ');
        $accountNumbers = $company->bank->pluck('account_number')->filter()->implode(' | ');

        return [
            // Company Information
            $company->name ?? '',
            $company->group_name ?? '',
            $company->type ?? '',
            $company->established_year ?? '',
            $company->total_employee ?? '',
            $company->business_classification ?? '',
            $company->other_business ?? '',
            $company->business_classification_detail ?? '',
            $company->npwp ?? '',
            $company->website_address ?? '',
            $company->system_management ?? '',
            $company->email_address ?? '',
            $company->credit_limit ?? '',
            $company->term_of_payment ?? '',

            // Contact Person
            $contact->name ?? '',
            $contact->position ?? '',
            $contact->department ?? '',
            $contact->email ?? '',
            $contact->telephone ?? '',

            // Addresses
            $addresses,
            $zipCodes,
            $telephones,
            $faxes,

            // Banks
            $bankNames,
            $accountNames,
            $accountNumbers,
        ];
    }

    /**
     * Header kolom (Bahasa Indonesia - sesuai dengan CompanyImport)
     */
    public function headings(): array
    {
        return [
            // Company Information
            'nama_perusahaan',
            'group_perusahaan',
            'jenis_perusahaan_vendorcustomer',
            'tahun_berdiri',
            'jumlah_karyawan',
            'klasifikasi_bisnis',
            'other_bisnis',
            'detail_bisnis',
            'npwp',
            'website',
            'jenis_sertifikat',
            'email_koresponden',
            'batas_kredit_dalam_rupiah',
            'jangga_waktu_pembayaran_dalam_hari',

            // Contact Person
            'nama_kontak',
            'jabatan_kontak',
            'departemen_kontak',
            'e_mail_kontak',
            'telepon_kontak',

            // Addresses
            'alamat_npwp',
            'kode_pos',
            'telepon_alamat',
            'fax_alamat',

            // Banks
            'nama_bank',
            'nama_akun_bank',
            'nomor_akun_bank',
        ];
    }

    /**
     * Styling untuk header
     */
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

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            // Company Information
            'A' => 35, // nama_perusahaan
            'B' => 20, // group_perusahaan
            'C' => 20, // jenis_perusahaan
            'D' => 15, // tahun_berdiri
            'E' => 15, // jumlah_karyawan
            'F' => 20, // klasifikasi_bisnis
            'G' => 20, // other_bisnis
            'H' => 30, // detail_bisnis
            'I' => 20, // npwp
            'J' => 25, // website
            'K' => 20, // jenis_sertifikat
            'L' => 30, // email_koresponden
            'M' => 20, // batas_kredit
            'N' => 25, // jangka_waktu

            // Contact Person
            'O' => 25, // nama_kontak
            'P' => 20, // jabatan_kontak
            'Q' => 20, // departemen_kontak
            'R' => 30, // email_kontak
            'S' => 20, // telepon_kontak

            // Addresses
            'T' => 45, // alamat
            'U' => 15, // kode_pos
            'V' => 20, // telepon_alamat
            'W' => 20, // fax

            // Banks
            'X' => 25, // nama_bank
            'Y' => 25, // nama_akun
            'Z' => 20, // nomor_akun
        ];
    }
}