<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterIncomeStatement extends Model
{
    use HasFactory;
    protected $table = 'master_income_statements';
    protected $guarded = [];
}
