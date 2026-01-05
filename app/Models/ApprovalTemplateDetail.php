<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalTemplateDetail extends Model
{
    use HasFactory;

    protected $table = 'approval_template_details';

    protected $fillable = [
        'approval_template_id', // ✅ Pastikan ini ada
        'user_id',
        'step_ordering',
        'status'
    ];

    protected $casts = [
        'step_ordering' => 'integer',
        'status' => 'integer',
    ];

    // ========== RELATIONSHIPS ==========

    // ✅ PERBAIKAN: Foreign key yang benar
    public function template()
    {
        return $this->belongsTo(ApprovalTemplate::class, 'approval_template_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Alias untuk backward compatibility
    public function masterApprovalModel()
    {
        return $this->template();
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('status', 0); // 0 = active in template
    }

    public function scopeForTemplate($query, $templateId)
    {
        return $query->where('approval_id', $templateId)
                     ->orderBy('step_ordering');
    }
}