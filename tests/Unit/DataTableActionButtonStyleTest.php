<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DataTableActionButtonStyleTest extends TestCase
{
    private function resource(string $path): string
    {
        return dirname(__DIR__, 2).'/resources/'.$path;
    }

    public function test_main_layout_loads_accessible_responsive_data_table_action_styles(): void
    {
        $layout = file_get_contents($this->resource('views/layouts/main.blade.php'));
        $stylesPath = $this->resource('views/layouts/partials/table_action_styles.blade.php');

        $this->assertStringContainsString("@include('layouts.partials.table_action_styles')", $layout);
        $this->assertFileExists($stylesPath);

        $styles = file_get_contents($stylesPath);
        $this->assertStringContainsString('.table-actions', $styles);
        $this->assertStringContainsString('table.dataTable tbody td .btn', $styles);
        $this->assertStringContainsString('min-width: 44px', $styles);
        $this->assertStringContainsString('min-height: 44px', $styles);
        $this->assertStringContainsString('gap: 6px', $styles);
        $this->assertStringContainsString('flex-wrap: wrap', $styles);
        $this->assertStringContainsString(':focus-visible', $styles);
        $this->assertStringContainsString('.table-action--view', $styles);
        $this->assertStringContainsString('.table-action--edit', $styles);
        $this->assertStringContainsString('.table-action--toggle', $styles);
        $this->assertStringContainsString('.table-action--delete', $styles);
        $this->assertStringContainsString('.table-action--copy', $styles);
        $this->assertStringContainsString('background: #2563eb !important', $styles);
        $this->assertStringContainsString('background: #f59e0b !important', $styles);
        $this->assertStringContainsString('background: #15925f !important', $styles);
        $this->assertStringContainsString('background: #dc3545 !important', $styles);
        $this->assertStringContainsString('background: #475569 !important', $styles);
    }

    public function test_form_links_uses_semantic_accessible_non_segmented_actions(): void
    {
        $view = file_get_contents($this->resource('views/admin/form_links/index.blade.php'));

        $this->assertStringContainsString('class="table-actions"', $view);
        $this->assertStringNotContainsString('btn-group btn-group-sm', $view);
        $this->assertStringContainsString('table-action table-action--view', $view);
        $this->assertStringContainsString('table-action table-action--edit', $view);
        $this->assertStringContainsString('table-action table-action--toggle', $view);
        $this->assertStringContainsString('table-action table-action--delete', $view);
        $this->assertStringContainsString('table-action table-action--copy', $view);
        $this->assertGreaterThanOrEqual(5, substr_count($view, 'aria-label='));
        $this->assertGreaterThanOrEqual(5, substr_count($view, 'title='));
    }
}
