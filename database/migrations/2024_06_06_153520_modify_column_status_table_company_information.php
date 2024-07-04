<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class ModifyColumnStatusTableCompanyInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_infomations', function (Blueprint $table) {
            DB::statement("ALTER TABLE company_infomations MODIFY status ENUM('checking', 'checking 2', 'approved', 'reject') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_infomations', function (Blueprint $table) {
            DB::statement("ALTER TABLE company_infomations MODIFY status ENUM('checking', 'approved') NOT NULL");
        });
    }
}
