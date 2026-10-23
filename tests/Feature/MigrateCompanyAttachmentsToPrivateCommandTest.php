<?php

namespace Tests\Feature;

use App\Models\CompanyAttachment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MigrateCompanyAttachmentsToPrivateCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('company_attachments', function (Blueprint $table) {
            $table->id(); $table->unsignedInteger('company_id'); $table->string('filename');
            $table->string('original_name')->nullable(); $table->string('filepath');
            $table->string('storage_disk')->default('public'); $table->integer('filesize')->nullable();
            $table->string('filetype')->nullable(); $table->string('mime_type')->nullable();
            $table->integer('width')->nullable(); $table->integer('height')->nullable();
            $table->string('sha256')->nullable(); $table->integer('sort_order')->default(0); $table->timestamps();
        });
        Storage::fake('public');
        Storage::fake('company_attachments');
    }

    /** @test */
    public function dry_run_preserves_legacy_file_and_real_run_verifies_then_moves_it(): void
    {
        Storage::disk('public')->put('company_attachments/legacy.pdf', '%PDF-1.4');
        $attachment = CompanyAttachment::create([
            'company_id' => 7, 'filename' => 'legacy.pdf', 'filepath' => 'company_attachments/legacy.pdf',
            'storage_disk' => 'public', 'filetype' => 'pdf', 'filesize' => 8,
        ]);

        $this->artisan('company-attachments:migrate-private', ['--dry-run' => true])->assertExitCode(0);
        Storage::disk('public')->assertExists($attachment->filepath);
        $this->assertSame('public', $attachment->fresh()->storage_disk);

        $this->artisan('company-attachments:migrate-private')->assertExitCode(0);
        $attachment->refresh();
        Storage::disk('public')->assertMissing('company_attachments/legacy.pdf');
        Storage::disk('company_attachments')->assertExists($attachment->filepath);
        $this->assertSame('company_attachments', $attachment->storage_disk);
        $this->assertSame(hash('sha256', '%PDF-1.4'), $attachment->sha256);
    }

    /** @test */
    public function missing_legacy_source_is_reported_without_changing_the_row(): void
    {
        $attachment = CompanyAttachment::create([
            'company_id' => 7, 'filename' => 'missing.pdf', 'filepath' => 'company_attachments/missing.pdf',
            'storage_disk' => 'public', 'filetype' => 'pdf',
        ]);

        $this->artisan('company-attachments:migrate-private')->assertExitCode(1);
        $this->assertSame('public', $attachment->fresh()->storage_disk);
    }
}
