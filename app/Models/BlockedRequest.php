<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedRequest extends Model
{
    use HasFactory;
    protected $table = 'blocked_requests';
    protected $fillable = [
        'type',
        'ip',
        'country',
        'city',
        'state',
        'timezone',
        'lat',
        'lon',
        'user_agent',
        'url',
        'extra',
        'time',
    ];
    protected $casts = [
        'user_agent' => 'array',
        'extra' => 'array',
    ];
}
