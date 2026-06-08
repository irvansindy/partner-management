<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixParentIdTypeInMenus extends Migration
{
    public function up()
    {
        // Set empty string ke NULL dulu sebelum ganti tipe kolom
        // (pengganti NULLIF(parent_id, '')::bigint di PostgreSQL)
        DB::statement("UPDATE menus SET parent_id = NULL WHERE parent_id = ''");

        // Ganti tipe kolom ke BIGINT — MySQL syntax
        DB::statement("ALTER TABLE menus MODIFY COLUMN parent_id BIGINT NULL");
    }

    public function down()
    {
        // Kembalikan ke VARCHAR — MySQL syntax
        DB::statement("ALTER TABLE menus MODIFY COLUMN parent_id VARCHAR(255) NULL");
    }
}