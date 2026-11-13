<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDepartment;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DepartmentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('admin.department.index');
    }
    public function fetchDepartment()
    {
        try {
            $department = MasterDepartment::all();
            return FormatResponseJson::success($department, __('settings.department fetched successfully'));
        } catch (\Exception $e) {
            report($e);
            return FormatResponseJson::error(null,__('settings.operation_failed'), 500);
        }
    }
    public function CreateOrUpdateDepartment(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'department_name' => 'required|string',
            ], array_merge(__('settings_validation.messages'), [
                'department_name.required'=> __('settings.Nama Department tidak boleh kosong'),
            ]), __('settings_validation.attributes'));
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $department = MasterDepartment::updateOrCreate(
                ['id' => $request->department_id],
                ['name' => $request->department_name]
            );
            DB::commit();
            return FormatResponseJson::success($department, __('settings.department created/updated successfully'));
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            report($e);
            DB::rollback();
            return FormatResponseJson::error(null,__('settings.operation_failed'), 400);
        }
    }
}
