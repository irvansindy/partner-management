<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class PublicFormLocaleUiTest extends TestCase
{
    /** @test */
    public function public_form_shell_and_wizard_use_the_active_locale(): void
    {
        App::setLocale('id');
        $this->assertSame('Langkah Registrasi', __('messages.Public Registration Steps'));
        $this->assertSame('Lanjutkan', __('messages.Public Continue'));

        App::setLocale('en');
        $this->assertSame('Registration Steps', __('messages.Public Registration Steps'));
        $this->assertSame('Continue', __('messages.Public Continue'));

        $layout = file_get_contents(resource_path('views/layouts/public.blade.php'));
        $form = file_get_contents(resource_path('views/public/form_index.blade.php'));

        $this->assertStringContainsString('id="public_locale_switch"', $layout);
        $this->assertStringContainsString('role="switch"', $layout);
        $this->assertStringContainsString('public-locale__track', $layout);
        $this->assertStringContainsString("app()->getLocale() === 'en'", $layout);
        $this->assertStringContainsString("'locale' => 'id'", $layout);
        $this->assertStringContainsString("'locale' => 'en'", $layout);
        $this->assertStringContainsString('data-id-url=', $layout);
        $this->assertStringContainsString('data-en-url=', $layout);
        $this->assertStringContainsString("'return_to' => request()->getRequestUri()", $layout);
        $this->assertStringNotContainsString('public-locale__link', $layout);
        $this->assertStringContainsString("{{ __('messages.Public Registration Steps') }}", $form);
        $this->assertStringContainsString("{{ __('messages.Public Continue') }}", $form);
        $this->assertStringNotContainsString('@lang(', $form);
        $this->assertStringNotContainsString('>REGISTRATION STEPS<', $form);
        $this->assertStringNotContainsString('>Continue <', $form);
    }

    /** @test */
    public function public_success_and_closed_pages_use_translation_keys(): void
    {
        $success = file_get_contents(resource_path('views/public/form_success.blade.php'));
        $closed = file_get_contents(resource_path('views/public/form_closed.blade.php'));

        $this->assertStringContainsString("__('messages.Public Success Title')", $success);
        $this->assertStringContainsString("__('messages.Public Form Closed')", $closed);

        $controller = file_get_contents(app_path('Http/Controllers/PublicFormController.php'));
        $this->assertStringContainsString("__('messages.Public Form Inactive')", $controller);
        $this->assertStringContainsString("__('messages.Public Form Expired'", $controller);
    }
}
