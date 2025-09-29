<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CompanyAddress;
use App\Services\GeocodingService;
class GeocodeAddressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $addressId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $addressId)
    {
        $this->addressId = $addressId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GeocodingService $geocoder)
    {
        $address = CompanyAddress::find($this->addressId);
        if (! $address) return;
        // Jika sudah ada lat/lng, skip
        if ($address->latitude && $address->longitude) return;
        // lakukan geocode
        $result = $geocoder->geocode($address->address);
        if ($result) {
            $address->latitude = $result['lat'];
            $address->longitude = $result['lng'];
            $address->geocoded_at = now();
            $address->save();
        } else {
            // simpan info kalau gagal (opsional)
            $address->geocoded_at = now();
            $address->save();
        }
        
        // Opsional: sleep atau rate-limit sederhana
        usleep(500000); // 0.5s jeda (atur sesuai policy)
    }
}
