<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AdminTopbarCleanupTest extends TestCase
{
    public function test_admin_topbar_excludes_unused_search_notifications_and_right_sidebar(): void
    {
        $root = dirname(__DIR__, 2).'/resources/views/';
        $topbar = file_get_contents($root.'components/layout/topbar.blade.php');
        $layout = file_get_contents($root.'layouts/main.blade.php');

        $this->assertStringNotContainsString('class="form-inline my-2 my-lg-0 search"', $topbar);
        $this->assertStringNotContainsString('search-link', $topbar);
        $this->assertStringNotContainsString('notifications_none', $topbar);
        $this->assertStringNotContainsString('right-sidebar-link', $topbar);
        $this->assertStringNotContainsString('more_vert', $topbar);
        $this->assertStringNotContainsString('class="search-results"', $layout);
        $this->assertStringNotContainsString('class="page-right-sidebar"', $layout);
        $this->assertStringNotContainsString('class="chat-sidebar"', $layout);

        $this->assertStringContainsString("@include('adminlte::partials.navbar.locale-switch')", $topbar);
        $this->assertStringContainsString('id="userDropdown"', $topbar);
        $this->assertStringContainsString('navigation-toggle', $topbar);
    }
}
