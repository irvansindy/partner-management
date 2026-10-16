<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandCompanyAttachmentMetadata extends Migration
{
    public function up()
    {
        Schema::table('company_attachments', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('filename');
            $table->string('mime_type', 100)->nullable()->after('filetype');
            $table->string('storage_disk', 50)->default('public')->after('filepath');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->char('sha256', 64)->nullable();
        });
    }

    public function down()
    {
        Schema::table('company_attachments', function (Blueprint $table) {
            $table->dropColumn(['original_name', 'mime_type', 'storage_disk', 'width', 'height', 'sha256']);
        });
    }
}
