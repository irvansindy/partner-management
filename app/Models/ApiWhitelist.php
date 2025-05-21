<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiWhitelist extends Model
{
    use HasFactory;
    protected $table = 'api_whitelists';

    protected $fillable = ['ip_address', 'description'];
}
