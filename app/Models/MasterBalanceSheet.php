<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBalanceSheet extends Model
{
    use HasFactory;
    protected $table = 'master_balance_sheets';
    protected $guarded = [];
}
