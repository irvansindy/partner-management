<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RegionSeederCompatibilityTest extends TestCase
{
    /** @test */
    public function seeders_match_the_current_indonesian_region_shape(): void
    {
        $root = dirname(__DIR__, 2);
        $provinces = file_get_contents($root.'/database/seeders/ProvincesSeeder.php');
        $regencies = file_get_contents($root.'/database/seeders/RegenciesSeeder.php');

        preg_match_all("/\((\d+), '[^']+', -?[0-9.]+, -?[0-9.]+\)/", $regencies, $matches);

        $this->assertCount(514, $matches[0]);
        $this->assertStringContainsString("38 => '96'", $provinces);
        $this->assertStringContainsString("(38, 'Kabupaten Sorong'", $regencies);
        $this->assertStringContainsString("(38, 'Kota Sorong'", $regencies);
        $this->assertStringNotContainsString("(38, 'Fakfak'", $regencies);
        $this->assertStringContainsString("(14, 'Kabupaten Semarang'", $regencies);
    }
}
