<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInformation;
use App\Models\CompanyBank;
use App\Models\User;
class ActivityLogDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CREATE CompanyBank
        $bank = CompanyBank::create([
            'company_id' => 1,
            'name'  => 'CIMB',
            'account_number' => '1234567890',
            'account_name'   => 'PT Demo Jaya',
        ]);

        // UPDATE CompanyBank
        $bank->update([
            'name' => 'BCA',
        ]);


        // // CREATE User
        // $user = User::create([
        //     'name' => 'ben slamet',
        //     'email' => 'ben@example.com',
        //     'password' => bcrypt('password'),
        // ]);

        // // UPDATE User
        // $user->update([
        //     'name' => 'slamet',
        // ]);
    }
}
