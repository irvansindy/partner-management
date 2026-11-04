<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkDetailActionsTest extends TestCase
{
    /** @test */
    public function detail_actions_use_one_primary_action_and_neutral_supporting_actions(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/show.blade.php');

        $this->assertStringContainsString('form-link-actions', $view);
        $this->assertStringContainsString('form-detail-action', $view);
        $this->assertSame(1, substr_count($view, 'btn-primary form-detail-action'));
        $this->assertSame(3, substr_count($view, 'btn-outline-secondary form-detail-action'));
        $this->assertStringNotContainsString('btn-warning btn-block', $view);
        $this->assertStringNotContainsString('btn-info btn-block', $view);
    }

    /** @test */
    public function submission_pdf_is_returned_as_a_laravel_response_without_output_buffer_assumptions(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringNotContainsString('ob_clean()', $controller);
        $this->assertStringContainsString("\$mpdf->Output('', 'S')", $controller);
        $this->assertStringContainsString("'Content-Type' => 'application/pdf'", $controller);
        $this->assertStringContainsString("'Content-Disposition' => 'inline; filename=\"'", $controller);
    }
}
