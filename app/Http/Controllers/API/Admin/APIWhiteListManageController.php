<?php

namespace App\Http\Controllers\API\Admin;

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
            return formatResponseJson::success($list_api,'Data fetched successfully');
        } catch (\Exception $e) {
            return formatResponseJson::error(null, 'Failed to fetch data: ' . $e->getMessage(), 500);
        }
    }
    public function createOrUpdate(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required|ip',
                'description' => 'required|string|max:255',
            ], [
                'ip_address.required'=> 'IP address is required',
                'ip_address.ip'=> 'IP address must be a valid IP address',
                'description.required'=> 'Description must be a string and cannot be null',
                'description.max'=> 'Description must not exceed 255 characters',
            ]);

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
            return FormatResponseJson::success($data, 'IP address created/updated successfully');

        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

}
