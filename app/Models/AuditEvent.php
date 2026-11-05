<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function changes()
    {
        return $this->hasMany(AuditEventChange::class);
    }

    public function subject()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}
