<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('name')->nullable();
            $table->string('group_name')->nullable();
            $table->string('established_year')->nullable();
            // $table->string('established_year')->nullable();
            $table->integer('total_employee')->nullable();
            $table->string('liable_person_and_position')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('board_of_directors')->nullable();
            $table->string('major_shareholders')->nullable();
            $table->string('business_classification')->nullable();
            $table->string('website_address')->nullable();
            $table->string('system_management')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('communication_language')->nullable();
            $table->string('email_address')->nullable();
            $table->text('remark')->nullable();
            $table->string('signature')->nullable();
            $table->string('stamp')->nullable();
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
        Schema::dropIfExists('company_informations');
    }
}
