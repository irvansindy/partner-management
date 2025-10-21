<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class ProductCustomer extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $fillable = [
        'company_id',
        'name',
        'merk',
        'distributor',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_id', 'id');
    }
}
