<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLatitudeLongitudeTableProvincesRegencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->double('latitude', 15, 8)->nullable()->after('name');
            $table->double('longitude', 15, 8)->nullable()->after('latitude');
            $table->string('capital')->nullable()->after('longitude');
        });
        Schema::table('regencies', function (Blueprint $table) {
            $table->double('latitude', 15, 8)->nullable()->after('name');
            $table->double('longitude', 15, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'capital']);
        });
        Schema::table('regencies', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}
