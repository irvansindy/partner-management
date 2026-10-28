<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCodesToIndonesianRegions extends Migration
{
    public function up()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->string('code', 2)->nullable()->unique()->after('id');
        });
        Schema::table('regencies', function (Blueprint $table) {
            $table->string('code', 4)->nullable()->unique()->after('id');
        });

        foreach ($this->provinceCodes() as $name => $code) {
            DB::table('provinces')->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->update(['code' => $code]);
        }
    }

    public function down()
    {
        Schema::table('regencies', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }

    private function provinceCodes(): array
    {
        return [
            'Aceh' => '11', 'Sumatera Utara' => '12', 'Sumatera Barat' => '13', 'Riau' => '14',
            'Jambi' => '15', 'Sumatera Selatan' => '16', 'Bengkulu' => '17', 'Lampung' => '18',
            'Bangka Belitung' => '19', 'Kepulauan Riau' => '21', 'DKI Jakarta' => '31',
            'Jawa Barat' => '32', 'Jawa Tengah' => '33', 'DI Yogyakarta' => '34', 'Jawa Timur' => '35',
            'Banten' => '36', 'Bali' => '51', 'Nusa Tenggara Barat' => '52',
            'Nusa Tenggara Timur' => '53', 'Kalimantan Barat' => '61', 'Kalimantan Tengah' => '62',
            'Kalimantan Selatan' => '63', 'Kalimantan Timur' => '64', 'Kalimantan Utara' => '65',
            'Sulawesi Utara' => '71', 'Sulawesi Tengah' => '72', 'Sulawesi Selatan' => '73',
            'Sulawesi Tenggara' => '74', 'Gorontalo' => '75', 'Sulawesi Barat' => '76',
            'Maluku' => '81', 'Maluku Utara' => '82', 'Papua' => '91', 'Papua Barat' => '92',
            'Papua Selatan' => '93', 'Papua Tengah' => '94', 'Papua Pegunungan' => '95',
            'Papua Barat Daya' => '96',
        ];
    }
}
