<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::create([
            "name"=> "Irvan Muhammad Sindy",
            "email"=> "adminsindy@gmail.com",
            "password"=> Hash::make("pass12345"),
            "office_id"=> 1,
        ]);
        $super_admin->assignRole("super-admin");

        $admin = User::create([
            "name"=> "admin 1",
            "email"=> "admin1@gmail.com",
            "password"=> Hash::make("pass12345"),
            "office_id"=> 1,
        ]);
        $admin->assignRole("admin");

        $super_user = User::create([
            "name"=> "super user 1",
            "email"=> "super_user1@gmail.com",
            "password"=> Hash::make("pass12345"),
            "office_id"=> 1,
        ]);
        $super_user->assignRole("super-user");

        $user = User::create([
            "name"=> "user 1",
            "email"=> "user1@gmail.com",
            "password"=> Hash::make("pass12345"),
            "office_id"=> 1,
        ]);
        $user->assignRole("user");
    }
}
