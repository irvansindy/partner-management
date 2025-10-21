<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSurvey extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'ownership_status',
        'rental_year',
        'pick_up',
        'truck'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
