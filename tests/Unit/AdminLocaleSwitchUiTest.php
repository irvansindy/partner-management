<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminLocaleSwitchUiTest extends TestCase
{
    public function test_admin_navbars_use_accessible_locale_switch_and_remove_floating_control(): void
    {
        $partialPath = resource_path('views/vendor/adminlte/partials/navbar/locale-switch.blade.php');

        $this->assertFileExists($partialPath);

        $partial = file_get_contents($partialPath);
        $navbar = file_get_contents(resource_path('views/vendor/adminlte/partials/navbar/navbar.blade.php'));
        $topNavbar = file_get_contents(resource_path('views/vendor/adminlte/partials/navbar/navbar-layout-topnav.blade.php'));
        $applicationTopbar = file_get_contents(resource_path('views/components/layout/topbar.blade.php'));
        $page = file_get_contents(resource_path('views/vendor/adminlte/page.blade.php'));

        $this->assertStringContainsString('role="switch"', $partial);
        $this->assertStringContainsString('aria-label=', $partial);
        $this->assertStringContainsString('>ID<', $partial);
        $this->assertStringContainsString('>EN<', $partial);
        $this->assertStringContainsString('admin-locale-switch__track', $partial);
        $this->assertSame(2, substr_count($partial, 'class="admin-locale-switch__option"'));
        $this->assertStringContainsString("app()->getLocale() === 'en'", $partial);
        $this->assertStringContainsString("route('locale.switch'", $partial);
        $this->assertStringContainsString("'return_to' => request()->getRequestUri()", $partial);
        $this->assertStringContainsString('data-id-url=', $partial);
        $this->assertStringContainsString('data-en-url=', $partial);
        $this->assertStringContainsString("addEventListener('change'", $partial);
        $this->assertStringContainsString(':focus-visible', $partial);

        $include = "@include('adminlte::partials.navbar.locale-switch')";
        $this->assertSame(1, substr_count($navbar, $include));
        $this->assertSame(1, substr_count($topNavbar, $include));
        $this->assertSame(1, substr_count($applicationTopbar, $include));
        $this->assertLessThan(strpos($navbar, '{{-- User menu link --}}'), strpos($navbar, $include));
        $this->assertLessThan(strpos($topNavbar, '{{-- User menu link --}}'), strpos($topNavbar, $include));
        $this->assertLessThan(strpos($applicationTopbar, '{{-- User dropdown --}}'), strpos($applicationTopbar, $include));

        $this->assertStringNotContainsString('floating-locale-btn', $page);
        $this->assertStringNotContainsString('dropdownMenuLink', $page);
    }
}
