<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicFormController;
use App\Models\PostalCode;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Services\ApprovalService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class PublicFormAddressSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('provinces', function (Blueprint $table) { $table->id(); $table->string('name'); $table->timestamps(); });
        Schema::create('regencies', function (Blueprint $table) { $table->id(); $table->foreignId('province_id'); $table->string('name'); $table->timestamps(); });
        Schema::create('postal_codes', function (Blueprint $table) { $table->id(); $table->foreignId('province_id'); $table->foreignId('regency_id'); $table->string('district'); $table->string('village'); $table->string('postal_code', 5); $table->timestamps(); });
    }

    /** @test */
    public function it_normalizes_indonesian_master_ids_and_foreign_text_addresses(): void
    {
        $province = Provinces::create(['name' => 'Banten']);
        $regency = Regencies::create(['province_id' => $province->id, 'name' => 'Tangerang']);
        PostalCode::create(['province_id' => $province->id, 'regency_id' => $regency->id, 'district' => 'Pinang', 'village' => 'Kunciran', 'postal_code' => '15144']);

        $validated = $this->validateSubmission($this->payload([
            'address_role' => ['legal', 'branch'], 'address' => ['Jl. Satu', '1 Main St'],
            'country_code' => ['ID', 'SG'], 'country' => ['Indonesia', 'Singapore'],
            'province' => [(string) $province->id, 'Central Region'], 'city' => [(string) $regency->id, 'Singapore'],
            'zip_code' => ['15144', '018956'], 'latitude' => ['-6.2', null], 'longitude' => ['106.8', null],
            'telephone' => ['+62812345678', '+6512345678'], 'fax' => ['+62812345678', '+6512345678'],
        ]));

        $this->assertSame(['Banten', 'Central Region'], $validated['province']);
        $this->assertSame(['Tangerang', 'Singapore'], $validated['city']);
    }

    /** @test */
    public function it_rejects_misaligned_or_forged_indonesian_address_arrays(): void
    {
        $this->expectException(ValidationException::class);
        $this->validateSubmission($this->payload(['country_code' => ['ID'], 'address_role' => ['legal'], 'latitude' => [], 'longitude' => []]));
    }

    private function payload(array $overrides): array
    {
        return array_replace([
            'idempotency_key'=>'11111111-1111-4111-8111-111111111111',
            'company_type'=>'vendor','company_name'=>'Pralon','established_year'=>'2000','liable_person'=>['Budi'],'liable_position'=>['Director'],'nik'=>['1234567890123456'],
            'business_classification'=>'Supplier','register_number_as_in_tax_invoice'=>'123','contact_department'=>['Sales'],'contact_position'=>['Manager'],'contact_name'=>['Tasya'],'contact_email'=>['a@example.com'],'contact_telephone'=>['+62812345678'],
            'address_role'=>['legal'],'address'=>['Jl. Satu'],'country_code'=>['ID'],'country'=>['Indonesia'],'province'=>['999'],'city'=>['999'],'zip_code'=>['15144'],'latitude'=>[null],'longitude'=>[null],'telephone'=>['+62812345678'],'fax'=>['+62812345678'],
            'bank_name'=>['Bank ABC'],'account_name'=>['Tasya'],'account_number'=>['123456'],
        ], $overrides);
    }

    private function validateSubmission(array $data): array
    {
        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));
        $method = new ReflectionMethod($controller, 'validateSubmission');
        $method->setAccessible(true);
        return $method->invoke($controller, Request::create('/', 'POST', $data), 'vendor');
    }
}
