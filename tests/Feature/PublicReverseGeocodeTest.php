<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Mockery;
use Tests\TestCase;
use Wimski\Nominatim\Contracts\GeocoderServiceInterface;
use Wimski\Nominatim\Contracts\Responses\ReverseGeocodingResponseInterface;
use Wimski\Nominatim\RequestParameters\ReverseGeocodingRequestParameters;

class PublicReverseGeocodeTest extends TestCase
{
    /** @test */
    public function reverse_map_route_accepts_valid_coordinates(): void
    {
        $response = Mockery::mock(ReverseGeocodingResponseInterface::class);
        $response->shouldReceive('getDisplayName')->once()->andReturn('Mall Alam Sutera, Tangerang, Indonesia');

        $geocoder = Mockery::mock(GeocoderServiceInterface::class);
        $geocoder->shouldReceive('requestReverseGeocoding')
            ->once()
            ->with(Mockery::type(ReverseGeocodingRequestParameters::class))
            ->andReturn($response);
        $this->app->instance(GeocoderServiceInterface::class, $geocoder);

        $this->postJson(route('reverse-map'), [
            'latitude' => -6.223,
            'longitude' => 106.654,
        ])->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.display_name', 'Mall Alam Sutera, Tangerang, Indonesia')
            ->assertJsonPath('data.latitude', -6.223)
            ->assertJsonPath('data.longitude', 106.654);
    }

    /** @test */
    public function reverse_map_route_rejects_out_of_range_coordinates(): void
    {
        $this->postJson(route('reverse-map'), [
            'latitude' => 91,
            'longitude' => -181,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['latitude', 'longitude']);
    }

    /** @test */
    public function reverse_map_route_returns_a_safe_provider_error(): void
    {
        $geocoder = Mockery::mock(GeocoderServiceInterface::class);
        $geocoder->shouldReceive('requestReverseGeocoding')->once()->andThrow(new \RuntimeException('provider secret'));
        $this->app->instance(GeocoderServiceInterface::class, $geocoder);

        $this->postJson(route('reverse-map'), [
            'latitude' => -6.223,
            'longitude' => 106.654,
        ])->assertStatus(502)
            ->assertJsonMissing(['error' => 'provider secret'])
            ->assertJsonPath('message', 'Alamat untuk koordinat tersebut tidak dapat dimuat.');
    }

    /** @test */
    public function reverse_map_route_is_rate_limited(): void
    {
        $route = Route::getRoutes()->getByName('reverse-map');

        $this->assertNotNull($route);
        $this->assertContains('throttle:60,1', $route->gatherMiddleware());
    }
}
