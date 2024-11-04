<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndUserLicenseAgreement extends Model
{
    use HasFactory;
    protected $table = 'end_user_license_agreements';
    protected $guarded = [];
}
