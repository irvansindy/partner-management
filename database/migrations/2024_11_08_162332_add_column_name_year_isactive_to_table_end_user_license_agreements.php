<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNameYearIsactiveToTableEndUserLicenseAgreements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('end_user_license_agreements', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('year')->nullable()->after('content');
            $table->boolean('is_active')->default(true)->after('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('end_user_license_agreements', function (Blueprint $table) {
            $table->dropColumn('name')->after('user_id');
            $table->dropColumn('year')->after('content');
            $table->dropColumn('is_active')->after('year');
        });
    }
}
