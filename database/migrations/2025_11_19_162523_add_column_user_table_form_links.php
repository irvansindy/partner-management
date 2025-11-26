<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserTableFormLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_links', function (Blueprint $table) {
            $table->unsignedInteger('department_id')->nullable()->after('token');
            $table->unsignedInteger('office_id')->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_links', function (Blueprint $table) {
            $table->dropColumn(['department_id', 'office_id']);
        });
    }
}
