<?php

namespace App\Exports\Sheets;

use App\Models\CompanyInformation;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithTitle};

class CompanyInformationExport implements FromCollection, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CompanyInformation::select(
            'id',
            'user_id',
            'name',
            'group_name',
            'type',
            'established_year',
            'total_employee',
            'liable_person_and_position',
            'owner_name',
            'board_of_directors',
            'major_shareholders',
            'business_classification',
            'business_classification_detail',
            'other_business',
            'website_address',
            'system_management',
            'contact_person',
            'communication_language',
            'email_address',
            'remark',
            'signature',
            'stamp',
            'supplier_number',
            'status',
            'location_id',
            'department_id',
            'blacklist',
            'created_at',
            'updated_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Name',
            'Group Name',
            'Type',
            'Established Year',
            'Total Employee',
            'Liable Person and Position',
            'Owner Name',
            'Board of Directors',
            'Major Shareholders',
            'Business Classification',
            'Business Classification Detail',
            'Other Business',
            'Website Address',
            'System Management',
            'Contact Person',
            'Communication Language',
            'Email Address',
            'Remark',
            'Signature',
            'Stamp',
            'Supplier Number',
            'Status',
            'Location ID',
            'Department ID',
            'Blacklist',
            'Created At',
            'Updated At'
        ];
    }

    public function title(): string
    {
        return 'Company Information';
    }
}
