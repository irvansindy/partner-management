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
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropColumn(['liable_person_and_position', 'liable_position', 'board_of_directors', 'major_shareholders', 'remark', 'signature', 'stamp']);
        });

        // Ubah kolom type menggunakan raw SQL
        // DB::statement("ALTER TABLE company_informations MODIFY COLUMN type ENUM('vendor', 'customer') NOT NULL");
        DB::statement("ALTER TABLE company_informations ALTER COLUMN type TYPE VARCHAR(20)");

        // Schema::table('company_informations', function (Blueprint $table) {
        //     $table->enum('term_of_payment', ['30', '45', '60', '90'])->after('email_address')->nullable();
        //     $table->integer('credit_limit')->after('term_of_payment')->nullable();
        //     $table->string('npwp')->nullable()->after('owner_name');
        // });
        Schema::table('company_informations', function (Blueprint $table) {
        // Hapus 'after' karena Postgres tidak mendukung pengurutan kolom (otomatis ditaruh di akhir)
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
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropColumn(['term_of_payment', 'credit_limit', 'npwp']);

        });

        // Kembalikan kolom type ke nilai semula
        // DB::statement("ALTER TABLE company_informations MODIFY COLUMN type ENUM('customer', 'vendor', 'customer dan vendor') NOT NULL");
        DB::statement("ALTER TABLE company_informations ALTER COLUMN type TYPE VARCHAR(50)");

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