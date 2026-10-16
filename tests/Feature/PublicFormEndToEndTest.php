<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicFormController;
use App\Models\FormLink;
use App\Services\ApprovalService;
use App\Services\CompanyAttachmentService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class PublicFormEndToEndTest extends TestCase
{
    /** @test */
    public function unexpected_submission_errors_return_only_a_generic_public_message(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('form_links', function (Blueprint $table) {
            $table->id(); $table->string('token'); $table->string('form_type');
            $table->boolean('is_active'); $table->timestamp('expires_at')->nullable();
            $table->integer('max_submissions')->nullable(); $table->integer('submission_count')->default(0);
            $table->timestamps();
        });
        FormLink::create(['token' => 'public-token', 'form_type' => 'vendor', 'is_active' => true]);
        $controller = new PublicFormController(Mockery::mock(ApprovalService::class), new CompanyAttachmentService());

        $response = $controller->submit(Request::create('/', 'POST', $this->validPayload()), 'public-token');
        $body = $response->getContent();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertStringContainsString('Gagal memproses formulir', $body);
        $this->assertStringNotContainsString('company_informations', $body);
        $this->assertStringNotContainsString('SQLSTATE', $body);
    }

    private function validPayload(): array
    {
        return [
            'company_type'=>'vendor','company_name'=>'Pralon','established_year'=>'2000',
            'liable_person'=>['Budi'],'liable_position'=>['Director'],'nik'=>['1234567890123456'],
            'business_classification'=>'Supplier','register_number_as_in_tax_invoice'=>'123',
            'contact_department'=>['Sales'],'contact_position'=>['Manager'],'contact_name'=>['Tasya'],
            'contact_email'=>['a@example.com'],'contact_telephone'=>['+6512345678'],
            'address_role'=>['legal'],'address'=>['One Main Street'],'country_code'=>['SG'],
            'country'=>['Singapore'],'province'=>['Central Region'],'city'=>['Singapore'],'zip_code'=>['018956'],
            'latitude'=>[null],'longitude'=>[null],'telephone'=>['+6512345678'],'fax'=>['+6512345678'],
            'bank_name'=>['Bank ABC 2'],'account_name'=>['Tasya'],'account_number'=>['123456'],
        ];
    }
}
