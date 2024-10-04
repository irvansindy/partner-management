<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterApprovalModel extends Model
{
    use HasFactory;
    protected $table = 'master_approval_models';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function master_departments()
    {
        return $this->hasOne(MasterDepartment::class, 'id','department_id');
    }
    public function master_offices()
    {
        return $this->hasOne(MasterOffice::class, 'id', 'location_id');
    }
    public function detailApprovalModel()
    {
        return $this->hasMany(DetailApprovalModel::class, 'approval_id', 'id');
    }
}
