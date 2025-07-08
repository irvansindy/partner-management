<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('gm_user_id')->nullable(); // General Manager user ID
            $table->unsignedInteger('office_id')->nullable(); // Head of Division user ID
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
        Schema::dropIfExists('master_divisions');
    }
}
