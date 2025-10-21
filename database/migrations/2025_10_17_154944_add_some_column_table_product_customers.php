<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnTableProductCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_customers', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->after('id');
            $table->string('name')->after('company_id');
            $table->string('merk')->after('name');
            $table->string('distributor')->after('merk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_customers', function (Blueprint $table) {
            $table->dropColumn(['company_id', 'name', 'merk', 'distributor']);
        });
    }
}
