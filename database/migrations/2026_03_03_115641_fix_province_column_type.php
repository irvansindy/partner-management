<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixProvinceColumnType extends Migration
{
    public function up()
    {
        // Tambah kolom baru
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->bigInteger('province_new')->nullable();
        });

        // Copy & cast data — MySQL syntax
        DB::statement('UPDATE company_addresses SET province_new = CAST(province AS SIGNED)');

        // Drop kolom lama
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        // Rename — valid di MySQL 8.0+
        DB::statement('ALTER TABLE company_addresses RENAME COLUMN province_new TO province');
    }

    public function down()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->string('province_old')->nullable();
        });

        // Cast bigint ke string — MySQL syntax
        DB::statement('UPDATE company_addresses SET province_old = CAST(province AS CHAR)');

        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        DB::statement('ALTER TABLE company_addresses RENAME COLUMN province_old TO province');
    }
}