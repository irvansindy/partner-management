<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailApprovalModel extends Model
{
    use HasFactory;
    protected $table = 'detail_approval_models';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function masterApprovalModel()
    {
        return $this->belongsTo(MasterApprovalModel::class);
    }
}
