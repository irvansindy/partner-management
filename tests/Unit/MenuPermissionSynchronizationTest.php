<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MenuPermissionSynchronizationTest extends TestCase
{
    /** @test */
    public function renaming_a_permission_keeps_menu_references_and_spatie_cache_in_sync(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/RoleAndPermissionController.php');

        $this->assertStringContainsString("Menu::where('can_permission', \$oldName)", $controller);
        $this->assertStringContainsString("->update(['can_permission' => \$newName])", $controller);
        $this->assertStringContainsString('Rule::unique', $controller);
        $this->assertStringContainsString('forgetCachedPermissions()', $controller);
        $this->assertStringContainsString('DB::transaction', $controller);
    }

    /** @test */
    public function editing_one_menu_does_not_rename_a_permission_used_by_other_menus(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/MenuController.php');
        $model = file_get_contents(dirname(__DIR__, 2).'/app/Models/Menu.php');

        $this->assertStringContainsString('Permission::firstOrCreate', $controller);
        $this->assertStringNotContainsString("\$permission->update([\n                            'name' => \$newPermission", $controller);
        $this->assertStringNotContainsString('HasPermissions', $model);
    }

    /** @test */
    public function partner_list_seed_and_upgrade_use_the_current_permission_name(): void
    {
        $seeder = file_get_contents(dirname(__DIR__, 2).'/database/seeders/MenusSeeder.php');
        $migrations = glob(dirname(__DIR__, 2).'/database/migrations/*_sync_partner_list_menu_permission.php');

        $this->assertStringContainsString("'can_permission' => 'partner-list'", $seeder);
        $this->assertStringNotContainsString("'can_permission' => 'partner-management'", $seeder);
        $this->assertCount(1, $migrations);
        $migration = file_get_contents($migrations[0]);
        $this->assertStringContainsString("where('can_permission', 'partner-management')", $migration);
        $this->assertStringContainsString("update(['can_permission' => 'partner-list'])", $migration);
    }
}
