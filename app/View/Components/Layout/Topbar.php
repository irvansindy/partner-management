<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;
use Illuminate\View\View;

class Topbar extends Component
{
    public int $unreadNotificationCount;

    public function __construct()
    {
        // TODO: ganti dengan query asli setelah tabel notifikasi ada.
        // Contoh nanti: Auth::user()->unreadNotifications()->count();
        $this->unreadNotificationCount = 0;
    }
    public function recentNotifications()
    {
        // TODO: ganti dengan query asli, contoh:
        // return Auth::user()->notifications()->latest()->take(5)->get();
        return collect();
    }
    public function render(): View
    {
        return view('components.layout.topbar');
    }
}