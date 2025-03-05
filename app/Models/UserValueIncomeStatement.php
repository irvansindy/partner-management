<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValueIncomeStatement extends Model
{
    use HasFactory;
    protected $table = 'user_value_income_statements';
    protected $guarded = [];
}
