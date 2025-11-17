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
