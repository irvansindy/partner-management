<?php

namespace App\View\Components\Layout;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class Sidebar extends Component
{
    public Collection $menus;
    public function __construct()
    {
        $this->menus = app(\App\Services\MenuService::class)->getAccessibleMenus();
    }
    public function isActive(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $path = trim($url, '/');
        return request()->is($path) || request()->is($path . '/*');
    }

    public function isMenuActive(array $menu): bool
    {
        if ($this->isActive($menu['url'] ?? null)) {
            return true;
        }

        foreach ($menu['children'] ?? [] as $child) {
            if ($this->isMenuActive($child)) {
                return true;
            }
        }

        return false;
    }

    public function render(): View
    {
        return view('components.layout.sidebar');
    }
}
