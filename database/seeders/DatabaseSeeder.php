<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        try {
            DB::transaction(function () {
                $this->call([
                    RegionPostalCodeSeeder::class,
                    RoleSeeder::class,
                    PermissionsSeeder::class,
                    OfficeSeeder::class,
                    LevelJobSeeder::class,
                    TitleJobSeeder::class,
                    MasterDivisionSeeder::class,
                    MasterDepartements::class,
                    BusinessClassificationSeeder::class,
                    CompanyAddInfoSeeder::class,
                    MasterIncomeStatement::class,
                    MasterBalanceSheet::class,
                    MenusSeeder::class,
                    RolePermissionSeeder::class,
                    UserSeeder::class,
                ]);
            });
        } finally {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
