<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ApprovalInboxMenuProvisioningTest extends TestCase
{
    /** @test */
    public function migration_provisions_the_approval_inbox_for_approver_roles(): void
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/*_add_approval_inbox_menu.php');

        $this->assertCount(1, $files);
        $migration = file_get_contents($files[0]);
        $this->assertStringContainsString("'approval-inbox'", $migration);
        $this->assertStringContainsString("'admin/approvals/my/pending'", $migration);
        $this->assertStringContainsString("['super-admin', 'admin', 'super-user']", $migration);
        $this->assertStringContainsString('updateOrInsert', $migration);
        $this->assertStringContainsString('insertOrIgnore', $migration);
        $this->assertStringContainsString('forgetCachedPermissions()', $migration);
    }

    /** @test */
    public function seeders_include_the_approval_inbox_menu_and_permission(): void
    {
        $root = dirname(__DIR__, 2);
        $menus = file_get_contents($root.'/database/seeders/MenusSeeder.php');
        $permissions = file_get_contents($root.'/database/seeders/PermissionsSeeder.php');

        $this->assertStringContainsString("'name_text' => 'My Approvals'", $menus);
        $this->assertStringContainsString("'url_name' => 'admin/approvals/my/pending'", $menus);
        $this->assertStringContainsString("'can_permission' => 'approval-inbox'", $menus);
        $this->assertStringContainsString("'approval-inbox'", $permissions);
    }
}
