<?php

namespace Tests\Unit;

use App\Models\ApprovalProcessStep;
use PHPUnit\Framework\TestCase;

class ApprovalNotesTextTest extends TestCase
{
    public function test_legacy_html_notes_are_read_as_text_with_paragraphs(): void
    {
        $step = new ApprovalProcessStep();
        $html = '<p>Approved &amp; checked</p><p>Next<br>line</p>';
        $step->setRawAttributes(['notes' => $html]);
        $this->assertSame("Approved & checked\nNext\nline", $step->notes);
        $this->assertSame($html, $step->getAttributes()['notes']);
    }

    public function test_plain_encoded_and_null_notes_remain_supported(): void
    {
        foreach ([[null, null], ['', ''], ['Plain notes', 'Plain notes'], ['&lt;p&gt;Checked&lt;/p&gt;', 'Checked']] as [$input, $expected]) {
            $step = new ApprovalProcessStep(['notes' => $input]);
            $this->assertSame($expected, $step->notes);
        }
    }
}
