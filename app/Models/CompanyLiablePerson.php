<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInsertWithLog;
class CompanyLiablePerson extends Model
{
    use HasFactory, HasInsertWithLog;
    protected $table = "company_liable_people";
    protected $fillable = [
        'company_id',
        'name',
        'nik',
        'position',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
