<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyAdditionalInformation;

class CompanyAddInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CompanyAdditionalInformation::create([
            DB::table('company_add_info_categories')->insert([
            [
                'name' => 'KTP Penanggung Jawab',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Akte Pendirian beserta Akte Perubahan terakhir',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Kuasa',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Keterangan Terdaftar Pajak',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kartu NPWP',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tanda Daftar Perusahaan (TDP)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Izin Usaha Perdagangan / Ijin Usaha Tetap untuk PMA',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Keterangan Domisili Usaha (SIUP) / Surat Ijin Tempat Usaha (SITU) / Izin Usaha Dagang dari Dinas Perdagangan setempat',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Company Organization',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Customers List (termasuk nama PIC Customer dan no. Telp)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Product List dalam bentuk excel',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Fakta Integritas Vendor',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Izin Usaha Konstruksi (SIUJK)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sertifikat badan usaha (SBU)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Angka Pengenal Import (API)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Nomor Induk Berusaha (NIB)',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KBLI',
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}