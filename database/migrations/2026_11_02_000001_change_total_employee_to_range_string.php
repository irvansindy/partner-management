<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTotalEmployeeToRangeString extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE company_informations MODIFY total_employee VARCHAR(20) NULL');
        DB::statement('ALTER TABLE form_submissions MODIFY total_employee VARCHAR(20) NULL');
    }

    public function down()
    {
        $representatives = [
            '1-100' => 100,
            '101-500' => 500,
            '501-1000' => 1000,
            '1001-5000' => 5000,
            '5001-10000' => 10000,
            '10000+' => 10001,
        ];

        foreach (['company_informations', 'form_submissions'] as $table) {
            foreach ($representatives as $range => $value) {
                DB::table($table)->where('total_employee', $range)->update(['total_employee' => $value]);
            }
        }

        DB::statement('ALTER TABLE company_informations MODIFY total_employee INT NULL');
        DB::statement('ALTER TABLE form_submissions MODIFY total_employee INT NULL');
    }
}
