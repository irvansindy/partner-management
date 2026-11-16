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
        Schema::table('company_informations', function (Blueprint $table) {
            // DB::statement("ALTER TABLE company_informations MODIFY status ENUM('checking', 'checking 2', 'approved', 'reject') NOT NULL");
            DB::statement("ALTER TABLE company_informations ADD CONSTRAINT status CHECK (status IN ('checking', 'checking 2', 'approved', 'reject'))");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $constraint = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'company_informations'
            AND CONSTRAINT_NAME = 'status'
            AND CONSTRAINT_TYPE = 'CHECK'
        ");

        if ($constraint) {
            DB::statement("
                ALTER TABLE company_informations
                DROP CHECK `status`
            ");
        }
    }
}
