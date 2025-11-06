<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_link_id')->constrained()->onDelete('cascade');

            // Master Information
            $table->string('company_type');
            $table->string('company_name');
            $table->string('company_group_name')->nullable();
            $table->year('established_year')->nullable();
            $table->integer('total_employee')->nullable();
            $table->json('liable_persons'); // array of liable persons
            $table->string('business_classification');
            $table->text('business_classification_detail')->nullable();
            $table->string('register_number_as_in_tax_invoice')->nullable();
            $table->string('website_address')->nullable();
            $table->string('system_management')->nullable();
            $table->string('email_address')->nullable();
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->string('term_of_payment')->nullable();

            // Contact Persons - stored as JSON
            $table->json('contacts');

            // Addresses - stored as JSON
            $table->json('addresses');

            // Bank Data - stored as JSON
            $table->json('banks');

            // Survey Data (hanya untuk customer) - stored as JSON
            $table->json('survey_data')->nullable();

            // File attachments - stored as JSON (path files)
            $table->json('attachments')->nullable();

            // Metadata
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->index('form_link_id');
            $table->index('company_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_submissions');
    }
}
