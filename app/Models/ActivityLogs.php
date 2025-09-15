<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $fillable = [
        'master_activity_log_id',
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'data',
        'ip_address',
    ];
    protected $casts = [
        'data' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function logs()
    {
        return $this->belongsTo(MasterActivityLog::class, 'id', 'master_activity_log_id');
    }
}
