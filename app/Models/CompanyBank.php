<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class CompanyBank extends Model
{
    use HasFactory, HasInsertWithLog;
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

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_id', 'id');
    }
}
