<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MasterDepartements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("master_departments")->insert([
            [
                "name"=> "Finance",
            ],
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
        ]);
    }
}
