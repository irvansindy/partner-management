<?php
namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public function getAccessibleMenus()
    {
        $user = Auth::user();

        $menus = Menu::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->filter(function ($menu) use ($user) {
                return !$menu->permission_name || $user->can($menu->permission_name);
            });

        return $this->buildMenuTree($menus);
    }

    private function buildMenuTree($menus, $parentId = null)
    {
        return $menus->filter(function ($menu) use ($parentId) {
            return $menu->parent_id == $parentId;
        })->map(function ($menu) use ($menus) {
            $menu->children = $this->buildMenuTree($menus, $menu->id);
            return $menu;
        });
    }
}