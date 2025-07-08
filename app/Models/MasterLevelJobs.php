<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLevelJobs extends Model
{
    use HasFactory;
    protected $table = 'master_level_jobs';
    protected $fillable = [
        'name',
    ];
}
