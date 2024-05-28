<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add some items to the menu...
            $event->menu->add('MAIN MENU');
            $event->menu->add([
                'text' => 'Company Data',
            'url' => 'detail-partner',
            'icon' => 'fas fa-fw fa-building',
            'topnav_user' => true,
            ],);
            $event->menu->add([
                'text' => 'User Management',
                'url'  => 'user-manage',
                'icon' => 'fas fa-fw fa-user',
            ],);
            $event->menu->add([
                'text' => 'Role Permission',
                'url'  => 'role-permission',
                'icon' => 'fas fa-fw fa-tasks',
            ],);
            $event->menu->add([
                'text' => 'Partner',
                'url'  => 'partner',
                'icon' => 'fas fa-fw fa-handshake',
            ],);
        });
    }
}
