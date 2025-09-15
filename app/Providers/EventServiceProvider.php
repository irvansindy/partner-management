<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Menu;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // menu
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $menus = Menu::with('children')
                ->where('type', 1)
                ->orderBy('order')
                ->get();
    
            $user = auth()->user();
    
            // Ambil semua permission dari role-role yang dimiliki user
            $rolePermissions = $user->roles
                ->flatMap(function ($role) {
                    return $role->permissions->pluck('name');
                })
                ->unique();
    
            foreach ($menus as $menu) {
                // Cek permission menu utama
                if ($menu->can_permission && !$rolePermissions->contains($menu->can_permission)) {
                    continue;
                }
    
                $menuItem = [
                    'text' => $menu->name_text,
                    'url'  => $menu->url_name,
                    'icon' => $menu->icon,
                ];
    
                // Filter submenu berdasarkan permission role
                $children = $menu->children->filter(function ($submenu) use ($rolePermissions) {
                    return !$submenu->can_permission || $rolePermissions->contains($submenu->can_permission);
                })->sortBy('order');
    
                if ($children->isNotEmpty()) {
                    $menuItem['submenu'] = $children->map(function ($submenu) {
                        return [
                            'text' => $submenu->name_text,
                            'url'  => $submenu->url_name,
                            'icon' => $submenu->icon,
                        ];
                    })->toArray();
                }
    
                $event->menu->add($menuItem);
            }
        });

    }
    

}
