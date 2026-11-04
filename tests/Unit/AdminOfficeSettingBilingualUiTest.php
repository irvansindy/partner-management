<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminOfficeSettingBilingualUiTest extends TestCase
{
    /** @test */
    public function office_setting_renders_and_uses_matching_bilingual_copy(): void
    {
        $indonesian = require resource_path('lang/id/messages.php');
        $english = require resource_path('lang/en/messages.php');
        $index = file_get_contents(resource_path('views/admin/office/index.blade.php'));
        $modal = file_get_contents(resource_path('views/admin/office/modal_office.blade.php'));
        $script = file_get_contents(resource_path('views/admin/office/office_js.blade.php'));

        foreach ([
            'Office Setting Title',
            'Office Setting Description',
            'Office Setting Create',
            'Office Setting Name',
            'Office Setting Address',
            'Office Setting Save Success',
            'Office Setting Table Empty',
        ] as $key) {
            $this->assertArrayHasKey($key, $indonesian);
            $this->assertArrayHasKey($key, $english);
            $this->assertNotSame($indonesian[$key], $english[$key]);
        }

        $this->assertStringContainsString("__('messages.Office Setting Title')", $index);
        $this->assertStringContainsString("__('messages.Office Setting Name')", $modal);
        $this->assertStringContainsString("@json(__('messages.Office Setting Create'))", $script);
        $this->assertStringContainsString('officeText.', $script);

        $this->assertStringNotContainsString('>Office Management<', $index);
        $this->assertStringNotContainsString("title: 'Success'", $script);
        $this->assertStringNotContainsString("body: 'Silahkan isi data", $script);

        $this->assertTrue(view()->exists('admin.office.index'));
        $this->assertTrue(view()->exists('admin.office.modal_office'));
    }
}
