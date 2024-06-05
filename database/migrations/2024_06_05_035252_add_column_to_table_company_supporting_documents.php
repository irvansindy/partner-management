<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableCompanySupportingDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_supporting_documents', function (Blueprint $table) {
            $table->string('document_type_name')->nullable()->after('document_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_supporting_documents', function (Blueprint $table) {
            $table->dropColumn('document_type_name')->after('document_type');
        });
    }
}
