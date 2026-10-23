<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeCompanyAddressProvinceToString extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE company_addresses MODIFY province VARCHAR(100) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE company_addresses ALTER COLUMN province TYPE VARCHAR(100) USING province::varchar');
        }
    }

    public function down()
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $foreignProvinceCount = DB::table('company_addresses')
                ->whereNotNull('province')
                ->whereRaw("province NOT REGEXP '^[0-9]+$'")
                ->count();

            if ($foreignProvinceCount > 0) {
                throw new \RuntimeException('Cannot restore province to BIGINT while international province values exist.');
            }

            DB::statement('ALTER TABLE company_addresses MODIFY province BIGINT NULL');
        } elseif ($driver === 'pgsql') {
            $foreignProvinceCount = DB::table('company_addresses')
                ->whereNotNull('province')
                ->whereRaw("province !~ '^[0-9]+$'")
                ->count();

            if ($foreignProvinceCount > 0) {
                throw new \RuntimeException('Cannot restore province to BIGINT while international province values exist.');
            }

            DB::statement('ALTER TABLE company_addresses ALTER COLUMN province TYPE BIGINT USING province::bigint');
        }
    }
}
