<?php

namespace Tests\Feature;

use App\Models\CompanyInformation;
use App\Services\CompanyAttachmentService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicFormAttachmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('company_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('filename');
            $table->string('original_name')->nullable();
            $table->string('filepath');
            $table->string('storage_disk')->default('public');
            $table->integer('filesize')->nullable();
            $table->string('filetype')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('sha256')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Storage::fake('company_attachments');
    }

    /** @test */
    public function it_stores_with_a_uuid_name_on_the_private_disk(): void
    {
        $service = new CompanyAttachmentService();
        $company = new CompanyInformation();
        $company->id = 42;
        $file = $this->pdf();
        $attachment = $service->store($company, $file, $service->validate($file), 1);

        $this->assertMatchesRegularExpression('/^companies\/42\/[0-9a-f-]{36}\.pdf$/', $attachment->filepath);
        $this->assertSame('company_attachments', $attachment->storage_disk);
        $this->assertSame('application/pdf', $attachment->mime_type);
        Storage::disk('company_attachments')->assertExists($attachment->filepath);
    }

    /** @test */
    public function it_removes_the_file_when_metadata_persistence_fails(): void
    {
        $service = new CompanyAttachmentService();
        $company = new CompanyInformation();
        $company->id = 42;
        $file = $this->pdf();
        $metadata = $service->validate($file);
        Schema::drop('company_attachments');

        try {
            $service->store($company, $file, $metadata, 1);
            $this->fail('Database failure was expected.');
        } catch (\Throwable $exception) {
            $this->assertSame([], Storage::disk('company_attachments')->allFiles());
        }
    }

    private function pdf(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'attachment-test-');
        file_put_contents($path, "%PDF-1.4\n%%EOF");
        return new UploadedFile($path, 'contract.pdf', null, null, true);
    }
}
