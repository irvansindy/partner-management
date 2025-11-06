<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormLinkIdToCompanyInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->unsignedBigInteger('form_link_id')->nullable()->after('id');
            $table->foreign('form_link_id')->references('id')->on('form_links')->onDelete('set null');
            $table->index('form_link_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_informations', function (Blueprint $table) {
            $table->dropForeign(['form_link_id']);
            $table->dropColumn('form_link_id');
        });
    }
}
