<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBusinessClassification;
class BusinessClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MasterBusinessClassification::firstOrCreate(['name' => 'Manufacturer']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Trading']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Agent']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Distributor']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Services']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Contractor']);
        MasterBusinessClassification::firstOrCreate(['name' => 'E-commerce']);
        MasterBusinessClassification::firstOrCreate(['name' => 'Other']);
    }
}
