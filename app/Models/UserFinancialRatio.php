<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFinancialRatio extends Model
{
    use HasFactory;
    protected $table = 'user_financial_ratios';
    protected $guarded = [];
}
