<?php

namespace Tests\Feature;

use App\Models\PostalCode;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Services\ValteraRegionImportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ValteraRegionImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->nullable()->unique();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 4)->nullable()->unique();
            $table->foreignId('province_id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id');
            $table->foreignId('regency_id');
            $table->string('district');
            $table->string('village');
            $table->string('postal_code', 5);
            $table->timestamps();
            $table->unique(['province_id', 'regency_id', 'district', 'village', 'postal_code']);
        });
    }

    /** @test */
    public function it_imports_valtera_hierarchy_by_codes_idempotently(): void
    {
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n36,Banten\n",
            'regencies.csv' => "code,province_code,name\n3671,36,Kota Tangerang\n",
            'districts.csv' => "code,regency_code,name\n367101,3671,Tangerang\n",
            'villages.csv' => "code,district_code,name,postal_code\n3671011001,367101,Sukasari,15118\n",
        ]);

        $service = app(ValteraRegionImportService::class);
        $first = $service->import($directory);
        $second = $service->import($directory);

        $this->assertSame(1, $first['postal_codes']['inserted']);
        $this->assertSame(1, $second['postal_codes']['existing']);
        $this->assertDatabaseHas('provinces', ['code' => '36', 'name' => 'Banten']);
        $this->assertDatabaseHas('regencies', ['code' => '3671', 'name' => 'Kota Tangerang']);
        $this->assertDatabaseHas('postal_codes', ['district' => 'Tangerang', 'village' => 'Sukasari', 'postal_code' => '15118']);
    }

    /** @test */
    public function it_reuses_legacy_city_and_regency_rows_with_the_same_base_name(): void
    {
        $province = Provinces::create(['name' => 'Banten']);
        Regencies::create(['province_id' => $province->id, 'name' => 'Tangerang']);
        Regencies::create(['province_id' => $province->id, 'name' => 'Kabupaten Tangerang']);
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n36,Banten\n",
            'regencies.csv' => "code,province_code,name\n3603,36,Kab. Tangerang\n3671,36,Kota Tangerang\n",
            'districts.csv' => "code,regency_code,name\n360301,3603,Balaraja\n367101,3671,Tangerang\n",
            'villages.csv' => "code,district_code,name,postal_code\n3603011001,360301,Balaraja,15610\n3671011001,367101,Sukasari,15118\n",
        ]);

        app(ValteraRegionImportService::class)->import($directory);

        $this->assertSame(2, Regencies::count());
        $this->assertDatabaseHas('regencies', ['name' => 'Kabupaten Tangerang', 'code' => '3603']);
        $this->assertDatabaseHas('regencies', ['name' => 'Tangerang', 'code' => '3671']);
    }

    /** @test */
    public function it_repairs_valtera_papua_barat_daya_codes_and_blank_names(): void
    {
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n92,Papua Barat\n",
            'regencies.csv' => "code,province_code,name\n9201,92,\n9271,92,\n",
            'districts.csv' => "code,regency_code,name\n920101,9201,\n927101,9271,\n",
            'villages.csv' => "code,district_code,name,postal_code\n9201011001,920101,Makbon,\n9271011001,927101,Klawasi,\n",
        ]);
        $supplement = $this->csv("no,kode_pos,kode_wilayah,prov_code,kab_code,kec_code,desa_code,full_code,is_kelurahan,is_desa,desa,kecamatan,dt2,kabupaten,provinsi,source_page\n1,98452,92.01.01.1001,92,01,01,1001,9201011001,1,0,Makbon,Makbon,Kabupaten,Sorong,Papua Barat Daya,216\n2,98411,92.71.01.1001,92,71,01,1001,9271011001,1,0,Klawasi,Sorong,Kota,Sorong,Papua Barat Daya,216\n");

        app(ValteraRegionImportService::class)->import($directory, false, $supplement);

        $southwest = Provinces::where('code', '96')->firstOrFail();
        $this->assertSame('Papua Barat Daya', $southwest->name);
        $this->assertDatabaseHas('regencies', ['province_id' => $southwest->id, 'code' => '9601', 'name' => 'Kab. Sorong']);
        $this->assertDatabaseHas('regencies', ['province_id' => $southwest->id, 'code' => '9671', 'name' => 'Kota Sorong']);
        $this->assertSame(2, PostalCode::count());
    }

    /** @test */
    public function it_rejects_unknown_blank_region_names(): void
    {
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n36,Banten\n",
            'regencies.csv' => "code,province_code,name\n3601,36,\n",
            'districts.csv' => "code,regency_code,name\n",
            'villages.csv' => "code,district_code,name,postal_code\n",
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('regencies.csv baris 2');

        app(ValteraRegionImportService::class)->import($directory);
    }

    /** @test */
    public function artisan_command_can_validate_the_source_without_writing(): void
    {
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n36,Banten\n",
            'regencies.csv' => "code,province_code,name\n3671,36,Kota Tangerang\n",
            'districts.csv' => "code,regency_code,name\n367101,3671,Tangerang\n",
            'villages.csv' => "code,district_code,name,postal_code\n3671011001,367101,Sukasari,15118\n",
        ]);

        $this->artisan('regions:import-valtera', ['directory' => $directory, '--dry-run' => true])
            ->expectsOutput('Validasi sumber berhasil. Tidak ada data yang ditulis.')
            ->assertExitCode(0);

        $this->assertSame(0, Provinces::count());
        $this->assertSame(0, PostalCode::count());
    }

    /** @test */
    public function dry_run_does_not_require_a_database_connection(): void
    {
        $directory = $this->dataset([
            'provinces.csv' => "code,name\n36,Banten\n",
            'regencies.csv' => "code,province_code,name\n3671,36,Kota Tangerang\n",
            'districts.csv' => "code,regency_code,name\n367101,3671,Tangerang\n",
            'villages.csv' => "code,district_code,name,postal_code\n3671011001,367101,Sukasari,15118\n",
        ]);
        config([
            'database.default' => 'unavailable',
            'database.connections.unavailable' => [
                'driver' => 'mysql', 'host' => 'invalid.invalid', 'database' => 'none',
                'username' => 'none', 'password' => 'none',
            ],
        ]);
        DB::purge('unavailable');

        $result = app(ValteraRegionImportService::class)->import($directory, true);

        $this->assertTrue($result['dry_run']);
        $this->assertSame(1, $result['postal_codes']['inserted']);
    }

    private function dataset(array $files): string
    {
        $directory = sys_get_temp_dir().'/valtera-'.bin2hex(random_bytes(6));
        mkdir($directory);
        foreach ($files as $name => $contents) {
            file_put_contents($directory.'/'.$name, $contents);
        }

        return $directory;
    }

    private function csv(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'postal-supplement-');
        file_put_contents($path, $contents);
        return $path;
    }
}
