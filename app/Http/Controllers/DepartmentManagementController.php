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
            return FormatResponseJson::success($department, 'department fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function CreateOrUpdateDepartment(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'department_name' => 'required|string',
            ], [
                'department_name.required'=> 'Nama Department tidak boleh kosong',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $department = MasterDepartment::updateOrCreate(
                ['id' => $request->department_id],
                ['name' => $request->department_name]
            );
            DB::commit();
            return FormatResponseJson::success($department, 'department created/updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
}
