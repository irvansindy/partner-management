<?php
namespace App\Services;

use Wimski\Nominatim\Nominatim; // jika pakai wimski
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeocodingService
{
    protected $nominatimBase = 'https://nominatim.openstreetmap.org/search';
    protected $userAgent;

    public function __construct()
    {
        $this->userAgent = config('app.name') . 'pralonpartner@gmail.com';
    }

    /**
     * Forward geocode an address -> [lat, lng] or null
     */
    public function geocode(string $address): ?array
    {
        // cache key supaya tidak terlalu banyak request
        $cacheKey = 'geocode:' . md5($address);
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            // Gunakan Nominatim publik
            $resp = Http::withHeaders([
                'User-Agent' => $this->userAgent
            ])->get($this->nominatimBase, [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
            ]);
            if ($resp->successful() && !empty($respJson = $resp->json()) && isset($respJson[0]['lat'])) {
                return [
                    'lat' => (float) $respJson[0]['lat'],
                    'lng' => (float) $respJson[0]['lon'],
                ];
            }

            // Fallback: LocationIQ (jika Anda set KEY di .env)
            if ($key = config('services.locationiq.key')) {
                $r = Http::get('https://us1.locationiq.com/v1/search.php', [
                    'key' => $key,
                    'q' => $address,
                    'format' => 'json',
                    'limit' => 1,
                ]);
                if ($r->successful() && !empty($jr = $r->json()) && isset($jr[0]['lat'])) {
                    return [
                        'lat' => (float) $jr[0]['lat'],
                        'lng' => (float) $jr[0]['lon'],
                    ];
                }
            }

            return null;
        });
    }
}
