<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyAndAddSomeColumnTableCompanyInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Hapus kolom yang sudah tidak digunakan.
         */
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropColumn([
                'liable_person_and_position',
                'liable_position',
                'board_of_directors',
                'major_shareholders',
                'remark',
                'signature',
                'stamp',
            ]);
        });

        /*
         * MySQL menggunakan MODIFY COLUMN,
         * bukan ALTER COLUMN ... TYPE seperti PostgreSQL.
         */
        DB::statement("
            ALTER TABLE company_informations
            MODIFY COLUMN type VARCHAR(20)
        ");

        /*
         * Tambahkan kolom baru.
         */
        Schema::table('company_informations', function (Blueprint $table) {
            $table->string('term_of_payment')->nullable();
            $table->integer('credit_limit')->nullable();
            $table->string('npwp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
         * Hapus kolom yang ditambahkan pada up().
         */
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropColumn([
                'term_of_payment',
                'credit_limit',
                'npwp',
            ]);
        });

        /*
         * Kembalikan tipe kolom type.
         *
         * Sesuaikan VARCHAR(50) dengan struktur database
         * sebelum migration ini dijalankan.
         */
        DB::statement("
            ALTER TABLE company_informations
            MODIFY COLUMN type VARCHAR(50)
        ");

        /*
         * Kembalikan kolom yang dihapus pada up().
         */
        Schema::table('company_informations', function (Blueprint $table) {
            $table->string('liable_person_and_position')->nullable();
            $table->string('liable_position')->nullable();
            $table->string('board_of_directors')->nullable();
            $table->string('major_shareholders')->nullable();
            $table->text('remark')->nullable();
            $table->string('signature')->nullable();
            $table->string('stamp')->nullable();
        });
    }
}