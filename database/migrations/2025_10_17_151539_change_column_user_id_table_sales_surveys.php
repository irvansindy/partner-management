<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeColumnUserIdTableSalesSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Rename:
         * user_id -> company_id
         *
         * Sekaligus ubah tipe menjadi BIGINT UNSIGNED NULL.
         *
         * CHANGE COLUMN adalah syntax MySQL.
         */
        DB::statement("
            ALTER TABLE sales_surveys
            CHANGE COLUMN user_id company_id BIGINT UNSIGNED NULL
        ");

        /*
         * Hapus payment_reference.
         */
        Schema::table('sales_surveys', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
         * Kembalikan:
         * company_id -> user_id
         *
         * Sekaligus kembalikan tipe menjadi BIGINT UNSIGNED NULL.
         */
        DB::statement("
            ALTER TABLE sales_surveys
            CHANGE COLUMN company_id user_id BIGINT UNSIGNED NULL
        ");

        /*
         * Kembalikan payment_reference.
         *
         * Berdasarkan migration sebelumnya,
         * nilai awalnya adalah:
         * cash, transfer, credit_card
         */
        Schema::table('sales_surveys', function (Blueprint $table) {
            $table->enum(
                'payment_reference',
                ['cash', 'transfer', 'credit_card']
            )->default('cash');
        });
    }
}