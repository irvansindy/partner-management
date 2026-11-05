<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditEventChange extends Model
{
    protected $guarded = [];

    protected $casts = [
        'old_value' => 'json',
        'new_value' => 'json',
        'sensitive' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(AuditEvent::class, 'audit_event_id');
    }
}
