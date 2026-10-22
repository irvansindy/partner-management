<?php

namespace Tests\Unit;

use App\Services\CompanyAttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CompanyAttachmentServiceTest extends TestCase
{
    /** @test */
    public function it_inspects_real_pdf_png_and_jpeg_signatures(): void
    {
        $service = new CompanyAttachmentService();

        $pdf = $service->validate($this->upload('contract.exe', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF"));
        $png = $service->validate($this->upload('logo.txt', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')));
        $jpeg = $service->validate(UploadedFile::fake()->image('photo.bin', 1, 1));

        $this->assertSame('application/pdf', $pdf['mime']);
        $this->assertSame('png', $png['extension']);
        $this->assertSame('jpeg', $jpeg['extension']);
        $this->assertSame(1, $png['width']);
        $this->assertSame(1, $png['height']);
    }

    /** @test */
    public function it_rejects_spoofed_or_truncated_files(): void
    {
        $service = new CompanyAttachmentService();

        foreach ([
            $this->upload('fake.pdf', 'plain text'),
            $this->upload('broken.png', "\x89PNG\r\n\x1a\ntruncated"),
        ] as $file) {
            try {
                $service->validate($file);
                $this->fail('Invalid file should be rejected.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('input-multiple-file', $exception->errors());
            }
        }
    }

    /** @test */
    public function it_sanitizes_the_original_name_without_using_it_as_a_storage_name(): void
    {
        $metadata = (new CompanyAttachmentService())->validate(
            $this->upload('invoice<>.pdf', "%PDF-1.4\n%%EOF")
        );

        $this->assertSame('invoice__.pdf', $metadata['original_name']);
        $this->assertStringNotContainsString('..', $metadata['original_name']);
    }

    /** @test */
    public function it_enforces_configured_size_and_image_dimension_limits(): void
    {
        config([
            'company_attachments.max_bytes' => 10,
            'company_attachments.max_width' => 1,
            'company_attachments.max_height' => 1,
        ]);
        $service = new CompanyAttachmentService();

        try {
            $service->validate($this->upload('large.pdf', "%PDF-1.4\n%%EOF"));
            $this->fail('Configured file size limit should be enforced.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('input-multiple-file', $exception->errors());
        }

        try {
            $service->validate(UploadedFile::fake()->image('wide.png', 2, 1));
            $this->fail('Configured image dimension limit should be enforced.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('input-multiple-file', $exception->errors());
        }
    }

    private function upload(string $name, string $contents): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'attachment-test-');
        file_put_contents($path, $contents);

        return new UploadedFile($path, $name, null, null, true);
    }
}
