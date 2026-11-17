<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};
use Spatie\Permission\Models\{Permission, Role};
use Tests\TestCase;

class PortableDatabaseSeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        (require database_path('migrations/2024_03_05_131125_create_permission_tables.php'))->up();
        $tables = [
            'users' => ['name','email','password','office_id','department_id','division_id','remember_token'],
            'master_offices' => ['name','address'], 'master_level_jobs' => ['name'],
            'master_job_titles' => ['name','level_id'], 'master_divisions' => ['name','gm_user_id','office_id'],
            'master_departments' => ['name','division_id'], 'master_business_classifications' => ['name'],
            'company_document_type_categories' => ['name','name_id_class'],
            'master_income_statements' => ['name'], 'master_balance_sheets' => ['name'],
            'menus' => ['name_text','url_name','can_permission','icon','type','description','order','parent_id','is_active'],
            'provinces' => ['name','code'], 'regencies' => ['name','code','province_id'],
            'postal_codes' => ['province_id','regency_id','district','village','postal_code'],
        ];
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns, $name) {
                $table->id();
                foreach ($columns as $column) {
                    if (str_ends_with($column, '_id') || in_array($column, ['type','order','is_active'])) $table->integer($column)->nullable();
                    else $table->string($column)->nullable();
                }
                $table->timestamps();
                if (in_array($name, ['provinces', 'regencies'])) $table->unique('code');
                if ($name === 'postal_codes') $table->unique(['province_id','regency_id','district','village','postal_code']);
            });
        }
    }

    public function test_default_seed_is_offline_complete_and_repeatable_with_bundled_data(): void
    {
        $this->seed(DatabaseSeeder::class);
        $counts = $this->counts();
        $this->assertSame(4, $counts['roles']);
        $this->assertSame(8, $counts['users']);
        $this->assertSame(38, $counts['provinces']);
        $this->assertSame(514, $counts['regencies']);
        $this->assertGreaterThan(83000, $counts['postal_codes']);
        foreach (['master_departments','master_divisions','master_level_jobs','master_job_titles','master_business_classifications','company_document_type_categories','master_income_statements','master_balance_sheets','menus','permissions','role_has_permissions'] as $table) {
            $this->assertGreaterThan(0, $counts[$table], $table);
        }
        $this->seed(DatabaseSeeder::class);
        $this->assertSame($counts, $this->counts());
        $this->assertTrue(Role::findByName('super-admin')->hasPermissionTo('system-maintenance'));
        foreach (['admin', 'super-user'] as $role) {
            $this->assertTrue(Role::findByName($role)->hasPermissionTo('approval-inbox'));
            $this->assertFalse(Role::findByName($role)->hasPermissionTo('system-maintenance'));
        }
        $settingId = DB::table('menus')->where('name_text','Setting')->value('id');
        $this->assertSame($settingId, DB::table('menus')->where('url_name','division-setting')->value('parent_id'));
        $this->assertDatabaseHas('postal_codes', ['postal_code' => '98411']);
    }

    public function test_bundled_snapshot_matches_recorded_checksums(): void
    {
        $directory = database_path('data/regions');
        $manifest = json_decode(file_get_contents($directory.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
        foreach ($manifest['files'] as $name => $metadata) {
            $this->assertSame($metadata['sha256'], hash_file('sha256', $directory.'/'.$name), $name);
        }
    }

    public function test_existing_ids_accounts_permissions_and_master_edits_survive_reseeding(): void
    {
        Permission::create(['id' => 40, 'name' => 'custom-permission', 'guard_name' => 'web']);
        $role = Role::create(['id' => 40, 'name' => 'user', 'guard_name' => 'web']);
        DB::table('master_offices')->insert(['id' => 42, 'name' => 'Head Office', 'address' => 'Edited address']);
        DB::table('master_level_jobs')->insert(['id' => 20, 'name' => 'Manager']);
        DB::table('master_divisions')->insert(['id' => 30, 'name' => 'FAT', 'office_id' => null]);
        DB::table('menus')->insert(['id' => 70, 'name_text' => 'Custom Menu', 'url_name' => 'custom-route', 'is_active' => 1]);
        DB::table('company_document_type_categories')->insert(['name' => 'Edited KTP Label', 'name_id_class' => 'ktp_penanggung_jawab']);
        DB::table('provinces')->insert(['id' => 90, 'code' => '36', 'name' => 'Banten']);
        DB::table('regencies')->insert(['id' => 120, 'code' => '3671', 'name' => 'Kota Tangerang', 'province_id' => 90]);
        $account = User::create(['name' => 'Changed User', 'email' => 'irvansindy@pralon.com', 'password' => Hash::make('ChangedPassword!'), 'office_id' => 42]);
        $account->assignRole($role);
        $password = $account->password;
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $account->refresh();
        $this->assertSame('Changed User', $account->name);
        $this->assertSame($password, $account->password);
        $this->assertTrue($account->hasRole('user'));
        $this->assertFalse($account->hasRole('super-admin'));
        $this->assertDatabaseHas('master_offices', ['id' => 42, 'address' => 'Edited address']);
        $this->assertDatabaseHas('permissions', ['id' => 40, 'name' => 'custom-permission']);
        $this->assertDatabaseHas('menus', ['id' => 70, 'name_text' => 'Custom Menu']);
        $this->assertSame(1, DB::table('company_document_type_categories')->where('name_id_class','ktp_penanggung_jawab')->count());
        $this->assertDatabaseHas('company_document_type_categories', ['name' => 'Edited KTP Label', 'name_id_class' => 'ktp_penanggung_jawab']);
        $this->assertDatabaseHas('provinces', ['id' => 90, 'code' => '36']);
        $this->assertDatabaseHas('regencies', ['id' => 120, 'code' => '3671', 'province_id' => 90]);
        $this->assertGreaterThan(0, DB::table('postal_codes')->where('province_id', 90)->where('regency_id', 120)->count());
        $this->assertDatabaseHas('master_job_titles', ['name' => 'Manager Purchasing', 'level_id' => 20]);
        $this->assertDatabaseHas('master_divisions', ['id' => 30, 'name' => 'FAT', 'office_id' => 42]);
        $this->assertSame(42, (int) User::where('email','advent@pralon.com')->value('office_id'));
        $settingId = DB::table('menus')->where('name_text','Setting')->value('id');
        $this->assertSame($settingId, DB::table('menus')->where('url_name','user-manage')->value('parent_id'));
    }

    public function test_missing_snapshot_fails_without_partial_master_writes(): void
    {
        config(['seeding.regions.directory' => '/tmp/nonexistent-seed-snapshot']);
        try { $this->seed(DatabaseSeeder::class); $this->fail('Missing source accepted'); }
        catch (\InvalidArgumentException $e) { $this->assertStringContainsString('File sumber', $e->getMessage()); }
        $this->assertSame(0, DB::table('roles')->count());
        $this->assertSame(0, DB::table('users')->count());
        $this->assertSame(0, DB::table('provinces')->count());
    }

    private function counts(): array
    {
        $counts = [];
        foreach (['roles','permissions','role_has_permissions','users','master_offices','master_level_jobs','master_job_titles','master_divisions','master_departments','master_business_classifications','company_document_type_categories','master_income_statements','master_balance_sheets','menus','provinces','regencies','postal_codes'] as $table) {
            $counts[$table] = DB::table($table)->count();
        }
        return $counts;
    }
}
