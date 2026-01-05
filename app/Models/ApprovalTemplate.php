<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalTemplate extends Model
{
    use HasFactory;

    protected $table = 'approval_templates';

    protected $fillable = [
        'name',
        'user_id',
        'location_id',
        'department_id',
        'step_ordering',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'step_ordering' => 'integer',
    ];

    // ========== RELATIONSHIPS ==========

    public function user()
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

    // ✅ PERBAIKAN: Tambahkan foreign key yang benar
    public function details()
    {
        return $this->hasMany(ApprovalTemplateDetail::class, 'approval_id', 'id')
                    ->orderBy('step_ordering');
    }

    // Alias untuk backward compatibility dengan nama lama
    public function master_departments()
    {
        return $this->department();
    }

    public function master_offices()
    {
        return $this->office();
    }

    public function detailApprovalModel()
    {
        return $this->details();
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeForLocation($query, $locationId, $departmentId)
    {
        return $query->where('location_id', $locationId)
                     ->where('department_id', $departmentId);
    }

    // ========== HELPER METHODS ==========

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function getTotalSteps(): int
    {
        return $this->details()->count();
    }
}