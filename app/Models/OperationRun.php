<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationRun extends Model
{
    protected $guarded = [];

    protected $casts = [
        'stats' => 'array',
        'metadata' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
