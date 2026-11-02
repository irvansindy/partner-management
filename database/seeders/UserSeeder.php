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
        $users = [
            [
                'name' => 'Irvan Muhammad Sindy',
                'email' => 'irvansindy@pralon.com',
                'office_id' => 1,
                'role' => 'super-admin',
            ],
            [
                'name' => 'Advent',
                'email' => 'advent@pralon.com',
                'office_id' => 1,
                'role' => 'admin',
            ],
            [
                'name' => 'yohan',
                'email' => 'yohan@pralon.com',
                'office_id' => 1,
                'role' => 'admin',
            ],
            [
                'name' => 'Angelina Agnes',
                'email' => 'agnes@pralon.com',
                'office_id' => 1,
                'role' => 'super-user',
            ],
            [
                'name' => 'Harum',
                'email' => 'harum@pralon.com',
                'office_id' => 1,
                'role' => 'super-user',
            ],
            [
                'name' => 'Tryvena',
                'email' => 'vena_liem@yahoo.com',
                'office_id' => 2,
                'role' => 'admin',
            ],
            [
                'name' => 'Nisa',
                'email' => 'nisapurchasing@pralon.com',
                'office_id' => 2,
                'role' => 'super-user',
            ],
            [
                'name' => 'Sisi',
                'email' => 'sisi@pralon.com',
                'office_id' => 3,
                'role' => 'super-user',
            ],
        ];

        foreach ($users as $attributes) {
            $role = $attributes['role'];
            unset($attributes['role']);

            $user = User::updateOrCreate(
                ['email' => $attributes['email']],
                [
                    'name' => $attributes['name'],
                    'password' => Hash::make('pass12345'),
                    'office_id' => $attributes['office_id'],
                ]
            );

            $user->assignRole($role);
        }
    }
}
