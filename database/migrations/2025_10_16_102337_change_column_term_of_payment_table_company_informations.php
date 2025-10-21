<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTermOfPaymentTableCompanyInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE company_informations MODIFY COLUMN term_of_payment varchar(255) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE company_informations MODIFY COLUMN term_of_payment ENUM('30', '45', '60', '90') NULL");
    }
}
