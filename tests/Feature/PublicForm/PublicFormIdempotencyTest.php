<?php

namespace Tests\Feature\PublicForm;

use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Services\PublicFormSubmissionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicFormIdempotencyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('form_links', function (Blueprint $table) {
            $table->id(); $table->string('token'); $table->string('form_type');
            $table->boolean('is_active'); $table->timestamp('expires_at')->nullable();
            $table->integer('max_submissions')->nullable(); $table->integer('submission_count')->default(0);
            $table->timestamps();
        });
        Schema::create('company_informations', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('form_link_id'); $table->uuid('idempotency_key');
            $table->unsignedInteger('user_id')->default(0); $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['form_link_id', 'idempotency_key']);
        });
    }

    /** @test */
    public function replaying_the_same_key_returns_the_original_company_without_executing_work_twice(): void
    {
        $link = FormLink::create([
            'token' => 'public-token', 'form_type' => 'vendor', 'is_active' => true,
            'max_submissions' => 1, 'submission_count' => 0,
        ]);
        $service = new PublicFormSubmissionService();
        $executions = 0;
        $submit = function () use ($service, $link, &$executions) {
            return $service->submit($link, '11111111-1111-4111-8111-111111111111', function ($lockedLink, $key) use (&$executions) {
                $executions++;
                return CompanyInformation::withoutEvents(fn () => CompanyInformation::create([
                    'form_link_id' => $lockedLink->id,
                    'idempotency_key' => $key,
                    'name' => 'Pralon',
                ]));
            });
        };

        $first = $submit();
        $second = $submit();

        $this->assertFalse($first['replayed']);
        $this->assertTrue($second['replayed']);
        $this->assertSame($first['company']->id, $second['company']->id);
        $this->assertSame(1, $executions);
        $this->assertSame(1, CompanyInformation::count());
        $this->assertSame(1, $link->fresh()->submission_count);
    }
}
