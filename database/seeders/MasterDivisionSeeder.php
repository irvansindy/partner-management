<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterDivision;
class MasterDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            [
                'name' => 'Manufacturing',
                'gm_user_id' => null,
                'office_id' => 2,
            ],
            [
                'name' => 'Sales and Marketing',
                'gm_user_id' => null,
                'office_id' => 1,
            ],
            [
                'name' => 'Supporting',
                'gm_user_id' => 25,
                'office_id' => 1,
            ],
        ];

        foreach ($divisions as $division) {
            MasterDivision::create($division);
        }
    }
}
