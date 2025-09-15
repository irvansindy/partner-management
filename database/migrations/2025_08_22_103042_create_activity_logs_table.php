<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action'); // create, update, delete, dll
            $table->text('description')->nullable();
            $table->string('model_type')->nullable(); // ex: App\Models\Company
            $table->unsignedBigInteger('model_id')->nullable(); // ex: 1
            $table->json('data')->nullable(); // simpan old/new data, id, dll
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
