<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class CompanyContact extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $fillable = [
        'company_informations_id',
        'name',
        'department',
        'position',
        'email',
        'telephone'
    ];
    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_informations_id', 'id');
    }
}
