<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            [
                "name"=> "Irvan Muhammad Sindy",
                "email"=> "adminsindy@gmail.com",
                "password"=> Hash::make("pass12345"),
            ],
            [
                "name"=> "admin 1",
                "email"=> "admin1@gmail.com",
                "password"=> Hash::make("pass12345"),
            ],
            [
                "name"=> "super user 1",
                "email"=> "super_user1@gmail.com",
                "password"=> Hash::make("pass12345"),
            ],
            [
                "name"=> "user 1",
                "email"=> "user1@gmail.com",
                "password"=> Hash::make("pass12345"),
            ],
        ]);
    }
}
