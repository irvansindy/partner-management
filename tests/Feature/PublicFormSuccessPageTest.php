<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicFormSuccessPageTest extends TestCase
{
    /** @test */
    public function thank_you_page_is_available_after_a_successful_submission(): void
    {
        $this->get(route('public.form.success'))
            ->assertOk()
            ->assertSee('Data registrasi telah kami terima')
            ->assertSee('REGISTRASI SELESAI')
            ->assertSee('Status pengajuan')
            ->assertSee('Terkirim')
            ->assertSee('Proses selanjutnya')
            ->assertSee(asset('uploads/logo/logo.png'));
    }

    /** @test */
    public function thank_you_page_avoids_the_decorative_full_screen_template(): void
    {
        $view = file_get_contents(resource_path('views/public/form_success.blade.php'));

        $this->assertStringNotContainsString('linear-gradient', $view);
        $this->assertStringNotContainsString('scaleIn', $view);
        $this->assertStringNotContainsString('font-awesome', $view);
        $this->assertStringContainsString('<main class="success-main">', $view);
        $this->assertStringContainsString('@media (max-width: 720px)', $view);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $view);
    }
}
