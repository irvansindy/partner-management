
<?php

use Illuminate\Database\Migrations\Migration;
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
        DB::statement("
            ALTER TABLE `company_banks`
            MODIFY COLUMN `account_number` VARCHAR(50) NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Keep account numbers as VARCHAR to prevent
        // data truncation, overflow and loss of leading zeros.
        DB::statement("
            ALTER TABLE `company_banks`
            MODIFY COLUMN `account_number` VARCHAR(50) NULL
        ");
    }
}
