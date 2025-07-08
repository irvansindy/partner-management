<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDivision extends Model
{
    use HasFactory;
    protected $table = 'master_divisions';
    protected $fillable = ['code', 'name', 'gm_user_id', 'office_id'];
    public function gm()
    {
        return $this->belongsTo(User::class, 'gm_user_id');
    }
    public function departments()
    {
        return $this->hasMany(MasterDepartment::class, 'division_id');
    }
}
