<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalMaster extends Model
{
    use HasFactory;
    protected $table = 'approval_masters';
    protected $guarded = [];
}
