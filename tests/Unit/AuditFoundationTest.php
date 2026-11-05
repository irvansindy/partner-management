<?php

namespace Tests\Unit;

use App\Audit\AuditDiff;
use App\Audit\AuditRedactor;
use Tests\TestCase;

class AuditFoundationTest extends TestCase
{
    /** @test */
    public function redactor_removes_nested_secrets_without_mutating_the_input(): void
    {
        $input = [
            'name' => 'Vendor Example',
            'password' => 'plain-secret',
            'nested' => [
                'authorization' => 'Bearer token-secret',
                'email' => 'person@example.com',
            ],
        ];

        $redacted = (new AuditRedactor(['password', 'authorization']))->redact($input);

        $this->assertSame('[REDACTED]', $redacted['password']);
        $this->assertSame('[REDACTED]', $redacted['nested']['authorization']);
        $this->assertSame('person@example.com', $redacted['nested']['email']);
        $this->assertSame('plain-secret', $input['password']);
    }

    /** @test */
    public function diff_returns_only_changed_fields_after_redaction(): void
    {
        $diff = new AuditDiff(new AuditRedactor(['token']));

        $changes = $diff->between(
            ['name' => 'Old', 'status' => 'active', 'token' => 'old-secret'],
            ['name' => 'New', 'status' => 'active', 'token' => 'new-secret']
        );

        $this->assertSame(['name', 'token'], array_column($changes, 'field'));
        $this->assertSame('Old', $changes[0]['old_value']);
        $this->assertSame('New', $changes[0]['new_value']);
        $this->assertSame('[REDACTED]', $changes[1]['old_value']);
        $this->assertTrue($changes[1]['sensitive']);
    }
}
