<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDepartment extends Model
{
    use HasFactory;
    protected $table = 'master_departments';
    protected $guarded = [];
    public function division()
    {
        return $this->belongsTo(MasterDivision::class, 'division_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
