<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        DB::statement('
            ALTER TABLE menus
            ALTER COLUMN parent_id TYPE BIGINT
            USING NULLIF(parent_id, \'\')::bigint
        ');
    }

    public function down()
    {
        DB::statement('
            ALTER TABLE menus
            ALTER COLUMN parent_id TYPE VARCHAR(255)
        ');
    }
}
