<?php

namespace App\Exports\Sheets;

use App\Models\CompanyAddress;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithTitle};

class CompanyAddressExport implements FromCollection, WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CompanyAddress::select(
            'id',
            'company_id',
            'address',
            'city',
            'province',
            'country',
            'postal_code',
            'created_at',
            'updated_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Company ID',
            'Address',
            'City',
            'Province',
            'Country',
            'Postal Code',
            'Created At',
            'Updated At'
        ];
    }

    public function title(): string
    {
        return 'Company Address';
    }
}
