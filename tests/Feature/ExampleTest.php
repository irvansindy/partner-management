<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function root_redirects_to_the_login_page()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
