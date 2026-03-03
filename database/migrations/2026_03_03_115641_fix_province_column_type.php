<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class FixProvinceColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tambah kolom baru
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->bigInteger('province_new')->nullable();
        });

        // Copy & cast data
        DB::statement('UPDATE company_addresses SET province_new = province::bigint');

        // Drop kolom lama
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        // Rename
        DB::statement('ALTER TABLE company_addresses RENAME COLUMN province_new TO province');
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->string('province_old')->nullable();
        });

        DB::statement('UPDATE company_addresses SET province_old = province::text');

        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        DB::statement('ALTER TABLE company_addresses RENAME COLUMN province_old TO province');
    }
}
