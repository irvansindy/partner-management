<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderVendorSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_vendor_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('company_information_id');
            $table->string('proposal_document');
            $table->longText('comment')->nullable();
            $table->enum('status', ['pending','checking','approved', 'rejected'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_vendor_submissions');
    }
}
