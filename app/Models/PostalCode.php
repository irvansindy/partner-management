<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $fillable = ['province_id', 'regency_id', 'district', 'village', 'postal_code'];

    public function province() { return $this->belongsTo(Provinces::class); }
    public function regency() { return $this->belongsTo(Regencies::class); }
}
