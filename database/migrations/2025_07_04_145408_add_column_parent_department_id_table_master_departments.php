<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentDepartmentIdTableMasterDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_departments', function (Blueprint $table) {
            $table->integer('parent_department_id')
                    ->after('division_id')
                    ->nullable()
                    ->comment('ID of the parent department this department belongs to');
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
            $table->dropColumn('parent_department_id')->after('division_id');
        });
    }
}
