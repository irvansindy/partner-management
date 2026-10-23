<?php

namespace Tests\Unit;

use Tests\TestCase;

class PublicFormDraftStorageTest extends TestCase
{
    /** @test */
    public function form_drafts_use_session_storage_and_an_explicit_non_sensitive_allowlist(): void
    {
        $source = file_get_contents(resource_path('views/public/form/js.blade.php'));

        $this->assertStringNotContainsString('localStorage.', $source);
        $this->assertStringContainsString('DRAFT_FIELD_ALLOWLIST', $source);
        $this->assertStringContainsString('sessionStorage.setItem', $source);
        $this->assertStringContainsString('sessionStorage.removeItem', $source);

        foreach (['_token', 'nik', 'account_number', 'contact_email', 'contact_telephone', 'address', 'input-multiple-file'] as $field) {
            $this->assertStringContainsString("'{$field}'", $source);
        }
        $this->assertStringContainsString('SENSITIVE_DRAFT_FIELDS', $source);
    }
}
