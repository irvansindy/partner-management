<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActivityLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'action' => 'array',   // <— penting, agar otomatis diserialisasi ke JSON
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function logs()
    {
        return $this->hasMany(ActivityLogs::class, "master_activity_log_id","id");
    }
}
