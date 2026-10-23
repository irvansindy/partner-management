<?php

namespace Tests\Feature\PublicForm;

use App\Http\Controllers\PublicFormController;
use App\Models\FormLink;
use App\Services\ApprovalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class PublicFormSqlInjectionTest extends TestCase
{
    /** @test */
    public function a_sql_payload_in_the_token_never_bypasses_exact_token_lookup(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('form_links', function (Blueprint $table) {
            $table->id(); $table->string('token'); $table->string('form_type'); $table->string('title')->nullable();
            $table->text('description')->nullable(); $table->boolean('is_active');
            $table->timestamp('expires_at')->nullable(); $table->integer('max_submissions')->nullable();
            $table->integer('submission_count')->default(0); $table->timestamps();
        });
        FormLink::create(['token' => 'real-token', 'form_type' => 'vendor', 'is_active' => true]);
        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));

        $this->expectException(ModelNotFoundException::class);
        $controller->show("' OR 1=1 --");
    }
}
