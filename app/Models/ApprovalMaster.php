<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalMaster extends Model
{
    use HasFactory;
    protected $table = 'approval_masters';
    protected $fillable = [
        'company_information_id',
        'user_id',
        'location_id',
        'department_id',
        'step_ordering',
        'status'
    ];

    public function approval()
    {
        return $this->hasMany(ApprovalDetails::class, 'approval_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(CompanyInformation::class, 'company_information_id', 'id');
    }
}
