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
            return FormatResponseJson::success($offices, 'office fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function fetchOfficeById(Request $request)
    {
        try {
            $office = MasterOffice::find($request->id);
            if (!$office) {
                return FormatResponseJson::error(null, 'Office not found', 404);
            }
            return FormatResponseJson::success($office, 'office fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function CreateOrUpdateOffice(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'office_name' => 'required|string',
                'office_address' => 'required|string',
            ], [
                'office_name.required'=> 'Nama Office tidak boleh kosong',
                'office_address.required'=> 'Alamat Office tidak boleh kosong',
            ]);
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
            return FormatResponseJson::success($office, 'Office created/updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
}
