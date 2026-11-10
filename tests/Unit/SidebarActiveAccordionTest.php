<?php

namespace Tests\Unit;

use App\View\Components\Layout\Sidebar;
use Illuminate\Http\Request;
use ReflectionClass;
use Tests\TestCase;

class SidebarActiveAccordionTest extends TestCase
{
    public function test_menu_url_with_a_trailing_slash_matches_the_current_request(): void
    {
        $this->app->instance('request', Request::create('/admin/company/export', 'GET'));
        $sidebar = (new ReflectionClass(Sidebar::class))->newInstanceWithoutConstructor();

        $this->assertTrue($sidebar->isActive('admin/company/export/'));
        $this->assertTrue($sidebar->isMenuActive([
            'url' => null,
            'children' => [
                ['url' => 'admin/company/export/', 'children' => []],
            ],
        ]));
    }

    public function test_sidebar_opens_every_parent_of_the_active_submenu(): void
    {
        $root = dirname(__DIR__, 2);
        $component = file_get_contents($root.'/app/View/Components/Layout/Sidebar.php');
        $item = file_get_contents($root.'/resources/views/components/layout/sidebar-menu-item.blade.php');

        $this->assertStringContainsString('public function isMenuActive(array $menu): bool', $component);
        $this->assertStringContainsString("trim(\$url, '/')", $component);
        $this->assertStringContainsString("foreach (\$menu['children'] ?? [] as \$child)", $component);
        $this->assertStringContainsString('if ($this->isMenuActive($child))', $component);
        $this->assertStringContainsString('$menuIsActive = $isMenuActive($menu)', $item);
        $this->assertStringContainsString("<li class=\"{{ \$menuIsActive ? 'active-page' : '' }}\">", $item);
        $this->assertStringContainsString("\$hasChildren && \$menuIsActive ? 'shown-menu' : ''", $item);
    }
}
