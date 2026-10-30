<?php

namespace Tests\Feature\PublicForm;

use App\Http\Controllers\PublicFormController;
use App\Models\FormLink;
use App\Services\ApprovalService;
use App\Services\CompanyAttachmentService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class PublicFormErrorResponseTest extends TestCase
{
    /** @test */
    public function unexpected_errors_return_a_generic_message_and_request_id_only(): void
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

        $request = Request::create('/', 'POST', $this->validPayload(), [], [
            'input-multiple-file' => [$this->pdf()],
        ]);
        $response = $controller->submit($request, 'public-token');
        $json = $response->getData(true);
        $encoded = $response->getContent();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('Gagal memproses formulir. Silakan coba lagi. | Failed to process the form. Please try again.', $json['meta']['message']);
        $this->assertTrue(Str::isUuid($json['meta']['request_id']));
        $this->assertStringNotContainsString('SQLSTATE', $encoded);
        $this->assertStringNotContainsString('company_informations', $encoded);
        $this->assertStringNotContainsString(base_path(), $encoded);
    }

    private function validPayload(): array
    {
        return [
            'idempotency_key'=>'11111111-1111-4111-8111-111111111111',
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

    private function pdf(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'public-form-attachment-');
        file_put_contents($path, "%PDF-1.4\n%%EOF");

        return new UploadedFile($path, 'document.pdf', null, null, true);
    }
}
