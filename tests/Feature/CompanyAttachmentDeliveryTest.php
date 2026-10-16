<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\CompanyAttachmentController;
use App\Models\CompanyAttachment;
use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Services\FormLinkService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CompanyAttachmentDeliveryTest extends TestCase
{
    /** @test */
    public function attachment_routes_are_authenticated(): void
    {
        foreach (['admin.company-attachments.show', 'admin.company-attachments.download'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route);
            $this->assertContains('auth', $route->gatherMiddleware());
        }
    }

    /** @test */
    public function preview_and_download_use_safe_headers_and_authorize_the_parent_form(): void
    {
        Storage::fake('company_attachments');
        Storage::disk('company_attachments')->put('companies/1/report.pdf', '%PDF-1.4');
        $formLink = new FormLink(['title' => 'Vendor']);
        $company = new CompanyInformation();
        $company->setRelation('formLink', $formLink);
        $attachment = new CompanyAttachment([
            'filepath' => 'companies/1/report.pdf', 'storage_disk' => 'company_attachments',
            'mime_type' => 'application/pdf', 'original_name' => "report\r\n.pdf",
        ]);
        $attachment->setRelation('company', $company);
        $service = Mockery::mock(FormLinkService::class);
        $service->shouldReceive('authorizeAccess')->twice()->with($formLink);
        $controller = new CompanyAttachmentController($service);

        $preview = $controller->show($attachment);
        $download = $controller->download($attachment);

        $this->assertSame('nosniff', $preview->headers->get('X-Content-Type-Options'));
        $this->assertSame('sandbox', $preview->headers->get('Content-Security-Policy'));
        $this->assertStringStartsWith('inline;', $preview->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('attachment;', $download->headers->get('Content-Disposition'));
        $this->assertStringNotContainsString("\r", $download->headers->get('Content-Disposition'));
    }
}
