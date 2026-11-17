<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterDivision;
use Illuminate\Support\Facades\DB;
class MasterDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            [
                'name' => 'FAT',
                'gm_user_id' => null,
                'office_id' => DB::table('master_offices')->where('name', 'Head Office')->value('id'),
            ],
            [
                'name' => 'Manufacturing',
                'gm_user_id' => null,
                'office_id' => DB::table('master_offices')->where('name', 'Pabrik Cimanggis')->value('id'),
            ],
            [
                'name' => 'Sales and Marketing',
                'gm_user_id' => null,
                'office_id' => DB::table('master_offices')->where('name', 'Head Office')->value('id'),
            ],
            [
                'name' => 'Supporting',
                'gm_user_id' => null,
                'office_id' => DB::table('master_offices')->where('name', 'Head Office')->value('id'),
            ],
        ];

        foreach ($divisions as $division) {
            $model = MasterDivision::firstOrCreate(['name' => $division['name']], $division);
            if ($model->office_id === null) {
                $model->update(['office_id' => $division['office_id']]);
            }
        }
    }
}
