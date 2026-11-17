<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class FormLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'token',
        'department_id',
        'office_id',
        'form_type',
        'title',
        'description',
        'is_active',
        'expires_at',
        'max_submissions',
        'submission_count',
        'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getDescriptionAttribute($value): string
    {
        $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/<\s*br\s*\/?\s*>|<\s*\/\s*(?:p|div|li|h[1-6])\s*>/i', "\n", $text);

        return trim(strip_tags($text));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formLink) {
            if (empty($formLink->token)) {
                $formLink->token = Str::random(40);
            }
        });
    }
    public function companies()
    {
        return $this->hasMany(CompanyInformation::class, 'form_link_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function department()
    {
        return $this->belongsTo(MasterDepartment::class, 'department_id', 'id');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasReachedMaxSubmissions()
    {
        return $this->max_submissions && $this->submission_count >= $this->max_submissions;
    }

    public function canAcceptSubmission()
    {
        return $this->is_active &&
               !$this->isExpired() &&
               !$this->hasReachedMaxSubmissions();
    }

    public function getPublicUrl()
    {
        return route('public.form.show', $this->token);
    }

    public function incrementSubmissionCount()
    {
        $this->increment('submission_count');
    }
}
