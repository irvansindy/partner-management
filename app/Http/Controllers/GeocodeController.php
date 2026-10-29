<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wimski\Nominatim\Contracts\GeocoderServiceInterface;
use Wimski\Nominatim\RequestParameters\ForwardGeocodingQueryRequestParameters;
use Wimski\Nominatim\RequestParameters\ReverseGeocodingRequestParameters;
use Wimski\Nominatim\Objects\Coordinate;
use Illuminate\Support\Facades\Log;

class GeocodeController extends Controller
{
    protected $geocoder;

    public function __construct(GeocoderServiceInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function index(Request $request)
    {
        return view ('test_leaflet_geocoder');
    }
    public function forward(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'address' => 'required|string|max:500',
            'country_code' => 'nullable|string|size:2|regex:/^[a-z]{2}$/i',
        ]);

        $address = trim($data['address']);
        $country = isset($data['country_code']) ? strtolower($data['country_code']) : null;

        try {
            // Buat parameter request
            $params = ForwardGeocodingQueryRequestParameters::make($address)
                ->limit(1)
                ->includeAddressDetails();

            if ($country) {
                $params->addCountryCode($country);
            }

            // Request ke Nominatim
            $response = $this->geocoder->requestForwardGeocoding($params);
            $items = $response->getItems();

            if (empty($items)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Address not found',
                ], 404);
            }

            $item = $items[0];
            $coordinate = $item->getCoordinate();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'latitude' => $coordinate->getLatitude(),
                    'longitude' => $coordinate->getLongitude(),
                    'display_name' => $item->getDisplayName(),
                ],
                // 'raw' => config('app.debug') ? $item->toArray() : null, // Hanya tampil di debug mode
                'raw' => config('app.debug') ? [
                    'place_id' => $item->getPlaceId(),
                    'display_name' => $item->getDisplayName(),
                    'latitude' => $coordinate->getLatitude(),
                    'longitude' => $coordinate->getLongitude(),
                ] : null,
            ]);
        } catch (\Throwable $e) {
            // Log error untuk debugging
            Log::error('Geocoding failed', [
                'address' => $address,
                'error' => $e->getMessage(),
            ]);

            // Error handling (misalnya rate limit, network error, dll)
            return response()->json([
                'status' => 'error',
                'message' => 'Geocoding request failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred while processing your request',
            ], 500);
        }
    }

    public function reverse(Request $request)
    {
        $data = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $latitude = (float) $data['latitude'];
        $longitude = (float) $data['longitude'];

        try {
            $parameters = ReverseGeocodingRequestParameters::make(
                new Coordinate($latitude, $longitude)
            )->zoom(18)->includeAddressDetails();

            $response = $this->geocoder->requestReverseGeocoding($parameters);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'display_name' => $response->getDisplayName(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Reverse geocoding failed', [
                'exception' => get_class($e),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Alamat untuk koordinat tersebut tidak dapat dimuat.',
            ], 502);
        }
    }
}
