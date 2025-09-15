<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\CompanyInformationExport;
use App\Exports\Sheets\CompanyAddressExport;
use App\Exports\Sheets\CompanyBankExport;
use App\Exports\Sheets\CompanyTaxExport;
class CompanyExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
        
    // }
    public function sheets(): array
    {
        return [
            new CompanyInformationExport(),
            new CompanyAddressExport(),
            new CompanyBankExport(),
            new CompanyTaxExport(),
        ];
    }
}
