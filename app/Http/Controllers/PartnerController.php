<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInformation;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\CompanyContact;
use App\Models\CompanyAttachment;
use App\Models\CompanyLiablePerson;
use App\Models\SalesSurvey;
use App\Models\ProductCustomer;
use App\Models\MasterIncomeStatement;
use App\Models\MasterBalanceSheet;
use App\Models\CompanySupportingDocument;
use App\Models\MasterActivityLog;
use App\Models\ActivityLogs;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Jobs\GeocodeAddressJob;
class PartnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
        // $this->middleware(['auth', 'check.company.info']);
    // }
    public function index()
    {
        return view("company.index");
    }
    public function fetchPartnerByUserId()
    {
        try {
            $company_profile = CompanyInformation::with(['user'])
            ->where("user_id", auth()->user()->id)->get();

            if ($company_profile) {
                return FormatResponseJson::success($company_profile, 'Company profile fetched successfully');
            } else {
                return FormatResponseJson::error(null, 'Company profile not found', 404);
            }
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function viewCreatePartner()
    {
        return view('cs_vendor.index');
    }
    public function viewAttachment()
    {
        return view('cs_vendor.attachment');
    }
    public function detailPartner(Request $request)
    {
        return view('company.detail_company');
    }
    public function fetchCompany()
    {
        try {
            // dd(auth()->user()->id);
            $company_profile = CompanyInformation::with(['user', 'contact', 'address', 'bank', 'tax', 'attachment'])
            ->where("user_id", auth()->user()->id)->first();

            $result_detail_partner = $company_profile != null ? $company_profile : null;
            $doc_pt = null;
            $doc_cv = null;
            $doc_ud_or_pd = null;
            $doc_perorangan = null;
            $supporting_document_user = null;
            $doc_type = CompanyDocumentTypeCategories::get(['id', 'name', 'name_id_class']);
            if ($result_detail_partner != null) {
                $doc_pt = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'pt')
                ->get();

                $doc_cv = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'cv')
                ->get();

                $doc_ud_or_pd = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'ud_or_pd')
                ->get();

                $doc_perorangan = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'perorangan')
                ->get();

                $supporting_document_user = CompanySupportingDocument::where('company_id', $company_profile->id)->get();
            }

            // 🔥 Log aktivitas di sini (langsung di function fetchCompany)
            // Ambil log terbaru, paginasi 15 per halaman
            // $logs = ActivityLogs::where('user_id', auth()->user()->id)->latest()->paginate(15);
            $logs = MasterActivityLog::with(['logs', 'user'])
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->user()->id);
            })
            ->paginate(10);

            $data = [
                $company_profile,
                $doc_type,
                'pt' => $doc_pt,
                'cv' => $doc_cv,
                'ud_or_pd' => $doc_ud_or_pd,
                'perorangan' => $doc_perorangan,
                'document' => $supporting_document_user,
                'logs' => $logs,
            ];
            return FormatResponseJson::success($data, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function ActivityLogs()
    {
        try {
            $logs = ActivityLogs::latest()->paginate(15);
            return FormatResponseJson::success($logs, 'Activity logs fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchCompanyPartnerById()
    {
        try {
            $company_profile = CompanyInformation::with(['user'])
            ->where("user_id", auth()->user()->id)->first();

            return FormatResponseJson::success($company_profile, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchContactById(Request $request)
    {
        try {
            $contact = CompanyContact::with(['company.user'])->whereHas('company', function ($q) use ($request) {
                $q->where('company_informations_id', $request->id);
                $q->where('user_id', auth()->user()->id);
            })->get();
            return FormatResponseJson::success($contact,'contact fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchAddressById(Request $request)
    {
        try {
            $address = CompanyAddress::with(['company.user'])->whereHas('company', function ($q) use ($request) {
                $q->where('company_id', $request->id);
                $q->where('user_id', auth()->user()->id);
            })->get();
            return FormatResponseJson::success($address,'address fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchBankById(Request $request)
    {
        try {
            $bank = CompanyBank::with(['company.user'])->whereHas('company', function ($q) use ($request) {
                $q->where('company_id', $request->id);
                $q->where('user_id', auth()->user()->id);
            })->get();
            return FormatResponseJson::success($bank,'bank fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchTaxById(Request $request)
    {
        try {
            $tax = CompanyTax::with(['company.user'])->whereHas('company', function ($q) use ($request) {
                $q->where('company_id', $request->id);
                $q->where('user_id', auth()->user()->id);
            })->get();
            return FormatResponseJson::success($tax,'tax fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchDocTypeCategories()
    {
        try {
            $doc_type = CompanyDocumentTypeCategories::all();
            return FormatResponseJson::success($doc_type,'Document Type fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchIncomeStatementBalanceSheet()
    {
        try {
            $income_statement = MasterIncomeStatement::orderBy('id', 'asc')->get(['id','name']);
            $balance_sheet = MasterBalanceSheet::orderBy('id', 'asc')->get(['id','name']);
            $data = [
                'income_statement'=> $income_statement,
                'balance_sheet'=> $balance_sheet
            ];
            return FormatResponseJson::success($data, 'Income Statement and Balance Sheet fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function resultFinancialRatio(Request $request)
    {
        try {
            $data = [];
            $aYearAgo = date("Y", strtotime("-1 year"));
            $twoYearAgo = date("Y", strtotime("-2 year"));
            dd($request->all());

            return FormatResponseJson::success($data, 'Financial ratio fetched successfully');
        } catch (ValidationException $e) {
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = CompanyInformation::where('id', $request->detail_id)->first();

            // Dynamic validation rules
            $rules = [
                'detail_company_name' => 'nullable|string',
                'detail_company_group_name' => 'nullable|string',
                'detail_company_type' => 'nullable|string',
                'detail_established_year' => 'nullable|string',
                'detail_total_employee' => 'nullable|string',
                'detail_liable_person_and_position' => 'nullable|string',
                'detail_owner_name' => 'nullable|string',
                'detail_board_of_directors' => 'nullable|string',
                'detail_major_shareholders' => 'nullable|string',
                'detail_business_classification' => 'nullable|string',
                'detail_business_classification_detail' => 'nullable|string',
                'detail_website_address' => 'nullable|string',
                'detail_system_management' => 'nullable|string',
                'detail_contact_person' => 'nullable|string',
                'detail_communication_language' => 'nullable|string',
                'detail_email_address' => 'nullable|string|email',
                'detail_address.*' => 'nullable|string',
                'detail_city.*' => 'nullable|string',
                'detail_country.*' => 'nullable|string',
                'detail_province.*' => 'nullable|string',
                'detail_zip_code.*' => 'nullable|integer',
                'detail_telephone.*' => 'nullable|string',
                'detail_fax.*' => 'nullable|string',
            ];

            // Only require stamp and signature if they are not already set
            if ($existing_data->stamp == null) {
                if($request->detail_stamp_file == null) {
                    $rules['detail_stamp_file'] = 'required|image|max:10000|mimes:jpg,jpeg,png';
                }
            }

            if ($existing_data->signature == null) {
                if($request->detail_signature_file == null) {
                    $rules['detail_signature_file'] = 'required|image|max:10000|mimes:jpg,jpeg,png';
                }
            }

            // Validation messages
            $messages = [
                'detail_company_name.required' => 'Company name/Nama perusahaan tidak boleh kosong',
                'detail_company_group_name.required' => 'Company group name/Nama grup perusahaan tidak boleh kosong',
                'detail_company_type.required' => 'Company type/tipe perusahaan tidak boleh kosong',
                'detail_established_year.required' => 'Established since year/Tahun berdiri tidak boleh kosong',
                'detail_total_employee.required' => 'Total employee/Jumlah Karyawan tidak boleh kosong',
                'detail_liable_person_and_position.required' => 'Liable person/Penanggung jawab tidak boleh kosong',
                'detail_owner_name.required' => 'Owner name/Nama pemilik tidak boleh kosong',
                'detail_board_of_directors.required' => 'Board of directors/Dewan direktur tidak boleh kosong',
                'detail_major_shareholders.required' => 'Board of directors/Pemilik saham mayoritas tidak boleh kosong',
                'detail_business_classification.required' => 'Business classification/Jenis usaha tidak boleh kosong',
                'detail_business_classification_detail.required' => 'Business classification Detail/Detail Jenis usaha tidak boleh kosong',
                'detail_website_address.required' => 'Website address/Alamat situs web tidak boleh kosong',
                'detail_system_management.required' => 'System management/Manajemen sistem tidak boleh kosong',
                'detail_contact_person.required' => 'Contact person/Kontak person tidak boleh kosong',
                'detail_communication_language.required' => 'Communication language/Bahasa komunikasi tidak boleh kosong',
                'detail_email_address.required' => 'Email address/Alamat email tidak boleh kosong',
                'detail_stamp_file.required' => 'Stamp/Stempel tidak boleh kosong',
                'detail_signature_file.required' => 'Signature/Tanda tangan tidak boleh kosong',
                'detail_address.*.required' => 'Address/Alamat tidak boleh kosong',
                'detail_city.*.required' => 'City/Kota tidak boleh kosong',
                'detail_country.*.required' => 'Country/Negara tidak boleh kosong',
                'detail_province.*.required' => 'Province/Provinsi tidak boleh kosong',
                'detail_zip_code.*.required' => 'Zip code/Kode pos tidak boleh kosong',
                'detail_telephone.*.required' => 'Telephone/Telepon tidak boleh kosong',
                'detail_fax.*.required' => 'Fax tidak boleh kosong',
            ];

            // Apply validation
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check and delete existing signature file if a new one is provided
            if ($request->hasFile('detail_signature_file')) {
                if ($existing_data->signature != null) {
                    // Delete old signature file
                    $oldSignaturePath = public_path('storage/uploads/signature/' . $existing_data->signature);
                    if (file_exists($oldSignaturePath)) {
                        unlink($oldSignaturePath);
                    }
                }
                // Handle new signature file upload
                $file_signature = $request->file('detail_signature_file');
                $slug_name = Str::slug($request->detail_company_name . ' signature', '-');
                $file_signature_name = $slug_name . '.' . $file_signature->getClientOriginalExtension();
                // Save signature file (example: move it to storage/uploads/signature)
                $file_signature->move(public_path('storage/uploads/signature'), $file_signature_name);
            } else {
                // Keep the old signature if no new one is uploaded
                $file_signature_name = $existing_data->signature;
            }

            // Check and delete existing stamp file if a new one is provided
            if ($request->hasFile('detail_stamp_file')) {
                if ($existing_data->stamp != null) {
                    // Delete old stamp file
                    $oldStampPath = public_path('storage/uploads/stamp/' . $existing_data->stamp);
                    if (file_exists($oldStampPath)) {
                        unlink($oldStampPath);
                    }
                }
                // Handle new stamp file upload
                $file_stamp = $request->file('detail_stamp_file');
                $slug_name = Str::slug($request->detail_company_name . ' stamp', '-');
                $file_stamp_name = $slug_name . '.' . $file_stamp->getClientOriginalExtension();
                // Save stamp file (example: move it to storage/uploads/stamp)
                $file_stamp->move(public_path('storage/uploads/stamp'), $file_stamp_name);
            } else {
                // Keep the old stamp if no new one is uploaded
                $file_stamp_name = $existing_data->stamp;
            }

            // Update company information
            $data_company_partner = [
                // 'user_id' => \Auth::user()->id,
                'name' => $request->detail_company_name,
                'group_name' => $request->detail_company_group_name,
                'type' => $request->detail_company_type,
                'established_year' => $request->detail_established_year,
                'total_employee' => $request->detail_total_employee,
                'liable_person_and_position' => $request->detail_liable_person_and_position,
                'owner_name' => $request->detail_owner_name,
                'board_of_directors' => $request->detail_board_of_directors,
                'major_shareholders' => $request->detail_major_shareholders,
                'business_classification' => $request->detail_business_classification,
                'business_classification_detail' => $request->detail_business_classification_detail,
                'other_business' => $request->detail_business_classification_other_detail,
                'website_address' => $request->detail_website_address,
                'system_management' => $request->detail_system_management,
                'contact_person' => $request->detail_contact_person,
                'communication_language' => $request->detail_communication_language,
                'email_address' => $request->detail_email_address,
                'signature' => $request->detail_signature_file != null ? $file_signature_name : $existing_data->signature,
                'stamp' => $request->detail_stamp_file != null ? $file_stamp_name : $existing_data->stamp,
            ];
            // updating data
            $existing_data->update($data_company_partner);

            if ($request->detail_address[0] != null) {
                $data_address = [];
                $list_address_data = [];
                $existing_address = CompanyAddress::where('company_id', $existing_data->id)->delete();

                for ($i=0; $i < count($request->detail_address); $i++) {
                    # code...
                    $list_address_data = [
                        'company_id' => $existing_data->id,
                        // 'company_id' => 'id',
                        'address' => $request->detail_address[$i],
                        'city' => $request->detail_city[$i],
                        'country' => $request->detail_country[$i],
                        'province' => $request->detail_province[$i],
                        'zip_code' => $request->detail_zip_code[$i],
                        'telephone' => $request->detail_telephone[$i],
                        'fax' => $request->detail_fax[$i],
                    ];
                    array_push($data_address, $list_address_data);
                }
                // $create_address = CompanyAddress::create($data_address);
                $create_address = CompanyAddress::insert($data_address);
            }

            if ($request->detail_bank_name[0] != null) {
                $data_bank = [];
                $list_bank_data = [];
                $existing_bank = CompanyBank::where('company_id', $existing_data->id)->delete();

                for ($i= 0; $i < count($request->detail_bank_name); $i++) {
                    $list_bank_data = [
                        'company_id' => $existing_data->id,
                        'name' => $request->detail_bank_name[$i],
                        'branch' => $request->detail_branch[$i],
                        'account_name' => $request->detail_account_name[$i],
                        'city_or_country' => $request->detail_city_or_country[$i],
                        'account_number' => $request->detail_account_number[$i],
                        'currency' => $request->detail_currency[$i],
                        'swift_code' => $request->detail_swift_code[$i],
                    ];
                    array_push($data_bank, $list_bank_data);
                }
                $create_bank = CompanyBank::insert($data_bank);
            }

            if ($request->detail_register_number_as_in_tax_invoice != null) {
                $existing_tax = CompanyTax::where('company_id', $existing_data->id)->delete();
                $data_tax = [
                    'company_id' => $existing_data->id,
                    'register_number_as_in_tax_invoice' => $request->detail_register_number_as_in_tax_invoice,
                    'trc_number' => $request->detail_trc_number,
                    'register_number_related_branch' => $request->detail_register_number_related_branch,
                    'valid_until' => $request->detail_valid_until,
                    'taxable_entrepreneur_number' => $request->detail_taxable_entrepreneur_number,
                    'tax_invoice_serial_number' => $request->detail_tax_invoice_serial_number,
                ];
                $create_tax = CompanyTax::insert($data_tax);
            }
            // Commit changes
            DB::commit();
            return FormatResponseJson::success('success', 'partner profile updated successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function storeAttachment(Request $request)
    {
        try {
            $rules = [
                'supporting_document_type.*' => 'required|string|max:255',
                'supporting_document_business_type.*' => 'required|string|max:255',
                'file_supporting_document.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10480', // Validate file uploads
            ];

            $validator = Validator::make($request->all(), $rules, [
                'supporting_document_type.*.required' => 'The supporting document type field is required',
                'supporting_document_business_type.*.required' => 'The supporting document business type field is required',
                'file_supporting_document.*.required' => 'The file supporting document field is required',
            ]);

            // throw validation error
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // check file uploaded
            if($request->file('file_supporting_document') != null) {
                for ($i = 0; $i < count($request->file_supporting_document); $i++) {
                    // setup name file
                    $explode_docx_type = explode('|', $request->supporting_document_type[$i]);
                    $result_docx_name = Str::slug($explode_docx_type[1], '_');
                    $file = $request->file('file_supporting_document')[$i];
                    $file_name = $result_docx_name.'_'.$request->supporting_document_business_type[$i].'.'.$file->getClientOriginalExtension();
                    $path = public_path('storage/uploads/'.$request->supporting_document_business_type[$i].'/');

                    // set name
                    $company_doc_type = $request->supporting_document_business_type[$i];
                    $document_type_name = $result_docx_name.'_'.$company_doc_type;

                    // checking all document
                    $existing_docx = CompanySupportingDocument::where([
                        'company_id' => $request->supporting_document_partner_id,
                        'document_type_name' => $document_type_name,
                    ])->first();

                    if ($existing_docx) {
                        return FormatResponseJson::error(null, 'Document already exists', 400); // Send error response
                    } else {
                        // insert data to database
                        $list_docx_attachment_data = [
                            'company_id'=> $request->supporting_document_partner_id,
                            'company_doc_type' => $company_doc_type,
                            'document' => 'storage/uploads/'.$request->supporting_document_business_type[$i].'/'.$file_name,
                            'document_type' => $file->getClientOriginalExtension(),
                            'document_type_name' => $document_type_name,
                        ];
                        // array_push($data_docx_attachment, $list_docx_attachment_data);
                        $create_supporting_document = CompanySupportingDocument::create($list_docx_attachment_data);
                        if ($create_supporting_document) {
                            $file->move($path, $file_name);
                        }
                    }
                }
            }
            DB::commit();
            return FormatResponseJson::success('success', 'documents uploaded successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function fetchAttachmentById(Request $request)
    {
        try {
            $existing_docx = CompanySupportingDocument::where([
                'company_id' => $request->supporting_document_partner_id,
            ])->first();
        } catch (ValidationException $e) {
            // DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function updateAttachmentById(Request $request)
    {
        try {

            DB::beginTransaction();
            $existing_partner = CompanyInformation::find($request->update_supporting_document_partner_id)->first('status');

            $existing_docx = CompanySupportingDocument::where([
                'id' => $request->update_supporting_document_id,
                'company_id' => $request->update_supporting_document_partner_id,
            ])->first();
            // set file name
            $company_docx_type = $existing_docx->company_doc_type;
            $docx_type = $existing_docx->document_type;
            $document_type_name = $existing_docx->document_type_name;

            $file = $request->file('update_file_supporting_document');
            $file_name = strtolower($document_type_name.'.'.$file->getClientOriginalExtension());
            $path = ('storage/uploads/'.$company_docx_type.'/');
            $result_file_name = strtolower('storage/uploads/'.$company_docx_type.'/'.$file_name);

            if($existing_partner->status === 'checking') {
                // Delete old attachment file
                $old_attachment_path = public_path($existing_docx->document);
                if (file_exists($old_attachment_path)) {
                    unlink($old_attachment_path);
                }
                // save new attachment
                $existing_docx->update([
                    'document' => $result_file_name,
                ]);
                $file->move($path, $file_name);
                // store new attachment file
            }

            DB::commit();
            return FormatResponseJson::success('success', 'documents updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function fetchProvinces(Request $request)
    {
        try {
            $provinces = Provinces::all();
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }
    public function fetchRegencies(Request $request)
    {
        try {
            $regencies = Regencies::where('province_id', $request->province_id)->get();
            return response()->json($regencies);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch regencies'], 500);
        }
    }

    // new function/method for validation and store data
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (auth()->user()->roles->pluck('name')[0] == 'user') {
                $this->checkIfUserAlreadyRegistered();
            }

            $validator = $this->validateRequest($request);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Handle multiple file attachments upload
            $attachmentFiles = $this->handleMultipleFileUploads($request);

            $companyData = $this->prepareCompanyData($request);
            $partner = CompanyInformation::create($companyData);
            $this->storeLiablePerson($request, $partner->id);
            $this->storeContact($request, $partner->id);
            $this->storeCompanyAddresses($request, $partner->id);
            $this->storeCompanyBanks($request, $partner->id);

            // store survey and product customer
            if ($request->company_type == 'customer' && $request->has('product_survey')) {
                $this->storeProductSurvey($request, $partner->id);
            }

            // Store file attachments
            if (!empty($attachmentFiles)) {
                $this->storeCompanyAttachments($request, $partner->id, $attachmentFiles);
            }

            // if ($request->company_type === 'customer') {
            //     $this->storeCustomerFinancialData($request, $partner->id);
            // }

            DB::commit();

            $fileCount = count($attachmentFiles);
            return FormatResponseJson::success(
                'success',
                "Partner profile created successfully with {$fileCount} file attachment(s)"
            );

        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    private function checkIfUserAlreadyRegistered()
    {
        $existing = CompanyInformation::where('user_id', auth()->id())->first();
        if ($existing) {
            throw new \Exception('Anda sudah mendaftar.', 400);
        }
    }

    private function validateRequest($request)
    {
        // ENGLISH VERSION
        $messagesEN = [
            // Company Info
            'company_type.required' => 'Company type is required.',
            'company_name.required' => 'Company name is required.',
            'established_year.integer' => 'Established year must be a number.',
            'total_employee.integer' => 'Total employee must be a number.',

            // Liable Person
            'liable_person.0.required' => 'Liable person is required.',
            'liable_position.0.required' => 'Liable position is required.',
            'nik.0.required' => 'NIK is required.',

            // Owner & Business
            'owner_name.required' => 'Owner name is required.',
            'business_classification.required' => 'Business classification is required.',
            'register_number_as_in_tax_invoice.required' => 'Tax invoice register number is required.',
            'email_address.email' => 'Email address format is invalid.',
            'credit_limit.integer' => 'Credit limit must be a number.',

            // Contact
            'contact_department.0.required' => 'Contact department is required.',
            'contact_position.0.required' => 'Contact position is required.',
            'contact_name.0.required' => 'Contact name is required.',
            'contact_email.0.required' => 'Contact email is required.',
            'contact_telephone.0.required' => 'Contact telephone is required.',

            // Address 1 (NPWP)
            'address.0.required' => 'Company address (according to NPWP) is required.',
            'city.0.required' => 'City is required.',
            'country.0.required' => 'Country is required.',
            'province.0.required' => 'Province is required.',
            'zip_code.0.required' => 'Postal code is required.',
            'zip_code.0.integer' => 'Postal code must be a number.',
            'telephone.0.required' => 'Telephone is required.',

            // Address 2 (Other)
            'address.1.required' => 'Company address (other) is required.',
            'city.1.required' => 'City is required.',
            'country.1.required' => 'Country is required.',
            'province.1.required' => 'Province is required.',
            'zip_code.1.required' => 'Postal code is required.',
            'zip_code.1.integer' => 'Postal code must be a number.',
            'telephone.1.required' => 'Telephone is required.',

            // Bank
            'bank_name.0.required' => 'Bank name is required.',
            'account_name.0.required' => 'Account name is required.',
            'account_number.0.required' => 'Account number is required.',

            // Survey
            'survey_pick_up.integer' => 'Pick up quantity must be a number.',
            'survey_truck.integer' => 'Truck quantity must be a number.',

            // File Attachments
            'input-multiple-file.array' => 'File attachments must be an array.',
            'input-multiple-file.*.file' => 'Each attachment must be a valid file.',
            'input-multiple-file.*.mimes' => 'File must be: jpg, jpeg, png, pdf, doc, docx, or webp.',
            'input-multiple-file.*.max' => 'File size must not exceed 5MB.',
        ];

        // INDONESIAN VERSION
        $messagesID = [
            // Company Info
            'company_type.required' => 'Tipe perusahaan wajib diisi.',
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'established_year.integer' => 'Tahun berdiri harus berupa angka.',
            'total_employee.integer' => 'Total karyawan harus berupa angka.',

            // Liable Person
            'liable_person.0.required' => 'Nama penanggung jawab wajib diisi.',
            'liable_position.0.required' => 'Posisi penanggung jawab wajib diisi.',
            'nik.0.required' => 'NIK wajib diisi.',

            // Owner & Business
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'business_classification.required' => 'Klasifikasi bisnis wajib diisi.',
            'register_number_as_in_tax_invoice.required' => 'Nomor registrasi sesuai faktur pajak wajib diisi.',
            'email_address.email' => 'Format alamat email tidak valid.',
            'credit_limit.integer' => 'Limit kredit harus berupa angka.',

            // Contact
            'contact_department.0.required' => 'Departemen kontak wajib diisi.',
            'contact_position.0.required' => 'Posisi kontak wajib diisi.',
            'contact_name.0.required' => 'Nama kontak wajib diisi.',
            'contact_email.0.required' => 'Email kontak wajib diisi.',
            'contact_telephone.0.required' => 'Telepon kontak wajib diisi.',

            // Address 1 (NPWP)
            'address.0.required' => 'Alamat perusahaan (sesuai NPWP) wajib diisi.',
            'city.0.required' => 'Kota wajib diisi.',
            'country.0.required' => 'Negara wajib diisi.',
            'province.0.required' => 'Provinsi wajib diisi.',
            'zip_code.0.required' => 'Kode pos wajib diisi.',
            'zip_code.0.integer' => 'Kode pos harus berupa angka.',
            'telephone.0.required' => 'Telepon wajib diisi.',

            // Address 2 (Other)
            'address.1.required' => 'Alamat perusahaan (lainnya) wajib diisi.',
            'city.1.required' => 'Kota wajib diisi.',
            'country.1.required' => 'Negara wajib diisi.',
            'province.1.required' => 'Provinsi wajib diisi.',
            'zip_code.1.required' => 'Kode pos wajib diisi.',
            'zip_code.1.integer' => 'Kode pos harus berupa angka.',
            'telephone.1.required' => 'Telepon wajib diisi.',

            // Bank
            'bank_name.0.required' => 'Nama bank wajib diisi.',
            'account_name.0.required' => 'Nama akun wajib diisi.',
            'account_number.0.required' => 'Nomor rekening wajib diisi.',

            // Survey
            'survey_pick_up.integer' => 'Jumlah pick up harus berupa angka.',
            'survey_truck.integer' => 'Jumlah truck harus berupa angka.',

            // File Attachments
            'input-multiple-file.array' => 'Lampiran file harus berupa array.',
            'input-multiple-file.*.file' => 'Setiap lampiran harus berupa file yang valid.',
            'input-multiple-file.*.mimes' => 'File harus berformat: jpg, jpeg, png, pdf, doc, docx, atau webp.',
            'input-multiple-file.*.max' => 'Ukuran file tidak boleh melebihi 5MB.',
        ];

        // USAGE - Gunakan salah satu sesuai bahasa yang diinginkan
        $messages = app()->getLocale() == 'id' ? $messagesID : $messagesEN;
        $validator = Validator::make($request->all(), [
            'company_type' => 'required|string',
            'company_name' => 'required|string',
            'company_group_name' => 'nullable|string',
            'established_year' => 'nullable|integer',
            'total_employee' => 'nullable|integer',
            'liable_person.0' => 'required|string',
            'liable_position.0' => 'required|string',
            'other_position.0' => 'nullable|string',
            'nik.0' => 'required|string',
            'owner_name' => 'nullable|string',
            'business_classification' => 'required|string',
            'business_classification_detail' => 'nullable|string',
            'register_number_as_in_tax_invoice' => 'required|string',
            'website_address' => 'nullable|string',
            'system_management' => 'nullable|string',
            'email_address' => 'nullable|email',
            'credit_limit' => 'nullable|integer',
            'term_of_payment' => 'nullable|string',

            // Validasi contact
            'contact_department.0' => 'required|string',
            'contact_position.0' => 'required|string',
            'contact_name.0' => 'required|string',
            'contact_email.0' => 'required|string',
            'contact_telephone.0' => 'required|string',

            // Validasi address
            'address.0' => 'required|string',
            'city.0' => 'required|string',
            'country.0' => 'required|string',
            'province.0' => 'required|string',
            'zip_code.0' => 'required|integer',
            'telephone.0' => 'required|string',
            'fax.0' => 'nullable|string',
            'address.1' => 'required|string',
            'city.1' => 'required|string',
            'country.1' => 'required|string',
            'province.1' => 'required|string',
            'zip_code.1' => 'required|integer',
            'telephone.1' => 'required|string',
            'fax.1' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',

            // Validasi bank
            'bank_name.0' => 'required|string',
            'account_name.0' => 'required|string',
            'account_number.0' => 'required|string',

            // survey for customer
            'survey_ownership_status' => 'nullable|string',
            'survey_pick_up' => 'nullable|integer',
            'survey_truck' => 'nullable|integer',

            'product_survey.*' => 'nullable|string',
            'merk_survey.*' => 'nullable|string',
            'distributor_survey.*' => 'nullable|string',

            // Validasi file attachments
            'input-multiple-file' => 'nullable|array',
            'input-multiple-file.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,webp|max:5120'
        ], $messages);

        $locale = app()->getLocale();

        if ($request->has('liable_position.0') && $request->liable_position[0] === 'Other' && empty($request->other_position[0])) {
            $validator->after(function ($validator) use ($locale) {
                $errorMessage = $locale === 'id'
            ? 'Posisi penanggung jawab wajib diisi ketika memilih "Lainnya".'
            : 'Liable position is required when selecting "Other".';
                $validator->errors()->add('other_position.0', $errorMessage);
            });
        }

        if ($request->business_classification === 'Other' && empty($request->business_classification_other_detail)) {
            $validator->after(function ($validator) use ($locale) {
                $errorMessage = $locale === 'id'
            ? 'Detail klasifikasi bisnis wajib diisi ketika memilih "Lainnya".'
            : 'Business classification detail is required when selecting "Other".';
                $validator->errors()->add('business_classification_other_detail', $errorMessage);
            });
        }

        if ($request->term_of_payment == 'Other' && empty($request->other_term_of_payment)) {
            // dd($request->other_term_of_payment);
            $validator->after(function ($validator) use ($locale) {
                $errorMessage = $locale === 'id'
            ? 'Detail term of payment wajib diisi ketika memilih "Lainnya".'
            : 'Term of payment detail is required when selecting "Other".';
                $validator->errors()->add('other_term_of_payment', $errorMessage);
            });
        }

        return $validator;
    }

    /**
     * Handle multiple file attachments upload
     */
    private function handleMultipleFileUploads($request)
    {
        $uploadedFiles = [];

        if ($request->hasFile('input-multiple-file')) {
            $files = $request->file('input-multiple-file');

            foreach ($files as $index => $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();

                // Generate unique filename
                $filename = time() . '_' . $index . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

                // Move file to public storage
                $file->move(public_path('storage/uploads/partner_documents'), $filename);

                $uploadedFiles[] = [
                    'original_name' => $originalName,
                    'stored_name' => $filename,
                    'file_path' => 'uploads/partner_documents/' . $filename,
                    'file_size' => $fileSize,
                    'file_type' => $extension,
                    'sort_order' => $index
                ];
            }
        }

        return $uploadedFiles;
    }

    private function prepareCompanyData($request)
    {
        return [
            'user_id' => auth()->id(),
            'name' => $request->company_name,
            'group_name' => $request->company_group_name,
            'type' => $request->company_type,
            'established_year' => $request->established_year,
            'total_employee' => $request->total_employee,
            // 'liable_person_and_position' => $request->liable_person_and_position,
            // 'liable_position' => $request->liable_person_and_position,
            'owner_name' => $request->owner_name,
            'npwp' => $request->register_number_as_in_tax_invoice,
            // 'board_of_directors' => $request->board_of_directors,
            // 'major_shareholders' => $request->major_shareholders,
            'business_classification' => $request->business_classification,
            'business_classification_detail' => $request->business_classification_detail,
            'other_business' => $request->business_classification_other_detail,
            'website_address' => $request->website_address,
            'system_management' => $request->system_management,
            // 'contact_person' => $request->contact_person,
            // 'communication_language' => $request->communication_language,
            'email_address' => $request->email_address,
            'term_of_payment' => $request->term_of_payment,
            'credit_limit' => $request->credit_limit,
            // 'signature' => $files['signature_file'],
            // 'stamp' => $files['stamp_file'],
            'status' => auth()->user()->roles->pluck('name')[0] == 'user' ? 'checking' : 'approved',
            'location_id' => auth()->user()->roles->pluck('name')[0] == 'user' ? null : auth()->user()->office_id,
            'department_id' => auth()->user()->roles->pluck('name')[0] == 'user' ? null : auth()->user()->department_id,
        ];
    }

    private function storeLiablePerson($request, $companyId)
    {
        $liablePersons = [];
        foreach ($request->liable_person ?? [] as $i => $name) {
            if (!empty($name)) {
                $request->liable_position[$i] == 'Other' && !empty($request->liable_position_other[$i]) ? $namePosition = $request->liable_position_other[$i] : $namePosition = $request->liable_position[$i];
                $liablePersons[] = [
                    'company_id' => $companyId,
                    'name' => $name,
                    'nik' => $request->nik[$i] ?? null,
                    'position' => $namePosition ?? null
                ];
            }
        }
        if (!empty($liablePersons)) {
            CompanyLiablePerson::insertWithLog($liablePersons);
        }
    }

    private function storeContact($request, $companyId)
    {
        $contacts = [];
        foreach ($request->contact_department ?? [] as $i => $contact) {
            if(!empty($contact)) {
                $contacts[] = [
                    'company_informations_id' => $companyId,
                    'name' => $request->contact_name[$i],
                    'department' => $contact,
                    'position' => $request->contact_position[$i],
                    'email' => $request->contact_email[$i],
                    'telephone' => $request->contact_telephone[$i],
                ];
            }
        }
        if(!empty($contacts)) {
            CompanyContact::insertWithLog($contacts);
        }
    }

    private function storeCompanyAddresses($request, $companyId)
    {
        $addresses = [];
        foreach ($request->address as $i => $addr) {
            if (!empty($addr)) {
                $addresses[] = [
                    'company_id' => $companyId,
                    'address' => $addr,
                    'city' => $request->city[$i],
                    'country' => $request->country[$i],
                    'province' => $request->province[$i],
                    'zip_code' => $request->zip_code[$i],
                    'telephone' => $request->telephone[$i],
                    'fax' => $request->fax[$i],
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($addresses)) {
            CompanyAddress::insertWithLog($addresses);

            // Ambil id alamat yang baru saja ditambahkan
            $ids = CompanyAddress::where('company_id', $companyId)
                ->latest('id')
                ->take(count($addresses))
                ->pluck('id');

            foreach ($ids as $id) {
                GeocodeAddressJob::dispatch($id);
            }
        }
    }

    private function storeCompanyBanks($request, $companyId)
    {
        $banks = [];
        foreach ($request->bank_name ?? [] as $i => $name) {
            if (!empty($name)) {
                $banks[] = [
                    'company_id' => $companyId,
                    'name' => $name,
                    // 'branch' => $request->branch[$i] ?? null,
                    'account_name' => $request->account_name[$i],
                    // 'city_or_country' => $request->city_or_country[$i] ?? null,
                    'account_number' => $request->account_number[$i],
                    // 'currency' => $request->currency[$i] ?? null,
                    // 'swift_code' => $request->swift_code[$i] ?? null,
                ];
            }
        }

        if (!empty($banks)) {
            CompanyBank::insertWithLog($banks);
        }
    }

    private function storeProductSurvey($request, $companyId)
    {
        SalesSurvey::create([
            'company_id' => $companyId,
            'ownership_status' => $request->survey_ownership_status,
            'rental_year' => $request->rental_year ?? null,
            'pick_up' => $request->survey_pick_up,
            'truck' => $request->survey_truck,
        ]);
        $surveys = [];
        foreach ($request->product_survey ?? [] as $i => $product) {
            if (!empty($product)) {
                $surveys[] = [
                    'company_id' => $companyId,
                    'name' => $product,
                    'merk' => $request->merk_survey[$i] ?? null,
                    'distributor' => $request->distributor_survey[$i] ?? null,
                ];
            }
        }

        if (!empty($surveys)) {
            ProductCustomer::insertWithLog($surveys);
        }
    }

    /**
     * Store company file attachments
     */
    private function storeCompanyAttachments($request, $companyId, $attachmentFiles)
    {
        $attachments = [];

        foreach ($attachmentFiles as $file) {
            $attachments[] = [
                'company_id' => $companyId,
                'filename' => $file['original_name'],
                // 'stored_filename' => $file['stored_name'],
                'filepath' => $file['file_path'],
                'filesize' => $file['file_size'],
                'filetype' => $file['file_type'],
                'sort_order' => $file['sort_order'],
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }

        if (!empty($attachments)) {
            // Pastikan ada method insertWithLog atau gunakan insert biasa
            if (method_exists(CompanyAttachment::class, 'insertWithLog')) {
                CompanyAttachment::insertWithLog($attachments);
            } else {
                CompanyAttachment::insert($attachments);
            }
        }
    }
}
