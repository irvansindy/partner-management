<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterBalanceSheet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table("master_balance_sheets")->insert([
            [
                "name"=> "cash",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "acc_receivables",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "other_current_asset",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "current_asset",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "fixed_asset",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "total_asset",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "current_liabilities",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "non_current_liabilities",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "total_current_liabilities",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "equity",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "land_and_or_building",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "net_equity",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
        ]);
    }
}
