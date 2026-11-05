<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class AddApprovalInboxMenu extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $permissionId = DB::table('permissions')
                ->where('name', 'approval-inbox')
                ->where('guard_name', 'web')
                ->value('id');

            if (!$permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => 'approval-inbox',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('menus')->updateOrInsert(
                ['url_name' => 'admin/approvals/my/pending'],
                [
                    'name_text' => 'My Approvals',
                    'can_permission' => 'approval-inbox',
                    'icon' => 'fas fa-clipboard-check',
                    'type' => 1,
                    'description' => 'Submissions assigned to the current approver.',
                    'order' => (int) DB::table('menus')->max('order') + 1,
                    'parent_id' => null,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $roleIds = DB::table('roles')
                ->whereIn('name', ['super-admin', 'admin', 'super-user'])
                ->where('guard_name', 'web')
                ->pluck('id');

            foreach ($roleIds as $roleId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::transaction(function () {
            $permissionId = DB::table('permissions')
                ->where('name', 'approval-inbox')
                ->where('guard_name', 'web')
                ->value('id');

            DB::table('menus')->where('url_name', 'admin/approvals/my/pending')->delete();

            if ($permissionId) {
                DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
                DB::table('permissions')->where('id', $permissionId)->delete();
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
