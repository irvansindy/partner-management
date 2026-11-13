<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiWhitelist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatResponseJson;
class APIWhiteListManageController extends Controller
{
    public function index()
    {
        return view('admin.api_white_list.index');
    }
    public function fetch()
    {
        try {
            $list_api = ApiWhitelist::all();
            return formatResponseJson::success($list_api,__('settings.Data fetched successfully'));
        } catch (\Exception $e) {
            report($e);
            return formatResponseJson::error(null, __('settings.operation_failed'), 500);
        }
    }
    public function fetchById(Request $request)
    {
        try {
            $data = ApiWhitelist::findOrFail($request->id);

            return FormatResponseJson::success($data, __('settings.IP address fetched successfully'));
        } catch (\Exception $e) {
            report($e);
            return FormatResponseJson::error(null, __('settings.operation_failed'), 404);
        }
    }

    public function createOrUpdate(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required|ip',
                'description' => 'required|string|max:255',
            ], array_merge(__('settings_validation.messages'), [
                'ip_address.required'=> __('settings.IP address is required'),
                'ip_address.ip'=> __('settings.IP address must be a valid IP address'),
                'description.required'=> __('settings.Description must be a string and cannot be null'),
                'description.max'=> __('settings.Description must not exceed 255 characters'),
            ]), __('settings_validation.attributes'));

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            if ($request->filled('id_ip_address')) {
                // UPDATE
                $data = ApiWhitelist::where('id', $request->id_ip_address)->firstOrFail();
                $data->update([
                    'ip_address' => $request->ip_address,
                    'description' => $request->description,
                ]);
            } else {
                // CREATE
                $data = ApiWhitelist::create([
                    'ip_address' => $request->ip_address,
                    'description' => $request->description,
                ]);
            }

            DB::commit();
            return FormatResponseJson::success($data, __('settings.IP address created/updated successfully'));

        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            report($e);
            DB::rollback();
            return FormatResponseJson::error(null, __('settings.operation_failed'), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = ApiWhitelist::findOrFail($request->id);
            $data->delete();

            return FormatResponseJson::success(null, __('settings.IP address deleted successfully'));
        } catch (\Exception $e) {
            report($e);
            return FormatResponseJson::error(null, __('settings.operation_failed'), 500);
        }
    }

}
