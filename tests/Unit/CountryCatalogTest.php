<?php

namespace Tests\Unit;

use App\Support\CountryCatalog;
use Tests\TestCase;

class CountryCatalogTest extends TestCase
{
    /** @test */
    public function it_exposes_a_complete_unique_country_catalog(): void
    {
        $countries = CountryCatalog::all();
        $codes = array_column($countries, 'code');

        $this->assertGreaterThanOrEqual(240, count($countries));
        $this->assertCount(count($codes), array_unique($codes));
        $this->assertSame(['code' => 'ID', 'name' => 'Indonesia', 'calling_code' => '+62'], CountryCatalog::find('id'));

        foreach ($countries as $country) {
            $this->assertMatchesRegularExpression('/^[A-Z]{2}$/', $country['code']);
            $this->assertMatchesRegularExpression('/^\+[1-9][0-9]{0,3}$/', $country['calling_code']);
            $this->assertNotSame('', trim($country['name']));
        }
    }

    /** @test */
    public function it_returns_null_for_an_unknown_country(): void
    {
        $this->assertNull(CountryCatalog::find('XX'));
    }
}
