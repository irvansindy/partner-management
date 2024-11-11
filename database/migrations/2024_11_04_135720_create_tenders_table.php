<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tender_type_id');
            $table->unsignedInteger('eula_tnc_id');
            $table->string('name')->nullable();
            $table->date('created_date')->nullable();
            $table->date('ordering_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('source_document')->nullable();
            $table->string('location_id')->nullable();
            $table->enum('status', ['open','close'])->default('open');
            $table->softDeletes();
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
        Schema::dropIfExists('tenders');
    }
}
