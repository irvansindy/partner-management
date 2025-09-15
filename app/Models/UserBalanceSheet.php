<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class UserBalanceSheet extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $table = 'user_balance_sheets';
    protected $guarded = [];
}
