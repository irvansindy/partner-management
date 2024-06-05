<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("roles")->insert([
            [
                "name"=> "user",
                "guard_name"=> "web",
            ],
            [
                "name"=> "super-user",
                "guard_name"=> "web",
            ],
            [
                "name"=> "admin",
                "guard_name"=> "web",
            ],
            [
                "name"=> "super-admin",
                "guard_name"=> "web",
            ],
        ]);
    }
}
