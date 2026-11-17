<?php

namespace Database\Seeders;

use App\Services\ValteraRegionImportService;
use Illuminate\Database\Seeder;

class RegionPostalCodeSeeder extends Seeder
{
    public function run(): void
    {
        $importer = app(ValteraRegionImportService::class);
        $directory = config('seeding.regions.directory');
        $supplement = config('seeding.regions.supplement');
        $importer->import($directory, true, $supplement);
        $result = $importer->import($directory, false, $supplement);
        if ($this->command) {
            $this->command->info('Wilayah dan kode pos: '.$result['postal_codes']['inserted'].' baru, '.$result['postal_codes']['existing'].' sudah ada.');
        }
    }
}
