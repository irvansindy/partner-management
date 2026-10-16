<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class CompanyAttachment extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $fillable = [
        'company_id',
        'filename',
        'original_name',
        'filepath',
        'storage_disk',
        'filesize',
        'filetype',
        'mime_type',
        'width',
        'height',
        'sha256',
        'sort_order'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }

    // Accessor untuk get full URL
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->filepath);
    }
}
