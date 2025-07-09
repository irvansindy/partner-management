<?php

namespace Database\Seeders;

use App\Models\MasterApprovalModel;
use App\Models\DetailApprovalModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterApprovalSeeder extends Seeder
{
    public function run()
    {
        // --- SALES RETAIL 1 ---
        $salesRetail1 = MasterApprovalModel::create([
            'name'          => 'Sales Retail 1 Customer Approval',
            'user_id'=> 12,
            'location_id'   => 1, // Head Office
            'department_id' => 10, // Sales Department
            'step_ordering' => 2,
            'status'        => '0'
        ]);

        // Step 1: Admin Sales (Pilih salah satu)
        $adminRetail1 = User::where('name', 'like', '%Stephanie%')->first();
        if ($adminRetail1) {
            DetailApprovalModel::create([
                'approval_id'   => $salesRetail1->id,
                'user_id'       => $adminRetail1->id,
                'step_ordering' => 1,
                'status'        => '0'
            ]);
        }

        // Step 2: Sales Manager
        $salesManagerRetail1 = User::where('name', 'like', '%Anen%')->first();
        if ($salesManagerRetail1) {
            DetailApprovalModel::create([
                'approval_id'   => $salesRetail1->id,
                'user_id'       => $salesManagerRetail1->id,
                'step_ordering' => 2,
                'status'        => '0'
            ]);
        }

        // --- SALES RETAIL 2 ---
        $salesRetail2 = MasterApprovalModel::create([
            'name' => 'Sales Retail 2 Customer Approval',
            'user_id'=> 6,
            'location_id'   => 1,
            'department_id' => 11,
            'step_ordering' => 2,
            'status'        => '0'
        ]);

        $adminRetail2 = User::where('name', 'like', '%Angelina Agnes Langitan%')->first();
        if ($adminRetail2) {
            DetailApprovalModel::create([
                'approval_id'   => $salesRetail2->id,
                'user_id'       => $adminRetail2->id,
                'step_ordering' => 1,
                'status'        => '0'
            ]);
        }

        $salesManagerRetail2 = User::where('name', 'like', '%Advent%')->first();
        if ($salesManagerRetail2) {
            DetailApprovalModel::create([
                'approval_id'   => $salesRetail2->id,
                'user_id'       => $salesManagerRetail2->id,
                'step_ordering' => 2,
                'status'        => '0'
            ]);
        }

        // --- PURCHASING ---
        $purchasingApproval = MasterApprovalModel::create([
            'name'          => 'Purchasing Approval',
            'user_id'=> 20,
            'location_id'   => 1,
            'department_id' => 2,
            'step_ordering' => 3,
            'status'        => '0'
        ]);

        // Step 1: Admin Purchasing
        $adminPurchasing = User::where('name', 'like', '%Siti Choirunnisa%')->first();
        if ($adminPurchasing) {
            DetailApprovalModel::create([
                'approval_id'   => $purchasingApproval->id,
                'user_id'       => $adminPurchasing->id,
                'step_ordering' => 1,
                'status'        => '0'
            ]);
        }

        // Step 2: Purchasing Manager
        $purchasingManager = User::where('name', 'like', '%Tryvena%')->first();
        if ($purchasingManager) {
            DetailApprovalModel::create([
                'approval_id'   => $purchasingApproval->id,
                'user_id'       => $purchasingManager->id,
                'step_ordering' => 2,
                'status'        => '0'
            ]);
        }

        // Step 3: Direktur Purchasing
        $direkturPurchasing = User::where('name', 'like', '%Djoko Widjaja%')->first();
        if ($direkturPurchasing) {
            DetailApprovalModel::create([
                'approval_id'   => $purchasingApproval->id,
                'user_id'       => $direkturPurchasing->id,
                'step_ordering' => 3,
                'status'        => '0'
            ]);
        }
    }
}
