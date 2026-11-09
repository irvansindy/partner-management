<?php

namespace Tests\Feature;

use App\Http\Controllers\PartnerController;
use App\Models\CompanySupportingDocument;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class SupportingDocumentMultipleUploadTest extends TestCase
{
    private string $temporaryPublicPath;

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('company_supporting_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('company_doc_type')->nullable();
            $table->string('document')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_type_name')->nullable();
            $table->timestamps();
        });
        CompanySupportingDocument::unsetEventDispatcher();

        $this->temporaryPublicPath = sys_get_temp_dir().'/partner-attachments-'.Str::uuid();
        File::makeDirectory($this->temporaryPublicPath.'/storage/uploads/pt', 0755, true);
        $this->app->instance('path.public', $this->temporaryPublicPath);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->temporaryPublicPath);
        parent::tearDown();
    }

    /** @test */
    public function it_stores_every_file_from_a_multiple_supporting_document_upload(): void
    {
        $request = Request::create('/store-attachment', 'POST', [
            'supporting_document_type' => ['1|Tax Document', '2|Company Profile'],
            'supporting_document_business_type' => ['pt', 'pt'],
            'supporting_document_partner_id' => 42,
        ], [], [
            'file_supporting_document' => [
                UploadedFile::fake()->create('tax.pdf', 10, 'application/pdf'),
                UploadedFile::fake()->create('profile.pdf', 10, 'application/pdf'),
            ],
        ]);

        $response = (new PartnerController())->storeAttachment($request);

        $this->assertSame(200, $response->getStatusCode(), $response->getContent());
        $this->assertCount(2, CompanySupportingDocument::all());
        $this->assertFileExists($this->temporaryPublicPath.'/storage/uploads/pt/tax_document_pt.pdf');
        $this->assertFileExists($this->temporaryPublicPath.'/storage/uploads/pt/company_profile_pt.pdf');
    }

    /** @test */
    public function it_rejects_misaligned_document_rows_without_storing_partial_data(): void
    {
        $request = Request::create('/store-attachment', 'POST', [
            'supporting_document_type' => ['1|Tax Document', '2|Company Profile'],
            'supporting_document_business_type' => ['pt', 'pt'],
            'supporting_document_partner_id' => 42,
        ], [], [
            'file_supporting_document' => [
                UploadedFile::fake()->create('tax.pdf', 10, 'application/pdf'),
            ],
        ]);

        $response = (new PartnerController())->storeAttachment($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertCount(0, CompanySupportingDocument::all());
    }
}
