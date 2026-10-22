<?php

namespace Tests\Feature\PublicForm;

use App\Models\FormLink;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicFormXssTest extends TestCase
{
    /** @test */
    public function form_link_content_is_html_escaped_in_the_public_page(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('master_business_classifications', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->timestamps();
        });
        $payload = '<script>window.pwned=true</script>';
        $formLink = new FormLink([
            'token' => 'safe-token',
            'title' => $payload,
            'description' => '<img src=x onerror=alert(1)>',
            'form_type' => 'vendor',
            'is_active' => true,
        ]);

        $html = view('public.form_index', ['formLink' => $formLink, 'countries' => []])->render();

        $this->assertStringNotContainsString($payload, $html);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
        $this->assertStringContainsString('&lt;script&gt;window.pwned=true&lt;/script&gt;', $html);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $html);
    }
}
