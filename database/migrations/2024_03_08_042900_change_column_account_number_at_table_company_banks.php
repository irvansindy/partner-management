<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class ChangeColumnAccountNumberAtTableCompanyBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_banks', function (Blueprint $table) {
            // DB::statement("ALTER TABLE {$table->getTable()} MODIFY `account_number` varchar(50)");
            DB::statement("ALTER TABLE company_banks ALTER COLUMN account_number TYPE VARCHAR(50)");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_banks', function (Blueprint $table) {
            // DB::statement("ALTER TABLE {$table->getTable()} MODIFY `account_number` integer(11)");
            DB::statement("ALTER TABLE company_banks ALTER COLUMN account_number TYPE INTEGER USING account_number::integer");
        });
    }
}
