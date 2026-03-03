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
        // DB::statement('ALTER TABLE sales_surveys CHANGE user_id company_id BIGINT UNSIGNED NULL');
        // Schema::table('sales_surveys', function (Blueprint $table) {
        //     $table->dropColumn('payment_reference');
        // });

        DB::statement('ALTER TABLE sales_surveys RENAME COLUMN user_id TO company_id');
        DB::statement('ALTER TABLE sales_surveys ALTER COLUMN company_id TYPE BIGINT');
        DB::statement('ALTER TABLE sales_surveys ALTER COLUMN company_id DROP NOT NULL');

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
        // DB::statement('ALTER TABLE sales_surveys CHANGE company_id user_id BIGINT UNSIGNED NULL');
        // Schema::table('sales_surveys', function (Blueprint $table) {
        //     $table->enum('payment_reference', ['cash', 'transfer', 'credit_card'])->default('cash');
        // });

        DB::statement('ALTER TABLE sales_surveys RENAME COLUMN company_id TO user_id');

        DB::statement('ALTER TABLE sales_surveys ALTER COLUMN user_id TYPE BIGINT');

        Schema::table('sales_surveys', function (Blueprint $table) {
            $table->string('payment_reference')->default('cash'); // Pakai string agar aman di Postgres
        });
    }
}
