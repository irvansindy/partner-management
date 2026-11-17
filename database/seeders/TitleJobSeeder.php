<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterJobTitles;
use App\Models\MasterLevelJobs;
class TitleJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managerLevelId = MasterLevelJobs::where('name', 'Manager')->firstOrFail()->id;
        $staffLevelId = MasterLevelJobs::where('name', 'Staff')->firstOrFail()->id;
        $jobTitles = [
            ['name' => 'Manager Purchasing', 'level_id' => $managerLevelId],
            ['name' => 'Manager Sales Retail 1', 'level_id' => $managerLevelId],
            ['name' => 'Manager Sales Retail 2', 'level_id' => $managerLevelId],
            ['name' => 'Manager Sales Project', 'level_id' => $managerLevelId],
            ['name' => 'Staff Purchasing', 'level_id' => $staffLevelId],
            ['name' => 'Staff Sales Retail 1', 'level_id' => $staffLevelId],
            ['name' => 'Staff Sales Retail 2', 'level_id' => $staffLevelId],
            ['name' => 'Staff Sales Project', 'level_id' => $staffLevelId],
        ];

        foreach ($jobTitles as $jobTitle) {
            MasterJobTitles::firstOrCreate(['name' => $jobTitle['name']], $jobTitle);
        }
    }
}
