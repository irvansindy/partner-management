<?php

namespace Tests\Unit;

use Tests\TestCase;

class BilingualConfigurationTest extends TestCase
{
    /** @test */
    public function google_translate_is_optional_and_configured_for_id_to_en_authoring(): void
    {
        $config = require config_path('laravel_google_translate.php');

        $this->assertArrayHasKey('google_translate_api_key', $config);
        $this->assertNull($config['google_translate_api_key']);
        $this->assertSame('id', $config['base_locale']);
        $this->assertSame('en', $config['default_target_locales']);
        $this->assertStringContainsString('GOOGLE_TRANSLATE_API_KEY=', file_get_contents(base_path('.env.example')));
    }

    /** @test */
    public function indonesian_and_english_message_files_have_identical_keys(): void
    {
        $id = require resource_path('lang/id/messages.php');
        $en = require resource_path('lang/en/messages.php');

        $this->assertSame([], array_values(array_diff(array_keys($id), array_keys($en))));
        $this->assertSame([], array_values(array_diff(array_keys($en), array_keys($id))));
    }
}
