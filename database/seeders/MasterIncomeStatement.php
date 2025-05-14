<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MasterIncomeStatement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("master_income_statements")->insert([
            [
                "name"=> "revenue",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "cost_of_revenue",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "gross_profit",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "opex",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "ebit",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "interest_expense",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "others_expense",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "others_income",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "ebt",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "net_profit",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
            [
                "name"=> "depreciation_expense",
                "created_at"=> date("Y-m-d H:i:s"),
            ],
        ]);
    }
}
