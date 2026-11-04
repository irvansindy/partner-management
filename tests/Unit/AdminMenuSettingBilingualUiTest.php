<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminMenuSettingBilingualUiTest extends TestCase
{
    /** @test */
    public function menu_setting_uses_matching_indonesian_and_english_copy(): void
    {
        $indonesian = require resource_path('lang/id/messages.php');
        $english = require resource_path('lang/en/messages.php');
        $index = file_get_contents(resource_path('views/admin/menus/index.blade.php'));
        $create = file_get_contents(resource_path('views/admin/menus/create.blade.php'));
        $submenu = file_get_contents(resource_path('views/admin/menus/submenu_create.blade.php'));
        $list = file_get_contents(resource_path('views/admin/menus/modal_list_submenu.blade.php'));
        $script = file_get_contents(resource_path('views/admin/menus/menu_js.blade.php'));

        foreach ([
            'Menu Setting Title',
            'Menu Setting Add Menu',
            'Menu Setting Create Menu',
            'Menu Setting List Submenus',
            'Menu Setting Select One',
            'Menu Setting Delete Confirmation',
            'Menu Setting Delete Success',
            'Menu Setting Search Placeholder',
        ] as $key) {
            $this->assertArrayHasKey($key, $indonesian);
            $this->assertArrayHasKey($key, $english);
            $this->assertNotSame($indonesian[$key], $english[$key]);
        }

        $this->assertStringContainsString("__('messages.Menu Setting Title')", $index);
        $this->assertStringContainsString("__('messages.Menu Setting Create Menu')", $create);
        $this->assertStringContainsString("__('messages.Menu Setting Create Submenu')", $submenu);
        $this->assertStringContainsString("__('messages.Menu Setting List Submenus')", $list);
        $this->assertStringContainsString("@json(__('messages.Menu Setting Select One'))", $script);
        $this->assertStringContainsString('menuText.', $script);

        $this->assertStringNotContainsString('>Menu Management<', $index);
        $this->assertStringNotContainsString('>Create Menu<', $create);
        $this->assertStringNotContainsString('>List SubMenu<', $list);
        $this->assertStringNotContainsString("title: 'Apakah Anda yakin?'", $script);
        $this->assertStringNotContainsString("placeholder: 'Search...'", $script);
    }
}
