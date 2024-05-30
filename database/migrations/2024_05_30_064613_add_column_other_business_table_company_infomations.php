<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOtherBusinessTableCompanyInfomations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_infomations', function (Blueprint $table) {
            $table->string('other_business')->nullable()->after('business_classification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_infomations', function (Blueprint $table) {
            $table->dropColumn('other_business')->after('business_classification');
        });
    }
}
