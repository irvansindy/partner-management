<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('register_number_as_in_tax_invoice')->nullable();
            $table->string('trc_number')->nullable();
            $table->string('register_number_related_branch')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('taxable_entrepreneur_number')->nullable();
            $table->string('tax_invoice_serial_number')->nullable();
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
        Schema::dropIfExists('company_taxes');
    }
}
