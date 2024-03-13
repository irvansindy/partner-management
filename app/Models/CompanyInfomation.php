<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfomation extends Model
{
    use HasFactory;

    protected $table = 'company_infomations';
    protected $guarded = [];
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function address()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id', 'id');
    }
    public function bank()
    {
        return $this->hasMany(CompanyBank::class, 'company_id', 'id');
    }
    public function tax()
    {
        return $this->hasMany(CompanyTax::class, 'company_id', 'id');
    }
    public function add_info()
    {
        return $this->hasMany(CompanyAdditionalInformation::class, 'company_id', 'id');
    }
}
