<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
class Menu extends Model
{
    use HasFactory,HasPermissions;
    protected $table = 'menus';
    protected $guarded = [];
    public function submenus()
    {
        return $this->hasMany(Submenu::class);
    }
}
