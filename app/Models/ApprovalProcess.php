<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalProcess extends Model
{
    use HasFactory;

    protected $table = 'approval_processes';

    protected $fillable = [
        'company_information_id',
        'user_id',
        'location_id',
        'department_id',
        'step_ordering',
        'status'
    ];

    protected $casts = [
        'step_ordering' => 'integer',
        'status' => 'integer',
    ];

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    // ========== RELATIONSHIPS ==========

    public function steps()
    {
        return $this->hasMany(ApprovalProcessStep::class, 'approval_id', 'id')
                    ->orderBy('step_ordering');
    }

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_information_id', 'id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(MasterDepartment::class, 'department_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo(MasterOffice::class, 'location_id', 'id');
    }

    // ========== SCOPES ==========

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [self::STATUS_APPROVED, self::STATUS_REJECTED]);
    }

    // ========== HELPER METHODS ==========

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getCurrentStep()
    {
        return $this->steps()->where('status', ApprovalProcessStep::STATUS_WAITING)->first();
    }

    public function getCompletedStepsCount(): int
    {
        return $this->steps()->where('status', ApprovalProcessStep::STATUS_APPROVED)->count();
    }

    public function getTotalStepsCount(): int
    {
        return $this->steps()->count();
    }

    public function getProgressPercentage(): float
    {
        $total = $this->getTotalStepsCount();
        if ($total === 0) return 0;

        return ($this->getCompletedStepsCount() / $total) * 100;
    }
}