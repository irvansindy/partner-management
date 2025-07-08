<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDivisionTableMasterDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_departments', function (Blueprint $table) {
            $table->unsignedInteger('division_id')->after('id')->nullable()->comment('ID of the division this department belongs to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_departments', function (Blueprint $table) {
            $table->dropColumn('division_id')->after('id');
        });
    }
}
