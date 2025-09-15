<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLiablePositionTableCompanyInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->string('liable_position')->nullable()->after('liable_person_and_position');
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
            $table->dropColumn('liable_position');
        });
    }
}
