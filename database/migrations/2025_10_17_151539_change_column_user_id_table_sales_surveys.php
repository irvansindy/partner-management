<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnUserIdTableSalesSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE sales_surveys CHANGE user_id company_id BIGINT UNSIGNED NULL');
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
        DB::statement('ALTER TABLE sales_surveys CHANGE company_id user_id BIGINT UNSIGNED NULL');
        Schema::table('sales_surveys', function (Blueprint $table) {
            $table->enum('payment_reference', ['cash', 'transfer', 'credit_card'])->default('cash');
        });
    }
}
