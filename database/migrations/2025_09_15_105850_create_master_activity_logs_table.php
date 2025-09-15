<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->json('action')->nullable();
            $table->timestamps();
        });
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->unsignedInteger('master_activity_log_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_activity_logs');
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('master_activity_log_id');
        });
    }
}
