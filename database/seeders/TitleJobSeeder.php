<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterJobTitles;
class TitleJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobTitles = [
            ['name' => 'Manager Purchasing', 'level_id' => 3],
            ['name' => 'Manager Sales Retail 1', 'level_id' => 3],
            ['name' => 'Manager Sales Retail 2', 'level_id' => 3],
            ['name' => 'Manager Sales Project', 'level_id' => 3],
            ['name' => 'Staff Purchasing', 'level_id' => 6],
            ['name' => 'Staff Sales Retail 1', 'level_id' => 6],
            ['name' => 'Staff Sales Retail 2', 'level_id' => 6],
            ['name' => 'Staff Sales Project', 'level_id' => 6],
        ];

        foreach ($jobTitles as $jobTitle) {
            MasterJobTitles::create($jobTitle);
        }
    }
}
