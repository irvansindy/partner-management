<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyAdditionalInformation;
use Illuminate\Support\Str;

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
            DB::table('company_document_type_categories')->insert([
            [
                'name' => 'KTP Penanggung Jawab',
                'name_id_class'=> Str::slug(strtolower('KTP Penanggung Jawab'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Akte Pendirian beserta Akte Perubahan terakhir',
                'name_id_class'=> Str::slug(strtolower('Akte Pendirian beserta Akte Perubahan terakhir'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Kuasa',
                'name_id_class'=> Str::slug(strtolower('Surat Kuasa'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Keterangan Terdaftar Pajak',
                'name_id_class'=> Str::slug(strtolower('Surat Keterangan Terdaftar Pajak'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kartu NPWP',
                'name_id_class'=> Str::slug(strtolower('Kartu NPWP'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'name_id_class'=> Str::slug(strtolower('Surat Pengukuhan Pengusaha Kena Pajak'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tanda Daftar Perusahaan (TDP)',
                'name_id_class'=> Str::slug(strtolower('Tanda Daftar Perusahaan'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Izin Usaha Perdagangan / Ijin Usaha Tetap untuk PMA',
                'name_id_class'=> Str::slug(strtolower('Surat Izin Usaha Perdagangan atau Ijin Usaha Tetap untuk PMA'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Keterangan Domisili Usaha (SIUP) / Surat Ijin Tempat Usaha (SITU) / Izin Usaha Dagang dari Dinas Perdagangan setempat',
                'name_id_class'=> Str::slug(strtolower('Siup atau SITU'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Company Organization',
                'name_id_class'=> Str::slug(strtolower('Company Organization'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Customers List (termasuk nama PIC Customer dan no. Telp)',
                'name_id_class'=> Str::slug(strtolower('Customers List'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Product List dalam bentuk excel',
                'name_id_class'=> Str::slug(strtolower('Product List'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Fakta Integritas Vendor',
                'name_id_class'=> Str::slug(strtolower('Fakta Integritas Vendor'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surat Izin Usaha Konstruksi (SIUJK)',
                'name_id_class'=> Str::slug(strtolower('Surat Izin Usaha Konstruksi'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sertifikat badan usaha (SBU)',
                'name_id_class'=> Str::slug(strtolower('Sertifikat badan usaha'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Angka Pengenal Import (API)',
                'name_id_class'=> Str::slug(strtolower('Angka Pengenal Import'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Nomor Induk Berusaha (NIB)',
                'name_id_class'=> Str::slug(strtolower('Nomor Induk Berusaha'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KBLI',
                'name_id_class'=> Str::slug(strtolower('KBLI'), '_'),
                'created_at'=> now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}