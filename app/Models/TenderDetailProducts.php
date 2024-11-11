<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TenderDetailProducts extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tender_detail_products';
    protected $guarded = [];

}
