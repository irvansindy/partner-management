<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLevelJobs;
class LevelJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levelJobs = [
            ['name' => 'Director'],
            ['name' => 'General Manager'],
            ['name' => 'Manager'],
            ['name' => 'Assistant Manager'],
            ['name' => 'Supervisor'],
            ['name' => 'Staff'],
        ];

        foreach ($levelJobs as $levelJob) {
            MasterLevelJobs::create($levelJob);
        }
    }
}
