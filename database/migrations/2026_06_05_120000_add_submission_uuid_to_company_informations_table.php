<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->uuid('submission_uuid')->nullable()->after('name')->unique();
        });
    }

    public function down()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropUnique(['submission_uuid']);
            $table->dropColumn('submission_uuid');
        });
    }
};
