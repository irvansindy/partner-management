<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalanceSheet extends Model
{
    use HasFactory;
    protected $table = 'user_balance_sheets';
    protected $guarded = [];
}
