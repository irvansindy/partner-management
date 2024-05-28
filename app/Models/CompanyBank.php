<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
    use HasFactory;
    protected $table = 'company_banks';
    protected $fillable = [
        'company_id',
        'name',
        'branch',
        'account_name',
        'city_or_country',
        'account_number',
        'currency',
        'swift_code',
    ];
}
