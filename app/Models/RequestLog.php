<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;
    protected $table = "request_logs";
    protected $fillable = [
        'type', 'ip', 'user_agent', 'url', 'extra', 'time'
    ];

    protected $casts = [
        'user_agent' => 'array',
        'extra'      => 'array',
        'time'       => 'datetime',
    ];
}
