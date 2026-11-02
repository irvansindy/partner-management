<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserSeederSafetyTest extends TestCase
{
    /** @test */
    public function user_seeder_is_idempotent_and_does_not_truncate_referenced_users(): void
    {
        $contents = file_get_contents(database_path('seeders/UserSeeder.php'));

        $this->assertStringNotContainsString('User::truncate()', $contents);
        $this->assertStringContainsString('User::updateOrCreate(', $contents);

        preg_match_all('/["\']email["\']\s*=>\s*["\']([^"\']+)["\']/', $contents, $matches);
        $this->assertSame(array_values(array_unique($matches[1])), $matches[1]);
    }
}
