<?php

namespace Tests\Unit;

use Tests\TestCase;

class PublicFormDraftStorageTest extends TestCase
{
    /** @test */
    public function form_drafts_store_all_restorable_fields_in_session_storage(): void
    {
        $source = file_get_contents(resource_path('views/public/form/js.blade.php'));

        $this->assertStringNotContainsString('localStorage.', $source);
        $this->assertStringNotContainsString('DRAFT_FIELD_ALLOWLIST', $source);
        $this->assertStringNotContainsString('SENSITIVE_DRAFT_FIELDS', $source);
        $this->assertStringContainsString('DRAFT_EXCLUDED_FIELDS', $source);
        $this->assertStringContainsString('sessionStorage.setItem', $source);
        $this->assertStringContainsString('sessionStorage.removeItem', $source);
        $this->assertStringContainsString("new Set(['_token'])", $source);
        $this->assertStringContainsString('if (this.type === "file") return;', $source);
        $this->assertStringContainsString("'#add_branch'", $source);
        $this->assertStringContainsString("'#add_contact'", $source);
    }
}
