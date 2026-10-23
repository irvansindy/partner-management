<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesTable extends Migration
{
    public function up()
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->restrictOnDelete();
            $table->foreignId('regency_id')->constrained('regencies')->restrictOnDelete();
            $table->string('district');
            $table->string('village');
            $table->string('postal_code', 5);
            $table->timestamps();
            $table->unique(['province_id', 'regency_id', 'district', 'village', 'postal_code'], 'postal_codes_location_unique');
            $table->index(['regency_id', 'postal_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('postal_codes');
    }
}
