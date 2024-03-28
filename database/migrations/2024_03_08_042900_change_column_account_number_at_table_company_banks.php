<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            // $table->string('account_number')->nullable()->change();
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY `account_number` varchar(50)");
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
            // $table->integer('account_number')->nullable()->change();
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY `account_number` integer(11)");
            // $table->integer('account_number')->nullable()->change();
        });
    }
}
