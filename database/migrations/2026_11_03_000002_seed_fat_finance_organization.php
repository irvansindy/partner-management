<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedFatFinanceOrganization extends Migration
{
    public function up()
    {
        $now = now();

        DB::table('master_divisions')->updateOrInsert(['name' => 'FAT'], [
            'updated_at' => $now,
            'created_at' => $now,
        ]);

        $fatDivisionId = DB::table('master_divisions')
            ->where('name', 'FAT')
            ->value('id');

        DB::table('master_departments')->updateOrInsert(['name' => 'Finance'], [
            'division_id' => $fatDivisionId,
            'updated_at' => $now,
            'created_at' => $now,
        ]);
    }

    public function down()
    {
        // Master organization data is intentionally preserved on rollback.
    }
}
