<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class UserValueIncomeStatement extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $table = 'user_value_income_statements';
    protected $guarded = [];
}
