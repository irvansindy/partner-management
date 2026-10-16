<?php

namespace Tests\Feature;

use App\Models\Provinces;
use App\Models\Regencies;
use App\Services\PostalCodeImportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PostalCodeImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('provinces', function (Blueprint $table) { $table->id(); $table->string('name'); $table->timestamps(); });
        Schema::create('regencies', function (Blueprint $table) { $table->id(); $table->foreignId('province_id'); $table->string('name'); $table->timestamps(); });
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id(); $table->foreignId('province_id'); $table->foreignId('regency_id');
            $table->string('district'); $table->string('village'); $table->string('postal_code', 5); $table->timestamps();
            $table->unique(['province_id', 'regency_id', 'district', 'village', 'postal_code']);
        });
    }

    /** @test */
    public function it_imports_valid_rows_idempotently_and_reports_invalid_rows(): void
    {
        $province = Provinces::create(['name' => 'Banten']);
        Regencies::create(['province_id' => $province->id, 'name' => 'Tangerang']);
        $file = $this->csv("\xEF\xBB\xBFprovince,regency,district,village,postal_code\n banten , TANGERANG , Pinang , Kunciran ,15144\nBanten,Serang,Anyar,Cikoneng,1234\n");

        $first = app(PostalCodeImportService::class)->import($file);
        $second = app(PostalCodeImportService::class)->import($file);

        $this->assertSame(1, $first['inserted']);
        $this->assertSame(1, $first['rejected']);
        $this->assertSame(1, $second['existing']);
        $this->assertSame(3, $first['errors'][0]['row']);
        $this->assertDatabaseHas('postal_codes', ['district' => 'Pinang', 'village' => 'Kunciran', 'postal_code' => '15144']);
    }

    /** @test */
    public function it_rejects_an_invalid_header(): void
    {
        $file = $this->csv("province,city,postal_code\nBanten,Tangerang,15144\n");

        $this->expectException(\InvalidArgumentException::class);
        app(PostalCodeImportService::class)->import($file);
    }

    private function csv(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'postal-');
        file_put_contents($path, $contents);
        return $path;
    }
}
