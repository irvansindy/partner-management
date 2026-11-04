<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminRolePermissionBilingualUiTest extends TestCase
{
    /** @test */
    public function role_permission_renders_and_uses_matching_bilingual_copy(): void
    {
        $indonesian = require resource_path('lang/id/messages.php');
        $english = require resource_path('lang/en/messages.php');
        $index = file_get_contents(resource_path('views/admin/role_and_permission/index.blade.php'));
        $script = file_get_contents(resource_path('views/admin/role_and_permission/role_permission_js.blade.php'));

        foreach ([
            'Role Permission Title',
            'Role Permission Description',
            'Role Permission Role Management',
            'Role Permission Permission Management',
            'Role Permission Create Role',
            'Role Permission Create Permission',
            'Role Permission Manage Permissions',
            'Role Permission Save Success',
        ] as $key) {
            $this->assertArrayHasKey($key, $indonesian);
            $this->assertArrayHasKey($key, $english);
            $this->assertNotSame($indonesian[$key], $english[$key]);
        }

        $this->assertStringContainsString("__('messages.Role Permission Title')", $index);
        $this->assertStringContainsString("@json(__('messages.Role Permission Success'))", $script);
        $this->assertStringContainsString('rolePermissionText.', $script);
        $this->assertStringNotContainsString('>Role Management<', $index);
        $this->assertStringNotContainsString("title: 'Success'", $script);

        $this->assertTrue(view()->exists('admin.role_and_permission.index'));
        $this->assertTrue(view()->exists('admin.role_and_permission.add_permission'));
    }
}
