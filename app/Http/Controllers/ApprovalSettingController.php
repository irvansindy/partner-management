<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\MasterApprovalModel;
use App\Models\DetailApprovalModel;
use App\Models\User;
use App\Models\MasterOffice;
use App\Models\MasterDepartment;
class ApprovalSettingController extends Controller
{
    public function index()
    {
        return view('admin.approval_setting.index');
    }
    public function fetchApproval()
    {
        try {
            $master_approval = MasterApprovalModel::with(['user', 'master_departments', 'master_offices'])->get();
            return FormatResponseJson::success($master_approval, 'role fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function fetchUserApproval(Request $request)
    {
        try {
            // dd($request->master_approval_id);
            $user = User::with('roles')->whereHas('roles', function($q) {
                $q->whereIn('name', ['admin', 'super-user']);
            })->get(['id', 'name']);
            $existing_approval = DetailApprovalModel::where(['approval_id' => $request->master_approval_id])->get();
            $data = [
                'users' => $user,
                'existing_approval' => $existing_approval,
            ];
            return FormatResponseJson::success($data, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function fetchOfficeAndDepartment()
    {
        try {
            $office = MasterOffice::get(['id', 'name']);
            $department = MasterDepartment::get(['id', 'name']);
            $data = [
                'offices' => $office,
                'departments'=> $department
            ];
            return FormatResponseJson::success($data, 'office and department fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function storeApprovalMaster(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = MasterApprovalModel::where(['location_id' => $request->location_id, 'department_id' => $request->department_id])->first();
            if ($existing_data) {
                return FormatResponseJson::error(null, 'data sudah ada.', 400);
            }

            $validator = Validator::make($request->all(), [
                'stagging_approval_office' => 'required',
                'stagging_approval_department' => 'required',
            ], [
                
            ]);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $master_approval = MasterApprovalModel::create([
                'name' => $request->approval_master_name,
                'user_id' => \Auth::user()->id,
                'location_id' => $request->stagging_approval_office,
                'department_id' => $request->stagging_approval_department,
                'step_ordering' => 1,
                'status' => '1',
            ]);
            
            DB::commit();
            // return FormatResponseJson::success($partner, 'partner profile created successfully');
            return FormatResponseJson::success('success', 'Master model approval berhasil dibuat');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function submitApprovalDetail(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'stagging_approval_name'=> 'required',
            ]);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $delete_existing = DetailApprovalModel::where(['approval_id' => $request->master_approval_id])->delete();
            if ($delete_existing) {
                // return FormatResponseJson::error(null, 'data sudah ada.', 400);
                if(count($request->stagging_approval_name) != 0) {
                    for ($i=0; $i < count($request->stagging_approval_name); $i++) {
                        $approval_stagging = DetailApprovalModel::create([
                            'approval_id' => $request->master_approval_id,
                            'user_id' => $request->stagging_approval_name[$i],
                            'step_ordering' => $i+1,
                            'status' => $i == 0 ? 0 : 1,
                        ]);
                    }
                }
            }

            DB::commit();
            // return FormatResponseJson::success($partner, 'partner profile created successfully');
            return FormatResponseJson::success('success', 'data approval modal berhasil dibuat');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
