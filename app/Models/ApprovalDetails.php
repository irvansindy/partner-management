<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalDetails extends Model
{
    use HasFactory;
    protected $table = 'approval_details';
    protected $fillable = [
        'approval_id',
        'user_id',
        'step_ordering',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function approvalMaster()
    {
        return $this->belongsTo(ApprovalMaster::class,'approval_id', 'id');
    }
}
