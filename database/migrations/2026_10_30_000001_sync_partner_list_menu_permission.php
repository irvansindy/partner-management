<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SyncPartnerListMenuPermission extends Migration
{
    public function up(): void
    {
        DB::table('menus')
            ->where('can_permission', 'partner-management')
            ->update(['can_permission' => 'partner-list']);
    }

    public function down(): void
    {
        DB::table('menus')
            ->where('can_permission', 'partner-list')
            ->update(['can_permission' => 'partner-management']);
    }
}
