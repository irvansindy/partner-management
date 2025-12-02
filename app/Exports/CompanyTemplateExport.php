<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class CompanyTemplateExport implements WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    /**
     * Header kolom template - SAMA PERSIS dengan mapping di CompanyImport
     * Urutan kolom sesuai dengan yang ada di mapRowKeys()
     */
    public function headings(): array
    {
        return [
            // Company Information
            'nama_perusahaan',                      // → name (WAJIB)
            'group_perusahaan',                     // → group_name
            'jenis_perusahaan_vendorcustomer',     // → type
            'tahun_berdiri',                        // → established_year
            'jumlah_karyawan',                      // → total_employee
            'klasifikasi_bisnis',                   // → business_classification
            'detail_bisnis',                        // → business_classification_detail
            'other_bisnis',                         // → other_business
            'npwp',                                 // → npwp
            'website',                              // → website_address
            'jenis_sertifikat',                     // → system_management
            'email_koresponden',                    // → email_address
            'batas_kredit_dalam_rupiah',           // → credit_limit
            'jangga_waktu_pembayaran_dalam_hari',  // → term_of_payment

            // Contact Person
            'nama_kontak',                          // → contact_name
            'jabatan_kontak',                       // → contact_position
            'departemen_kontak',                    // → contact_department
            'e_mail_kontak',                        // → contact_email
            'telepon_kontak',                       // → contact_telephone

            // Address (Multiple - separator |)
            'alamat_npwp',                          // → address (bisa multiple dengan |)
            'kode_pos',                             // → zip_code (bisa multiple dengan |)
            'telepon_alamat',                       // → telephone_address (bisa multiple dengan |)
            'fax_alamat',                           // → fax (bisa multiple dengan |)

            // Bank (Multiple - separator |)
            'nama_bank',                            // → bank_name (bisa multiple dengan |)
            'nama_akun_bank',                       // → account_name (bisa multiple dengan |)
            'nomor_akun_bank',                      // → account_number (bisa multiple dengan |)
        ];
    }

    /**
     * Styling untuk header dan template
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header (row 1)
        $sheet->getStyle('A1:AA1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height untuk header
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Tambahkan contoh data di row 2
        $sheet->fromArray([
            [
                // Company Information
                'PT Vereenigde Oostindische Compagnie',  // nama_perusahaan (WAJIB)
                'VOC Group',                              // group_perusahaan
                'vendor',                                 // jenis_perusahaan_vendorcustomer
                '1602',                                   // tahun_berdiri
                '70000',                                  // jumlah_karyawan
                'Distributor',                            // klasifikasi_bisnis
                'Menyalurkan rempah-rempah',             // detail_bisnis
                '',                                       // other_bisnis
                '65764678976',                           // npwp
                'voc.com',                               // website
                'ISO',                                    // jenis_sertifikat
                'voc@voc.com',                           // email_koresponden
                '100000000',                             // batas_kredit_dalam_rupiah
                '45',                                     // jangga_waktu_pembayaran_dalam_hari

                // Contact Person
                'Sindy',                                  // nama_kontak
                'Staff',                                  // jabatan_kontak
                'ICT',                                    // departemen_kontak
                'sindyict@gmail.com',                    // e_mail_kontak
                '0895443459988',                         // telepon_kontak

                // Address (Multiple dengan separator |)
                'Jl. Sirotol Mustakim | Jl. Kedua No. 2', // alamat_npwp
                '10000 | 10001',                          // kode_pos
                '02111211 | 02111212',                    // telepon_alamat
                '02112211 | 02112212',                    // fax_alamat

                // Bank (Multiple dengan separator |)
                'Bank BCA | Bank Mandiri',                // nama_bank
                'Sindy | PT VOC',                         // nama_akun_bank
                '9876545 | 1234567',                      // nomor_akun_bank
            ]
        ], null, 'A2');

        // Style untuk contoh data (row 2)
        $sheet->getStyle('A2:AA2')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF4E6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Freeze pane - freeze header row
        $sheet->freezePane('A2');

        return [];
    }

    /**
     * Lebar kolom untuk setiap field
     */
    public function columnWidths(): array
    {
        return [
            // Company Information
            'A' => 35,  // nama_perusahaan (WAJIB - lebih lebar)
            'B' => 20,  // group_perusahaan
            'C' => 20,  // jenis_perusahaan
            'D' => 15,  // tahun_berdiri
            'E' => 15,  // jumlah_karyawan
            'F' => 20,  // klasifikasi_bisnis
            'G' => 30,  // detail_bisnis
            'H' => 20,  // other_bisnis
            'I' => 20,  // npwp
            'J' => 25,  // website
            'K' => 20,  // jenis_sertifikat
            'L' => 30,  // email_koresponden
            'M' => 20,  // batas_kredit
            'N' => 25,  // jangka_waktu_hari

            // Contact Person
            'O' => 25,  // nama_kontak
            'P' => 20,  // jabatan_kontak
            'Q' => 20,  // departemen_kontak
            'R' => 30,  // email_kontak
            'S' => 20,  // telepon_kontak

            // Address
            'T' => 45,  // alamat (multiple - lebih lebar)
            'U' => 15,  // kode_pos
            'V' => 20,  // telepon_alamat
            'W' => 20,  // fax

            // Bank
            'X' => 25,  // nama_bank (multiple)
            'Y' => 25,  // nama_akun
            'Z' => 20,  // nomor_akun
        ];
    }

    /**
     * Register events untuk menambahkan validasi dan catatan
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ========================================
                // TAMBAHKAN COMMENT/NOTE PADA HEADER
                // ========================================

                // nama_perusahaan (WAJIB)
                $sheet->getComment('A1')->getText()->createTextRun(
                    "⚠️ WAJIB DIISI!\nNama perusahaan yang akan diimport.\n\nContoh: PT Maju Jaya"
                );

                // jenis_perusahaan_vendorcustomer
                $sheet->getComment('C1')->getText()->createTextRun(
                    "Pilih salah satu:\n• vendor\n• customer\n\nContoh: vendor"
                );

                // email_koresponden
                $sheet->getComment('L1')->getText()->createTextRun(
                    "Format email yang valid.\n\nContoh: info@perusahaan.com"
                );

                // tahun_berdiri
                $sheet->getComment('D1')->getText()->createTextRun(
                    "Harus berupa angka (tahun).\n\nContoh: 2020"
                );

                // alamat_npwp (Multiple)
                $sheet->getComment('T1')->getText()->createTextRun(
                    "📍 MULTIPLE ADDRESSES\nPisahkan dengan | (pipe) untuk multiple alamat.\n\nContoh:\nJl. Utama No. 1 | Jl. Kedua No. 2"
                );

                // kode_pos (Multiple)
                $sheet->getComment('U1')->getText()->createTextRun(
                    "MULTIPLE ZIP CODES\nPisahkan dengan | sesuai urutan alamat.\n\nContoh: 12345 | 67890"
                );

                // telepon_alamat (Multiple)
                $sheet->getComment('V1')->getText()->createTextRun(
                    "MULTIPLE TELEPON\nPisahkan dengan | sesuai urutan alamat.\n\nContoh: 021-111 | 021-222"
                );

                // fax_alamat (Multiple)
                $sheet->getComment('W1')->getText()->createTextRun(
                    "MULTIPLE FAX\nPisahkan dengan | sesuai urutan alamat.\n\nContoh: 021-333 | 021-444"
                );

                // nama_bank (Multiple)
                $sheet->getComment('X1')->getText()->createTextRun(
                    "🏦 MULTIPLE BANKS\nPisahkan dengan | untuk multiple bank.\n\nContoh: Bank BCA | Bank Mandiri"
                );

                // nama_akun_bank (Multiple)
                $sheet->getComment('Y1')->getText()->createTextRun(
                    "MULTIPLE ACCOUNT NAMES\nPisahkan dengan | sesuai urutan bank.\n\nContoh: PT ABC | CV XYZ"
                );

                // nomor_akun_bank (Multiple)
                $sheet->getComment('Z1')->getText()->createTextRun(
                    "MULTIPLE ACCOUNT NUMBERS\nPisahkan dengan | sesuai urutan bank.\n\nContoh: 1234567890 | 0987654321"
                );

                // e_mail_kontak
                $sheet->getComment('R1')->getText()->createTextRun(
                    "Format email yang valid.\n\nContoh: kontak@perusahaan.com"
                );

                // ========================================
                // DATA VALIDATION - Dropdown untuk jenis_perusahaan
                // ========================================
                $validation = $sheet->getCell('C2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('❌ Input Error');
                $validation->setError('Harus pilih "vendor" atau "customer"');
                $validation->setPromptTitle('📋 Pilih Jenis Perusahaan');
                $validation->setPrompt('Silakan pilih:\n• vendor (untuk supplier)\n• customer (untuk pelanggan)');
                $validation->setFormula1('"vendor,customer"');

                // Copy validation ke rows 3-1000
                for ($row = 3; $row <= 1000; $row++) {
                    $sheet->getCell('C' . $row)->setDataValidation(clone $validation);
                }

                // ========================================
                // HIGHLIGHT KOLOM WAJIB (nama_perusahaan)
                // ========================================
                $sheet->getStyle('A1')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FF6B6B'], // Merah untuk kolom wajib
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ]);

                // ========================================
                // HIGHLIGHT KOLOM MULTIPLE (berbeda warna)
                // ========================================
                $multipleColumns = ['T', 'U', 'V', 'W', 'X', 'Y', 'Z']; // Alamat & Bank multiple
                foreach ($multipleColumns as $col) {
                    $sheet->getStyle($col . '1')->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '95E1D3'], // Tosca untuk multiple
                        ],
                    ]);
                }

                // ========================================
                // TAMBAHKAN SHEET INSTRUCTIONS (Sheet ke-2)
                // ========================================
                // Note: Ini akan menambah sheet baru untuk petunjuk
                $spreadsheet = $event->sheet->getDelegate()->getParent();
                $instructionSheet = $spreadsheet->createSheet();
                $instructionSheet->setTitle('📋 PETUNJUK');

                $instructions = [
                    ['PETUNJUK PENGGUNAAN TEMPLATE IMPORT'],
                    [''],
                    ['1. KOLOM WAJIB (HEADER MERAH)'],
                    ['   • nama_perusahaan → WAJIB DIISI'],
                    [''],
                    ['2. KOLOM MULTIPLE (HEADER TOSCA)'],
                    ['   • Alamat, Telepon, Fax, Bank → Bisa lebih dari 1'],
                    ['   • Pisahkan dengan karakter | (pipe)'],
                    ['   • Contoh: Bank BCA | Bank Mandiri'],
                    [''],
                    ['3. FORMAT DATA'],
                    ['   • tahun_berdiri → Harus angka (contoh: 2020)'],
                    ['   • email_koresponden → Format email valid'],
                    ['   • e_mail_kontak → Format email valid'],
                    ['   • jenis_perusahaan → Pilih: vendor atau customer'],
                    [''],
                    ['4. CONTOH MULTIPLE DATA'],
                    ['   Jika punya 2 alamat:'],
                    ['   • alamat_npwp: Jl. A No. 1 | Jl. B No. 2'],
                    ['   • kode_pos: 12345 | 67890'],
                    ['   • telepon_alamat: 021-111 | 021-222'],
                    ['   • fax_alamat: 021-333 | 021-444'],
                    [''],
                    ['5. TIPS'],
                    ['   • Lihat contoh di row 2 sheet "Data"'],
                    ['   • Hover mouse di header untuk lihat catatan'],
                    ['   • Hapus row contoh sebelum import data real'],
                    [''],
                    ['6. PROSES IMPORT'],
                    ['   • Isi data sesuai format'],
                    ['   • Save file Excel'],
                    ['   • Upload di halaman import'],
                    ['   • Preview data sebelum confirm'],
                ];

                $instructionSheet->fromArray($instructions, null, 'A1');

                // Style untuk instruction sheet
                $instructionSheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '4472C4']],
                ]);

                $instructionSheet->getColumnDimension('A')->setWidth(70);

                // Set sheet Data sebagai active sheet (default)
                $spreadsheet->setActiveSheetIndex(0);
            },
        ];
    }
}