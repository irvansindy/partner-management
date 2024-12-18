<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDepartmentIdToTableCompanyInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->unsignedInteger('department_id')->unsigned()->nullable()->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });
    }
}
