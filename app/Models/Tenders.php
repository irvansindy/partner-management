<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tenders extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tenders';
    protected $guarded = [];
    public function detailProduct()
    {
        return $this->hasMany(TenderDetailProducts::class, 'tender_id','id');
    }
    public function vendorSubmission()
    {
        return $this->hasMany(TenderVendorSubmissions::class,'tender_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function eula()
    {
        return $this->belongsTo(EndUserLicenseAgreement::class,'eula_tnc_id','id');
    }
}
