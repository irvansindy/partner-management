<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAdditionalInformation extends Model
{
    use HasFactory;
    protected $table = 'company_additional_information';
    protected $fillable = [
        'company_id',
        'type_branch',
        'country',
        'province',
        'city',
        'zip_code',
        // 'telephone_country_code',
        'telephone',
        // 'fax_country_code',
        'fax',
        'main_product_name_and_brand',
        'main_customer',
        'main_customer_telephone'
    ];
}
