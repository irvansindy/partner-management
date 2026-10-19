<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicFormAlphaInputFilterTest extends TestCase
{
    /** @test */
    public function alpha_space_fields_filter_invalid_characters_during_typing_and_paste(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString("name=\"contact_department[]\"", $script);
        $this->assertStringContainsString("name=\"liable_person[]\"", $script);
        $this->assertStringContainsString("name=\"account_name[]\"", $script);
        $this->assertStringContainsString("#company_name, #company_group_name", $script);
        $this->assertStringContainsString("replace(/[^\\p{L} ]/gu, '')", $script);
        $this->assertStringContainsString('setSelectionRange', $script);
        $this->assertStringContainsString("on('input', '[data-bank-name]'", $script);
        $this->assertStringContainsString("replace(/[^\\p{L}\\p{N} .,&'()\\/+\\-]/gu, '')", $script);
    }
}
