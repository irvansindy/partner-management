<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Irvan Muhammad Sindy',
                'email' => 'irvansindy@pralon.com',
                'office_name' => 'Head Office',
                'role' => 'super-admin',
            ],
            [
                'name' => 'Advent',
                'email' => 'advent@pralon.com',
                'office_name' => 'Head Office',
                'role' => 'admin',
            ],
            [
                'name' => 'yohan',
                'email' => 'yohan@pralon.com',
                'office_name' => 'Head Office',
                'role' => 'admin',
            ],
            [
                'name' => 'Angelina Agnes',
                'email' => 'agnes@pralon.com',
                'office_name' => 'Head Office',
                'role' => 'super-user',
            ],
            [
                'name' => 'Harum',
                'email' => 'harum@pralon.com',
                'office_name' => 'Head Office',
                'role' => 'super-user',
            ],
            [
                'name' => 'Tryvena',
                'email' => 'vena_liem@yahoo.com',
                'office_name' => 'Pabrik Cimanggis',
                'role' => 'admin',
            ],
            [
                'name' => 'Nisa',
                'email' => 'nisapurchasing@pralon.com',
                'office_name' => 'Pabrik Cimanggis',
                'role' => 'super-user',
            ],
            [
                'name' => 'Sisi',
                'email' => 'sisi@pralon.com',
                'office_name' => 'Pabrik Karawang',
                'role' => 'super-user',
            ],
        ];

        foreach ($users as $attributes) {
            $role = $attributes['role'];
            unset($attributes['role']);

            $user = User::firstOrCreate(
                ['email' => $attributes['email']],
                [
                    'name' => $attributes['name'],
                    'password' => Hash::make(config('seeding.user_password')),
                    'office_id' => DB::table('master_offices')->where('name', $attributes['office_name'])->value('id'),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $user->assignRole($role);
            }
        }
    }
}
