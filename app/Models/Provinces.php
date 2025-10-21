<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    use HasFactory;
    protected $table = 'provinces';
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];
    public function regencies()
    {
        return $this->hasMany(Regencies::class, 'province_id', 'id');
    }
    // relasi ke alamat perusahaan berdasarkan nama provinsi
    public function companyAddresses()
    {
        return $this->hasMany(CompanyAddress::class, 'province', 'id');
    }
}
