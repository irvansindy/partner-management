<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [1, 'dashboard', '2025-10-20 02:44:18'],
            [2, '#', '2025-10-20 02:46:12'],
            [3, 'menu-setting', '2025-10-20 02:53:45'],
            [4, 'user-manage', '2025-10-20 02:56:19'],
            [5, 'role-permission', '2025-10-20 03:09:06'],
            [6, 'office-setting', '2025-10-20 03:11:20'],
            [7, 'department-setting', '2025-10-20 03:16:38'],
            [8, 'partner-management', '2025-10-20 03:45:19'],
            [9, 'api-whitelist', '2025-10-20 04:02:10'],
            [10, 'approval-setting', '2025-10-20 04:14:12'],
            [11, 'admin/form-links', '2025-11-11 14:20:14'],
            [12, 'admin/company/export/', '2026-01-14 11:08:31'],
            [13, 'admin/company/import/', '2026-01-14 11:10:41'],
        ];

        $data = [];

        foreach ($permissions as $perm) {
            $data[] = [
                'id' => $perm[0],
                'name' => $perm[1],
                'guard_name' => 'web',
                'created_at' => Carbon::parse($perm[2]),
                'updated_at' => Carbon::parse($perm[2]),
            ];
        }

        DB::table('permissions')->upsert(
            $data,
            ['id'], // unique key
            ['name', 'guard_name', 'updated_at'] // fields to update
        );
    }
}