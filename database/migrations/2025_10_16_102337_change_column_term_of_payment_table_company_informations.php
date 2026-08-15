<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeColumnTermOfPaymentTableCompanyInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * MySQL:
         * Ubah term_of_payment menjadi VARCHAR(255)
         * dan izinkan NULL.
         */
        DB::statement("
            ALTER TABLE company_informations
            MODIFY COLUMN term_of_payment VARCHAR(255) NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
         * Kembalikan ke struktur ENUM sebelumnya.
         *
         * Nilai yang diperbolehkan:
         * 30, 45, 60, 90
         */
        DB::statement("
            ALTER TABLE company_informations
            MODIFY COLUMN term_of_payment
            ENUM('30', '45', '60', '90') NULL
        ");
    }
}