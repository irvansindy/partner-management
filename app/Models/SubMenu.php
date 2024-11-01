<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

class SubMenu extends Model
{
    use HasFactory,HasPermissions;
    protected $table = 'sub_menus';
    protected $guarded = [];
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
