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
        MasterBusinessClassification::create(['name' => 'Manufacturer']);
        MasterBusinessClassification::create(['name' => 'Trading']);
        MasterBusinessClassification::create(['name' => 'Agent']);
        MasterBusinessClassification::create(['name' => 'Distributor']);
        MasterBusinessClassification::create(['name' => 'Services']);
        MasterBusinessClassification::create(['name' => 'Contractor']);
        MasterBusinessClassification::create(['name' => 'E-commerce']);
        MasterBusinessClassification::create(['name' => 'Other']);
    }
}
