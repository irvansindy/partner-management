<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenders;
use App\Models\TenderDetailProducts;
use App\Models\TenderVendorAccess;
use App\Models\TenderVendorSubmissions;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class TenderForVendorController extends Controller
{
    public function index()
    {
        return view('admin.tenders.vendor.index');
    }
    public function fetchTenderVendor()
    {
        try {
            $tenders = Tenders::with(['detailProduct', 'vendorSubmission', 'user', 'eula'])->get();
            $message = '';
            $list_tender = count($tenders) == 0 ? null : $tenders;
            $message = count($tenders) == 0 ? 'tender vendor is empty':'tender vendor fetched successfully';
            return FormatResponseJson::success($list_tender, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function storeTenderVendor(Request $request)
    {
        try {
            // dd(date('Y-m-d h:i:s'));
            DB::beginTransaction();
            $validations = Validator::make($request->all(), [
                'create_tender_name'=> 'required|string',
                'create_tender_type'=> 'required|string',
                'create_tender_source_document'=> 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10480',
                'create_tender_created_date'=> 'required|string',
                'create_tender_effective_date'=> 'required|string',
                'create_tender_expired_date'=> 'required|string',
                'create_tender_product_name.*'=> 'required|string',
                'create_tender_product_requirement.*'=> 'required|string',
                'create_tender_product_list_vendor' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Cek jika `create_tender_product_list_vendor` adalah array
                    if (is_array($value)) {
                        // Jika array, pastikan elemen pertama tidak boleh kosong
                        if (empty($value[0])) {
                            $fail('The first item in ' . $attribute . ' cannot be empty.');
                        }
                    } else {
                        // Jika bukan array, pastikan berupa string
                        if (!is_string($value)) {
                            $fail($attribute . ' must be a string.');
                        }
                    }
                },]
            ]);

            if ($validations->fails()) {
                throw new ValidationException($validations);
            }

            // store data
            // master tender
            $data_tender = [
                'user_id' => \Auth::user()->id,
                // 'tender_type_id' => $request->create_tender_type,
                'tender_type_id' => 1,
                'eula_tnc_id' => 1,
                'name' => $request->create_tender_name,
                'created_date' => $request->create_tender_created_date,
                'ordering_date' => $request->create_tender_effective_date,
                'expired_date' => $request->create_tender_expired_date,
                'source_document' => null,
                'location_id' => \Auth::user()->office_id,
                'status' => 'open',
            ];
            $create_tender = Tenders::create($data_tender);

            // setup upload tender document
            $tender_document = $request->file('create_tender_source_document');
            if ($tender_document != null && $create_tender) {
                // setup file name
                $name = Str::slug($request->create_tender_name, '_');
                $file = $tender_document;
                $file_name = $name.$file->getClientOriginalExtension();
                $path = public_path('storage/uploads/tender_vendor/');
                $result_file = $path.$file_name;

                // store data to database and local storage
                $create_tender->update(['source_document'=> $result_file]);
                $file->move($path, $file_name);
            }

            // tender product list
            $array_tender_product = $request->create_tender_product_name;
            $array_tender_product_requirement = $request->create_tender_product_requirement;
            if (count($array_tender_product) > 0 && count($array_tender_product_requirement) > 0) {
                $array_product = [];
                $list_data_product = [];
                for ($i=0; $i < count($array_tender_product); $i++) { 
                    $list_data_product = [
                        'tender_id' => $create_tender->id,
                        // 'tender_id' => 1,
                        'product_name' => $array_tender_product[$i],
                        'product_requirement' => $array_tender_product_requirement[$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    // dd($list_data_product);
                    array_push($array_product, $list_data_product);
                }
                // dd($array_product);
                $create_tender_product = TenderDetailProducts::insert($array_product);
            }

            // tender vendor list
            $list_vendor = $request->create_tender_product_list_vendor;
            if ($list_vendor != null && count($list_vendor) > 0) {
                $array_vendor = [];
                $list_data_vendor = [];
                for ($i= 0; $i < count($list_vendor); $i++) {
                    $list_data_vendor = [
                        // 'tender_id' => $create_tender->id,
                        'tender_id' => 1,
                        'company_information_id' => $list_vendor[$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    array_push($array_vendor, $list_data_vendor);
                }
                $create_tender_vendor = TenderVendorAccess::insert($array_vendor);
            }

            DB::commit();
            return FormatResponseJson::success($create_tender, 'tender created successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function fetchTenderVendorById(Request $request)
    {
        try {
            $tenders = Tenders::with(['detailProduct', 'vendorSubmission', 'user', 'eula'])
            ->where('id', $request->id)
            ->first();
            $message = '';
            // dd($tenders);
            $detail_tender = $tenders == null ? null : $tenders;
            $message = $tenders == null ? 'tender vendor is empty':'tender vendor fetched successfully';
            return FormatResponseJson::success($detail_tender, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
