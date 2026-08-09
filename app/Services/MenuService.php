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
                return !$menu->can_permission || $user->can($menu->can_permission);
            });

        return $this->buildMenuTree($menus);
    }

    private function buildMenuTree($menus, $parentId = null)
    {
        return $menus->filter(function ($menu) use ($parentId) {
            return $menu->parent_id == $parentId;
        })->map(function ($menu) use ($menus) {
            $url = $menu->url_name;
            if ($url === '#' || $url === '') {
                $url = null;
            }

            return [
                'id' => $menu->id,
                'name' => $menu->name_text,
                'url' => $url,
                'icon' => $menu->icon,
                'parent_id' => $menu->parent_id,
                'order' => $menu->order,
                'children' => $this->buildMenuTree($menus, $menu->id)->values()
            ];

        })->values();
    }
}