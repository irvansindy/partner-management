<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RegionCodeSchemaTest extends TestCase
{
    /** @test */
    public function region_models_and_migration_expose_stable_codes(): void
    {
        $root = dirname(__DIR__, 2);
        $province = file_get_contents($root.'/app/Models/Provinces.php');
        $regency = file_get_contents($root.'/app/Models/Regencies.php');
        $migration = file_get_contents($root.'/database/migrations/2026_10_27_000001_add_codes_to_indonesian_regions.php');

        $this->assertStringContainsString("'code'", $province);
        $this->assertStringContainsString("'code'", $regency);
        $this->assertStringContainsString("string('code', 2)", $migration);
        $this->assertStringContainsString("string('code', 4)", $migration);
        $this->assertStringContainsString("dropColumn('code')", $migration);
    }
}
