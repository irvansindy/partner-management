<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterOffice;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class OfficeManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('admin.office.index');
    }
    public function fetchOffice()
    {
        try {
            $offices = MasterOffice::all();
            return FormatResponseJson::success($offices, __('settings.office fetched successfully'));
        } catch (\Exception $e) {
            report($e);
            return FormatResponseJson::error(null,__('settings.operation_failed'), 500);
        }
    }
    public function fetchOfficeById(Request $request)
    {
        try {
            $office = MasterOffice::find($request->id);
            if (!$office) {
                return FormatResponseJson::error(null, __('settings.Office not found'), 404);
            }
            return FormatResponseJson::success($office, __('settings.office fetched successfully'));
        } catch (\Exception $e) {
            report($e);
            return FormatResponseJson::error(null,__('settings.operation_failed'), 500);
        }
    }
    public function CreateOrUpdateOffice(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'office_name' => 'required|string',
                'office_address' => 'required|string',
            ], array_merge(__('settings_validation.messages'), [
                'office_name.required'=> __('settings.Nama Office tidak boleh kosong'),
                'office_address.required'=> __('settings.Alamat Office tidak boleh kosong'),
            ]), __('settings_validation.attributes'));
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $office = MasterOffice::updateOrCreate(
    ['id' => $request->office_id],
        [
                    'name' => $request->office_name,
                    'address' => $request->office_address,
                ]
            );
            DB::commit();
            return FormatResponseJson::success($office, __('settings.Office created/updated successfully'));
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
