<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixParentIdTypeInMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Ubah string kosong menjadi NULL terlebih dahulu.
         *
         * Ini penting karena parent_id akan diubah
         * menjadi BIGINT.
         */
        DB::statement("
            UPDATE menus
            SET parent_id = NULL
            WHERE parent_id = ''
        ");

        /*
         * Ubah tipe parent_id menjadi BIGINT.
         *
         * NULL tetap diperbolehkan karena root menu
         * biasanya tidak mempunyai parent.
         */
        DB::statement("
            ALTER TABLE menus
            MODIFY COLUMN parent_id BIGINT NULL
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
         * Kembalikan parent_id menjadi VARCHAR(255).
         */
        DB::statement("
            ALTER TABLE menus
            MODIFY COLUMN parent_id VARCHAR(255) NULL
        ");
    }
}