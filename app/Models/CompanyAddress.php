<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory;
    protected $table = 'company_addresses';
    protected $fillable = [
        'company_id',
        'address',
        'country',
        'province',
        'city',
        'zip_code',
        'telephone',
        'fax',
    ];
}
