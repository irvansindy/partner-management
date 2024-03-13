<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAdditionalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_additional_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('type_branch')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('telephone_country_code')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax_country_code')->nullable();
            $table->string('fax')->nullable();
            $table->string('main_product_name_and_brand')->nullable();
            $table->string('main_customer')->nullable();
            $table->string('main_customer_telephone')->nullable();
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
        Schema::dropIfExists('company_additional_information');
    }
}
