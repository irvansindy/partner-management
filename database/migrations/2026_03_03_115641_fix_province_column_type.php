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
        /*
         * Tambahkan kolom baru dengan tipe BIGINT.
         */
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->bigInteger('province_new')->nullable();
        });

        /*
         * Copy data dari province lama ke province_new.
         *
         * PostgreSQL:
         * province::bigint
         *
         * MySQL:
         * CAST(province AS UNSIGNED)
         */
        DB::statement("
            UPDATE company_addresses
            SET province_new = CAST(province AS UNSIGNED)
        ");

        /*
         * Hapus kolom province lama.
         */
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        /*
         * Rename province_new menjadi province.
         */
        DB::statement("
            ALTER TABLE company_addresses
            RENAME COLUMN province_new TO province
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
         * Buat kolom temporary dengan tipe VARCHAR.
         */
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->string('province_old')->nullable();
        });

        /*
         * Convert BIGINT kembali menjadi VARCHAR.
         *
         * PostgreSQL:
         * province::text
         *
         * MySQL:
         * CAST(province AS CHAR)
         */
        DB::statement("
            UPDATE company_addresses
            SET province_old = CAST(province AS CHAR)
        ");

        /*
         * Hapus kolom province BIGINT.
         */
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        /*
         * Rename province_old menjadi province.
         */
        DB::statement("
            ALTER TABLE company_addresses
            RENAME COLUMN province_old TO province
        ");
    }
}