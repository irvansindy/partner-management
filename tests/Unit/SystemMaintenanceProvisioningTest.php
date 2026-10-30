<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SystemMaintenanceProvisioningTest extends TestCase
{
    /** @test */
    public function migration_provisions_the_menu_permission_and_super_admin_assignment_idempotently(): void
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/*_add_system_maintenance_menu.php');
        $this->assertCount(1, $files);
        $migration = file_get_contents($files[0]);

        $this->assertStringContainsString("'system-maintenance'", $migration);
        $this->assertStringContainsString("'super-admin'", $migration);
        $this->assertStringContainsString("'admin/system-maintenance'", $migration);
        $this->assertStringContainsString('updateOrInsert', $migration);
        $this->assertStringContainsString('insertOrIgnore', $migration);
        $this->assertStringNotContainsString("'admin'", str_replace("'super-admin'", '', $migration));
    }

    /** @test */
    public function menu_seeder_contains_the_system_maintenance_entry(): void
    {
        $seeder = file_get_contents(dirname(__DIR__, 2).'/database/seeders/MenusSeeder.php');

        $this->assertStringContainsString("'name_text' => 'System Maintenance'", $seeder);
        $this->assertStringContainsString("'url_name' => 'admin/system-maintenance'", $seeder);
        $this->assertStringContainsString("'can_permission' => 'system-maintenance'", $seeder);
    }
}
