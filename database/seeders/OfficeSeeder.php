<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = [
            [
                "name"=> "Head Office",
                "address"=> "Alam Sutera",
            ],
            [
                "name"=> "Pabrik Cimanggis",
                "address"=> "Depok",
            ],
            [
                "name"=> "Pabrik Karawang",
                "address"=> "Karawang",
            ],
            [
                "name"=> "Gudang T8",
                "address"=> "Alam Sutera",
            ]
        ];
        foreach ($offices as $office) {
            if (!DB::table('master_offices')->where('name', $office['name'])->exists()) {
                DB::table('master_offices')->insert($office);
            }
        }
    }
}
