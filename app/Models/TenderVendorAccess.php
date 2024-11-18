<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderVendorAccess extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tender_vendor_accesses';
    protected $guarded = [];
}
