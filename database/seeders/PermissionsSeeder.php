<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'dashboard',
            '#',
            'menu-setting',
            'user-manage',
            'role-permission',
            'office-setting',
            'department-setting',
            'partner-list',
            'ip-whitelist',
            'approval-setting',
            'admin/form-links',
            'admin/company/export/',
            'admin/company/import/',
            'approval-inbox',
            'system-maintenance',
            'division-setting',
        ] as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
