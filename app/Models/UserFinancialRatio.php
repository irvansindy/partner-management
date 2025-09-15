<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class UserFinancialRatio extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $table = 'user_financial_ratios';
    protected $guarded = [];
}
