<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkIndexDataTableTest extends TestCase
{
    private function projectFile(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }

    public function test_form_link_index_uses_local_datatable_with_horizontal_scrolling(): void
    {
        $view = file_get_contents($this->projectFile('resources/views/admin/form_links/index.blade.php'));

        $this->assertStringContainsString("vendor/datatables/css/dataTables.bootstrap4.min.css", $view);
        $this->assertStringContainsString("vendor/datatables/js/jquery.dataTables.min.js", $view);
        $this->assertStringContainsString("vendor/datatables/js/dataTables.bootstrap4.min.js", $view);
        $this->assertStringContainsString('id="form_links_table"', $view);
        $this->assertStringContainsString('scrollX: true', $view);
        $this->assertStringContainsString('autoWidth: false', $view);
        $this->assertStringContainsString('<th>No.</th>', $view);
        $this->assertStringNotContainsString('<th>ID</th>', $view);
        $this->assertStringContainsString("<th>{{ __('admin.Created By') }}</th>", $view);
        $this->assertStringContainsString("<th>{{ __('admin.Created At') }}</th>", $view);
        $this->assertStringContainsString('{{ $formLink->creator->name ?? \'-\' }}', $view);
        $this->assertStringContainsString("\$formLink->created_at->locale(app()->getLocale())->translatedFormat('d M Y H:i')", $view);
        $this->assertStringContainsString('class="row-number"', $view);
        $this->assertStringContainsString('drawCallback:', $view);
        $this->assertStringContainsString('targets: [0, 8, 9]', $view);
        $this->assertStringContainsString('const formLinksTable =', $view);
        $this->assertStringContainsString('function adjustFormLinksTable()', $view);
        $this->assertStringContainsString('formLinksTable.columns.adjust()', $view);
        $this->assertStringContainsString("$('.navigation-toggle').on('click.formLinksTable'", $view);
        $this->assertStringContainsString("$('.page-content, .page-sidebar').on('transitionend.formLinksTable'", $view);
        $this->assertStringContainsString("$(window).on('resize.formLinksTable'", $view);
        $this->assertStringNotContainsString('$formLinks->links()', $view);
    }

    public function test_form_link_service_returns_all_authorized_rows_for_client_side_datatable(): void
    {
        $service = file_get_contents($this->projectFile('app/Services/FormLinkService.php'));
        $methodStart = strpos($service, 'public function getFormLinksForCurrentUser');
        $methodEnd = strpos($service, 'public function getAdminUsers', $methodStart);
        $method = substr($service, $methodStart, $methodEnd - $methodStart);

        $this->assertStringContainsString('getFormLinksForCurrentUser(): Collection', $method);
        $this->assertStringContainsString("FormLink::with('creator:id,name')", $method);
        $this->assertStringNotContainsString('paginate(', $method);
        $this->assertGreaterThanOrEqual(3, substr_count($method, '->get()'));
    }
}
