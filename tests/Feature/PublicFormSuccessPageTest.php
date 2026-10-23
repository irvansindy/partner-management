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
            ->assertSee('Terima Kasih')
            ->assertSee('Data registrasi berhasil dikirim');
    }
}
