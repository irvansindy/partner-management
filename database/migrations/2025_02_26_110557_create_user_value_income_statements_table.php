<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserValueIncomeStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_value_income_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_information_id');
            $table->unsignedBigInteger('master_income_statement_id');
            $table->double('value');
            $table->year('year');
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
        Schema::dropIfExists('user_value_income_statements');
    }
}
