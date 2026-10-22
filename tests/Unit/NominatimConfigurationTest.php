<?php

namespace Tests\Unit;

use Tests\TestCase;

class NominatimConfigurationTest extends TestCase
{
    /** @test */
    public function nominatim_identity_configuration_always_has_string_defaults(): void
    {
        $this->assertIsString(config('nominatim.services.nominatim.user_agent'));
        $this->assertNotSame('', trim(config('nominatim.services.nominatim.user_agent')));
        $this->assertIsString(config('nominatim.services.nominatim.email'));
        $this->assertNotSame('', trim(config('nominatim.services.nominatim.email')));
    }
}
