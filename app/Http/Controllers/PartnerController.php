<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInformation;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\CompanyContact;
use App\Models\UserValueIncomeStatement;
use App\Models\UserBalanceSheet;
use App\Models\UserFinancialRatio;
use App\Models\MasterIncomeStatement;
use App\Models\MasterBalanceSheet;
use App\Models\CompanySupportingDocument;
use App\Models\MasterActivityLog;
use App\Models\ActivityLogs;
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
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware(['auth', 'check.company.info']);
    }
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

            $files = $this->handleFileUploads($request);

            $companyData = $this->prepareCompanyData($request, $files);
            $partner = CompanyInformation::create($companyData);

            $this->storeContact($request, $partner->id);
            $this->storeCompanyAddresses($request, $partner->id);
            $this->storeCompanyBanks($request, $partner->id);
            $this->storeCompanyTax($request, $partner->id);

            if ($request->company_type === 'customer') {
                $this->storeCustomerFinancialData($request, $partner->id);
            }

            DB::commit();
            return FormatResponseJson::success('success', 'partner profile created successfully');
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
            // return FormatResponseJson::error(null, 'Anda sudah mendaftar.', 400);
        }
    }
    private function validateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'company_group_name' => 'required|string',
            'company_type' => 'required|string',
            'established_year' => 'required|integer',
            'total_employee' => 'required|string',
            'liable_person_and_position' => 'required|string',
            'owner_name' => 'required|string',
            'board_of_directors' => 'required|string',
            'major_shareholders' => 'required|string',
            'business_classification' => 'required|string',
            'business_classification_detail' => 'required|string',
            'website_address' => 'required|string',
            'system_management' => 'required|string',
            'contact_person' => 'required|string',
            'communication_language' => 'required|string',
            'email_address' => 'required|email',
            // 'stamp_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
            // 'signature_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
            'contact_department.0' => 'required|string',
            'contact_position.0' => 'required|string',
            'contact_name.0' => 'required|string',
            'contact_email.0' => 'required|string',
            'contact_telephone.0' => 'required|string',
            'address.0' => 'required|string',
            'city.0' => 'required|string',
            'country.0' => 'required|string',
            'province.0' => 'required|string',
            'zip_code.0' => 'required|integer',
            'telephone.0' => 'required|string',
            'fax.0' => 'required|string',
        ], [
            'company_name.required' => 'The company name field is required.',
            'company_group_name.required' => 'The company group name field is required.',
            'company_type.required' => 'The company type field is required.',
            'established_year.required' => 'The established year field is required.',
            'total_employee.required' => 'The total employee field is required.',
            'liable_person_and_position.required' => 'The liable person and position field is required.',
            'owner_name.required' => 'The owner name field is required.',
            'board_of_directors.required' => 'The board of directors field is required.',
            'major_shareholders.required' => 'The major shareholders field is required.',
            'business_classification.required' => 'The business classification field is required.',
            'business_classification_detail.required' => 'The business classification detail field is required.',
            'website_address.required' => 'The website address field is required.',
            'system_management.required' => 'The system management field is required.',
            'contact_person.required' => 'The contact person field is required.',
            'communication_language.required' => 'The communication language field is required.',
            'email_address.required' => 'The email address field is required.',
            'stamp_file.required' => 'The stamp file field is required.',
            'signature_file.required' => 'The signature file field is required.',
            'address.0.required' => 'The address field is required',
            'city.0.required' => 'The city field is required.',
            'country.0.required' => 'The country field is required.',
            'province.0.required' => 'The province field is required.',
            'zip_code.0.required' => 'The zip_code field is required.',
            'fax.0.required' => 'The fax field is required.',
            'zip_code.0.integer' => 'The zip_code must be an integer.',
            'telephone.0.required' => 'The telephone field is required.',
        ]);

        if ($request->business_classification === 'Other' && empty($request->business_classification_other_detail)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('business_classification_other_detail', 'Business classification/Jenis usaha tidak boleh kosong!');
            });
        }

        return $validator;
    }
    private function handleFileUploads($request)
    {
        $files = ['signature_file' => null, 'stamp_file' => null];

        if ($request->hasFile('signature_file')) {
            $signature = $request->file('signature_file');
            $name = Str::slug($request->company_name.' signature').'.'.$signature->getClientOriginalExtension();
            $signature->move(public_path('storage/uploads/signature'), $name);
            $files['signature_file'] = $name;
        }

        if ($request->hasFile('stamp_file')) {
            $stamp = $request->file('stamp_file');
            $name = Str::slug($request->company_name.' stamp').'.'.$stamp->getClientOriginalExtension();
            $stamp->move(public_path('storage/uploads/stamp'), $name);
            $files['stamp_file'] = $name;
        }

        return $files;
    }
    private function prepareCompanyData($request, $files)
    {
        return [
            'user_id' => auth()->id(),
            'name' => $request->company_name,
            'group_name' => $request->company_group_name,
            'type' => $request->company_type,
            'established_year' => $request->established_year,
            'total_employee' => $request->total_employee,
            'liable_person_and_position' => $request->liable_person_and_position,
            'liable_position' => $request->liable_person_and_position,
            'owner_name' => $request->owner_name,
            'board_of_directors' => $request->board_of_directors,
            'major_shareholders' => $request->major_shareholders,
            'business_classification' => $request->business_classification,
            'business_classification_detail' => $request->business_classification_detail,
            'other_business' => $request->business_classification_other_detail,
            'website_address' => $request->website_address,
            'system_management' => $request->system_management,
            'contact_person' => $request->contact_person,
            'communication_language' => $request->communication_language,
            'email_address' => $request->email_address,
            'signature' => $files['signature_file'],
            'stamp' => $files['stamp_file'],
            'status' => auth()->user()->roles->pluck('name')[0] == 'user' ? 'checking' : 'approved',
            'location_id' => auth()->user()->roles->pluck('name')[0] == 'user' ? null : auth()->user()->office_id,
            'department_id' => auth()->user()->roles->pluck('name')[0] == 'user' ? null : auth()->user()->office_id,
        ];
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
            $contact = CompanyContact::insertWithLog($contacts);
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
                    'created_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($addresses)) {
            CompanyAddress::insertWithLog($addresses);
            // Ambil id alamat yang baru saja ditambahkan
            // Misal pakai created_at untuk mem-filter
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
                    'branch' => $request->branch[$i],
                    'account_name' => $request->account_name[$i],
                    'city_or_country' => $request->city_or_country[$i],
                    'account_number' => $request->account_number[$i],
                    'currency' => $request->currency[$i],
                    'swift_code' => $request->swift_code[$i],
                ];
            }
        }

        if (!empty($banks)) {
            CompanyBank::insertWithLog($banks);
        }
    }
    private function storeCompanyTax($request, $companyId)
    {
        if ($request->register_number_as_in_tax_invoice !== null) {
            CompanyTax::create([
                'company_id' => $companyId,
                'register_number_as_in_tax_invoice' => $request->register_number_as_in_tax_invoice,
                'trc_number' => $request->trc_number,
                'register_number_related_branch' => $request->register_number_related_branch,
                'valid_until' => $request->valid_until,
                'taxable_entrepreneur_number' => $request->taxable_entrepreneur_number,
                'tax_invoice_serial_number' => $request->tax_invoice_serial_number,
            ]);
        }
    }
    private function storeCustomerFinancialData($request, $companyId)
    {
        $aYearAgo = date('Y', strtotime('-1 year'));
        $twoYearAgo = date('Y', strtotime('-2 year'));
        $years = [$aYearAgo, $twoYearAgo];

        $incomeStatements = MasterIncomeStatement::orderBy('id')->get();
        $balanceSheets = MasterBalanceSheet::orderBy('id')->get();

        $incomeData = [];
        foreach ($incomeStatements as $item) {
            foreach ($years as $year) {
                $key = Str::slug($item->name, '_') . '_' . $year;
                $value = str_replace(',', '', $request->input($key, 0));
                $incomeData[] = [
                    'company_information_id' => $companyId,
                    'master_income_statement_id' => $item->id,
                    'value' => $value,
                    'year' => $year,
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }
        }
        if (!empty($incomeData)) {
            UserValueIncomeStatement::insertWithLog($incomeData);
        }

        $balanceData = [];
        foreach ($balanceSheets as $item) {
            foreach ($years as $year) {
                $key = Str::slug($item->name, '_') . '_' . $year;
                $value = str_replace(',', '', $request->input($key, 0));
                $balanceData[] = [
                    'company_information_id' => $companyId,
                    'master_balance_sheets_id' => $item->id,
                    'value' => $value,
                    'year' => $year,
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }
        }
        if (!empty($balanceData)) {
            UserBalanceSheet::insertWithLog($balanceData);
        }

        $ratios = ['wc_ratio', 'cf_coverage', 'tie_ratio', 'debt_asset', 'account_receivable_turn_over', 'net_profit_margin'];
        $ratioData = [];
        foreach ($ratios as $ratio) {
            foreach ($years as $year) {
                $key = $ratio . '_' . $year;
                $value = str_replace(',', '', $request->input($key, 0));
                if ($value !== null && $value !== '') {
                    $ratioData[] = [
                        'company_information_id' => $companyId,
                        'value' => $value,
                        'year' => $year,
                        'created_at' => now(),
                        'updated_at' => null,
                    ];
                }
            }
        }

        if (!empty($ratioData)) {
            UserFinancialRatio::insertWithLog($ratioData);
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = CompanyInformation::where('id', $request->detail_id)->first();
            
            // Dynamic validation rules
            $rules = [
                'detail_company_name' => 'required|string',
                'detail_company_group_name' => 'required|string',
                'detail_company_type' => 'required|string',
                'detail_established_year' => 'required|string',
                'detail_total_employee' => 'required|string',
                'detail_liable_person_and_position' => 'required|string',
                'detail_owner_name' => 'required|string',
                'detail_board_of_directors' => 'required|string',
                'detail_major_shareholders' => 'required|string',
                'detail_business_classification' => 'required|string',
                'detail_business_classification_detail' => 'required|string',
                'detail_website_address' => 'required|string',
                'detail_system_management' => 'required|string',
                'detail_contact_person' => 'required|string',
                'detail_communication_language' => 'required|string',
                'detail_email_address' => 'required|string|email',
                'detail_address.*' => 'required|string',
                'detail_city.*' => 'required|string',
                'detail_country.*' => 'required|string',
                'detail_province.*' => 'required|string',
                'detail_zip_code.*' => 'required|integer',
                'detail_telephone.*' => 'required|string',
                'detail_fax.*' => 'required|string',
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
}
