<?php

namespace Tests\Unit;

use App\Models\FormLink;
use PHPUnit\Framework\TestCase;

class FormLinkDescriptionTest extends TestCase
{
    public function test_legacy_html_description_is_read_as_plain_text_without_rewriting_database_value(): void
    {
        $form = new FormLink();
        $form->setRawAttributes(['description' => '<p>First &amp; second</p><p>Next<br>line</p>']);
        $this->assertSame("First & second\nNext\nline", $form->description);
        $this->assertSame('<p>First &amp; second</p><p>Next<br>line</p>', $form->getAttributes()['description']);
    }

    public function test_plain_encoded_and_empty_descriptions_are_supported(): void
    {
        foreach ([null => '', 'Plain text' => 'Plain text', '&lt;p&gt;Encoded&lt;/p&gt;' => 'Encoded'] as $input => $expected) {
            $form = new FormLink(['description' => $input]);
            $this->assertSame($expected, $form->description);
        }
    }
}
