<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJobTitles extends Model
{
    use HasFactory;
    protected $table = 'master_job_titles';
    protected $fillable = [
        'name',
        'level_id'
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'job_title_id');
    }
    public function level()
    {
        return $this->belongsTo(MasterLevelJobs::class, 'level_id', 'id');
    }

}
