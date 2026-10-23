<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->uuid('idempotency_key')->nullable()->after('form_link_id');
            $table->unique(['form_link_id', 'idempotency_key'], 'company_form_idempotency_unique');
        });
    }

    public function down(): void
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropUnique('company_form_idempotency_unique');
            $table->dropColumn('idempotency_key');
        });
    }
};
