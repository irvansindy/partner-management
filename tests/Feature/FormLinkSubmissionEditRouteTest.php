<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FormLinkSubmissionEditRouteTest extends TestCase
{
    /** @test */
    public function submission_edit_routes_have_the_expected_admin_names(): void
    {
        $this->assertTrue(Route::has('admin.form-links.submission-edit'));
        $this->assertTrue(Route::has('admin.form-links.submission-update'));
        $this->assertFalse(Route::has('admin.admin.form-links.submission-edit'));
        $this->assertFalse(Route::has('admin.admin.form-links.submission-update'));
    }

    /** @test */
    public function submission_edit_view_contains_all_editable_sections(): void
    {
        $this->assertTrue(view()->exists('admin.form_links.submission_edit'));

        $view = file_get_contents(resource_path('views/admin/form_links/submission_edit.blade.php'));
        foreach (['liable_people', 'contacts', 'addresses', 'banks', 'new_attachments', 'delete_attachment_ids'] as $field) {
            $this->assertStringContainsString($field, $view);
        }
    }

    /** @test */
    public function repeatable_row_templates_are_encoded_from_a_prebuilt_variable(): void
    {
        $view = file_get_contents(resource_path('views/admin/form_links/submission_edit.blade.php'));

        $this->assertStringContainsString('$rowTemplates = [', $view);
        $this->assertStringContainsString('@json($rowTemplates)', $view);
        $this->assertStringNotContainsString('const templates = @json([', $view);
    }
}
