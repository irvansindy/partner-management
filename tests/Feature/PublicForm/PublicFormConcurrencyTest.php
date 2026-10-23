<?php

namespace Tests\Feature\PublicForm;

use App\Exceptions\PublicFormClosedException;
use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Services\PublicFormSubmissionService;

class PublicFormConcurrencyTest extends PublicFormIdempotencyTest
{
    /** @test */
    public function different_keys_cannot_consume_the_same_final_submission_slot(): void
    {
        $link = FormLink::create([
            'token' => 'one-slot', 'form_type' => 'vendor', 'is_active' => true,
            'max_submissions' => 1, 'submission_count' => 0,
        ]);
        $service = new PublicFormSubmissionService();
        $create = fn ($lockedLink, $key) => CompanyInformation::withoutEvents(fn () => CompanyInformation::create([
            'form_link_id' => $lockedLink->id, 'idempotency_key' => $key, 'name' => 'Pralon',
        ]));

        $service->submit($link, '11111111-1111-4111-8111-111111111111', $create);

        $this->expectException(PublicFormClosedException::class);
        try {
            $service->submit($link, '22222222-2222-4222-8222-222222222222', $create);
        } finally {
            $this->assertSame(1, CompanyInformation::count());
            $this->assertSame(1, $link->fresh()->submission_count);
        }
    }
}
