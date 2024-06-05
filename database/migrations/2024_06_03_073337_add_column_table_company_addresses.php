<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTableCompanyAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->boolean('main')->nullable()->after('fax')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn('main')->after('fax');
        });
    }
}
