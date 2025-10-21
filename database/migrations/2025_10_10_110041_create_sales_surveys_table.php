<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->enum('ownership_status', ['own', 'rented'])->default('own');
            $table->integer('rental_year')->default(1)->nullable();
            $table->integer('pick_up')->default(0)->nullable();
            $table->integer('truck')->default(0)->nullable();
            $table->enum('payment_reference', ['cash', 'transfer', 'credit_card'])->default('cash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_surveys');
    }
}
