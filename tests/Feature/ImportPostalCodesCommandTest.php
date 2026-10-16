<?php

namespace Tests\Feature;

use Tests\TestCase;

class ImportPostalCodesCommandTest extends TestCase
{
    /** @test */
    public function it_fails_for_a_missing_csv_file(): void
    {
        $this->artisan('postal-codes:import', ['file' => '/missing/postal.csv'])->assertExitCode(1);
    }
}
