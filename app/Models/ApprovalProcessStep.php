<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalProcessStep extends Model
{
    use HasFactory;

    protected $table = 'approval_process_steps';

    protected $fillable = [
        'approval_id',
        'user_id',
        'step_ordering',
        'status',
        'notes',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'step_ordering' => 'integer',
        'status' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_WAITING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    // ========== RELATIONSHIPS ==========

    public function process()
    {
        return $this->belongsTo(ApprovalProcess::class, 'approval_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ========== SCOPES ==========

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ========== HELPER METHODS ==========

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function approve(string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'notes' => $notes,
            'approved_at' => now()
        ]);
    }

    public function reject(string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'notes' => $notes,
            'rejected_at' => now()
        ]);
    }
}