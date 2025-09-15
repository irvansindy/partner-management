<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLtmTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltm_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status')->default(0);
            $table->string('locale', 191);
            $table->string('group', 191);
            $table->text('key');
            $table->text('value')->nullable();
            $table->timestamps();
            // Table-specific settings
    $table->engine = 'InnoDB';
    $table->charset = 'utf8';
    $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ltm_translations');
    }
}
