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

        $path = ltrim($url, '/');
        return request()->is($path) || request()->is($path . '/*');
    }

    public function render(): View
    {
        return view('components.layout.sidebar');
    }
}