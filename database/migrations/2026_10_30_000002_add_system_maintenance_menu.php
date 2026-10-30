<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class AddSystemMaintenanceMenu extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $permissionId = DB::table('permissions')
                ->where('name', 'system-maintenance')
                ->where('guard_name', 'web')
                ->value('id');

            if (!$permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => 'system-maintenance',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('menus')->updateOrInsert(
                ['url_name' => 'admin/system-maintenance'],
                [
                    'name_text' => 'System Maintenance',
                    'can_permission' => 'system-maintenance',
                    'icon' => 'fas fa-tools',
                    'type' => 1,
                    'description' => 'Run approved application maintenance commands.',
                    'order' => (int) DB::table('menus')->max('order') + 1,
                    'parent_id' => null,
                    'is_active' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $superAdminId = DB::table('roles')
                ->where('name', 'super-admin')
                ->where('guard_name', 'web')
                ->value('id');

            if ($superAdminId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $superAdminId,
                ]);
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::transaction(function () {
            $permissionId = DB::table('permissions')
                ->where('name', 'system-maintenance')
                ->where('guard_name', 'web')
                ->value('id');

            DB::table('menus')->where('url_name', 'admin/system-maintenance')->delete();

            if ($permissionId) {
                DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
                DB::table('permissions')->where('id', $permissionId)->delete();
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
