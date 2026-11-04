<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterDivision;
class MasterDepartements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fatDivision = MasterDivision::updateOrCreate(['name' => 'FAT'], [
            'gm_user_id' => null,
            'office_id' => 1,
        ]);

        $departments = [
            ['name' => 'Finance', 'division_id' => $fatDivision->id],
            [
                "name"=> "HRD",
            ],
            [
                "name"=> "ICT",
            ],
            [
                "name"=> "Marketing",
            ],
            [
                "name"=> "Production",
            ],
            [
                "name"=> "Purchasing",
            ],
            [
                "name"=> "Quality Control",
            ],
            [
                "name"=> "Research and Development",
            ],
            [
                "name"=> "Sales Project",
            ],
            [
                "name"=> "Sales Retail 1",
            ],
            [
                "name"=> "Sales Retail 2",
            ],
            [
                "name"=> "Warehouse",
            ],
            [
                "name"=> "Logistic",
            ],
            [
                "name"=> "General Affair",
            ],
        ];

        foreach ($departments as $department) {
            DB::table('master_departments')->updateOrInsert(['name' => $department['name']], $department);
        }
    }
}
