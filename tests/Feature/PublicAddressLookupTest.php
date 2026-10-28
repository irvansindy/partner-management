<?php

namespace Tests\Feature;

use App\Models\PostalCode;
use App\Models\Provinces;
use App\Models\Regencies;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicAddressLookupTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('provinces', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->timestamps();
        });
        Schema::create('regencies', function (Blueprint $table) {
            $table->id(); $table->string('code', 4)->nullable(); $table->foreignId('province_id'); $table->string('name'); $table->timestamps();
        });
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id(); $table->foreignId('province_id'); $table->foreignId('regency_id');
            $table->string('district'); $table->string('village'); $table->string('postal_code', 5); $table->timestamps();
        });
    }

    /** @test */
    public function scoped_address_master_routes_exist_and_are_throttled(): void
    {
        foreach (['public.form.provinces', 'public.form.regencies', 'public.form.postal-codes'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route);
            $this->assertContains('throttle:60,1', $route->gatherMiddleware());
        }
    }

    /** @test */
    public function postal_codes_are_scoped_to_the_selected_regency_and_returned_once(): void
    {
        $province = Provinces::create(['name' => 'Banten']);
        $tangerang = Regencies::create(['province_id' => $province->id, 'name' => 'Kota Tangerang']);
        $serang = Regencies::create(['province_id' => $province->id, 'name' => 'Kota Serang']);

        PostalCode::create(['province_id' => $province->id, 'regency_id' => $tangerang->id, 'district' => 'Pinang', 'village' => 'Panunggangan', 'postal_code' => '15143']);
        PostalCode::create(['province_id' => $province->id, 'regency_id' => $tangerang->id, 'district' => 'Pinang', 'village' => 'Panunggangan Timur', 'postal_code' => '15143']);
        PostalCode::create(['province_id' => $province->id, 'regency_id' => $serang->id, 'district' => 'Serang', 'village' => 'Cimuncang', 'postal_code' => '42111']);

        $response = $this->getJson(route('public.form.postal-codes', $tangerang->id));

        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.postal_code', '15143');
        $this->assertStringContainsString('Pinang', $response->json('data.0.area'));
        $this->assertStringNotContainsString('Serang', $response->json('data.0.area'));
    }

    /** @test */
    public function regency_lookup_hides_legacy_unmapped_rows_after_codes_are_available(): void
    {
        $province = Provinces::create(['name' => 'Papua Barat Daya']);
        Regencies::create(['province_id' => $province->id, 'code' => '9601', 'name' => 'Kab. Sorong']);
        Regencies::create(['province_id' => $province->id, 'name' => 'Fakfak (legacy duplicate)']);

        $response = $this->getJson(route('public.form.regencies', $province->id));

        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.code', '9601');
    }
}
