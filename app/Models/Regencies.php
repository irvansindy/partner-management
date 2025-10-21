<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regencies extends Model
{
    use HasFactory;
    protected $table = 'regencies';
    protected $fillable = [
        'province_id',
        'name',
        'latitude',
        'longitude',
    ];

    public function province()
    {
        return $this->belongsTo(Provinces::class, 'province_id', 'id');
    }
    public function companyAddresses()
    {
        return $this->hasMany(CompanyAddress::class, 'city', 'name');
    }
}
