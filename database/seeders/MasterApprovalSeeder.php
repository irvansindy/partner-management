<?php
// database/seeders/MasterApprovalSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterApprovalModel;
use App\Models\DetailApprovalModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MasterApprovalSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Sales Retail 1
            $salesRetail1 = MasterApprovalModel::create([
                'name' => 'Sales Retail 1 Customer Approval',
                'user_id' => 1,
                'location_id' => 1,
                'department_id' => 10,
                'step_ordering' => 1,
                'status' => 1
            ]);

            DetailApprovalModel::insert([
                [
                    'approval_id' => $salesRetail1->id,
                    'user_id' => User::where('name', 'LIKE', '%Anen%')->value('id'),
                    'step_ordering' => 2,
                    'status' => 1
                ]
            ]);

            // Sales Retail 2
            $salesRetail2 = MasterApprovalModel::create([
                'name' => 'Sales Retail 2 Customer Approval',
                'user_id' => 1,
                'location_id' => 1,
                'department_id' => 11,
                'step_ordering' => 1,
                'status' => 1
            ]);

            DetailApprovalModel::insert([
                [
                    'approval_id' => $salesRetail2->id,
                    'user_id' => 8,
                    'step_ordering' => 2,
                    'status' => 1
                ],
                [
                    'approval_id' => $salesRetail2->id,
                    'user_id' => 28,
                    'step_ordering' => 2,
                    'status' => 1
                ]
            ]);

            // Purchasing
            $purchasing = MasterApprovalModel::create([
                'name' => 'Purchasing Customer Approval',
                'user_id' => 1,
                'location_id' => 2,
                'department_id' => 6, // Purchasing
                'step_ordering' => 1,
                'status' => 1
            ]);

            DetailApprovalModel::insert([
                [
                    'approval_id' => $purchasing->id,
                    'user_id' => 17,
                    'step_ordering' => 2,
                    'status' => 1
                ],
                [
                    'approval_id' => $purchasing->id,
                    'user_id' => 18,
                    'step_ordering' => 2,
                    'status' => 1
                ],
                [
                    'approval_id' => $purchasing->id,
                    'user_id' => 25,
                    'step_ordering' => 3,
                    'status' => 1
                ]
            ]);
        });
    }
}
