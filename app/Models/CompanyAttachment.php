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
        'filepath',
        'filesize',
        'filetype',
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
