<?php

namespace App\Exports;

use App\Models\CompanyInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class PartnerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return CompanyInformation::all();
        $data_vendor = DB::select('
            SELECT a.name, a.group_name, a.type, a.established_year, a.total_employee, a.owner_name, a.email_address, a.website_address FROM company_informations a
        ');
        return collect($data_vendor);
    }
    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Nama Grup',
            'Tipe',
            'Tahun Berdiri',
            'Jumlah Karyawan',
            'Pemilik Perusahaan',
            'Email Perusahaan',
            'No. Tlp',
        ];
    }
}
