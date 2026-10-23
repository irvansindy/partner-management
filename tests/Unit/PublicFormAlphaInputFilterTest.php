<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicFormAlphaInputFilterTest extends TestCase
{
    /** @test */
    public function alpha_space_fields_are_filtered_during_input_for_static_and_dynamic_rows(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString("on('input', '[data-alpha-spaces]'", $script);
        $this->assertStringContainsString("replace(/[^\\p{L} ]/gu, '')", $script);
        $this->assertStringContainsString('setSelectionRange', $script);
    }

    /** @test */
    public function bank_name_fields_filter_only_unsupported_characters_during_input(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString("on('input', '[data-bank-name]'", $script);
        $this->assertStringContainsString("replace(/[^\\p{L}\\p{N} .,&'()\\/+\\-]/gu, '')", $script);
    }
}
