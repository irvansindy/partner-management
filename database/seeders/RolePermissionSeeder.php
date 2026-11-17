<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Role::findByName('super-admin', 'web')->givePermissionTo(Permission::where('guard_name', 'web')->get());
        $permissions = ['dashboard', '#', 'partner-list', 'admin/form-links', 'admin/company/export/', 'admin/company/import/', 'approval-inbox'];
        foreach (['admin', 'super-user'] as $role) {
            Role::findByName($role, 'web')->givePermissionTo($permissions);
        }
    }
}
