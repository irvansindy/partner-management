<?php

namespace App\Http\Controllers;

use App\Models\FormLink;
use App\Models\CompanyInformation;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyContact;
use App\Models\CompanyLiablePerson;
use App\Models\SalesSurvey;
use App\Models\ProductCustomer;
use App\Services\ApprovalService; // ✅ ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\FormatResponseJson;
use App\Rules\PublicHttpsUrl;
use App\Support\CountryCatalog;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Models\PostalCode;
use Illuminate\Validation\ValidationException;
use App\Services\CompanyAttachmentService;
use App\Support\SecurityLogSanitizer;

class PublicFormController extends Controller
{
    protected $approvalService; // ✅ ADD THIS
    protected $attachmentService;

    // ✅ ADD CONSTRUCTOR
    public function __construct(ApprovalService $approvalService, CompanyAttachmentService $attachmentService = null)
    {
        $this->approvalService = $approvalService;
        $this->attachmentService = $attachmentService ?: app(CompanyAttachmentService::class);
    }

    public function show($token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();

        if (!$formLink->canAcceptSubmission()) {
            return view('public.form_closed', [
                'formLink' => $formLink,
                'message' => $this->getClosedMessage($formLink)
            ]);
        }

        return view('public.form_index', [
            'formLink' => $formLink,
            'countries' => CountryCatalog::all(),
        ]);
    }

    public function submit(Request $request, $token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();

        if (!$formLink->canAcceptSubmission()) {
            return FormatResponseJson::error(false, 'Form sudah tidak dapat menerima submission.', 503);
        }

        $validated = $this->validateSubmission($request, $formLink->form_type);
        $uploads = $request->file('input-multiple-file', []);
        $attachmentMetadata = [];
        foreach ($uploads as $file) {
            $attachmentMetadata[] = $this->attachmentService->validate($file);
        }

        $storedAttachments = [];
        DB::beginTransaction();
        try {
            $formLink = FormLink::whereKey($formLink->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$formLink->canAcceptSubmission()) {
                DB::rollBack();

                return FormatResponseJson::error(false, 'Form sudah tidak dapat menerima submission.', 503);
            }

            // 1. Simpan Company Information
            $company = CompanyInformation::create([
                'form_link_id' => $formLink->id,
                'user_id' => 0,
                'name' => $validated['company_name'],
                'group_name' => $validated['company_group_name'] ?? null,
                'type' => $validated['company_type'],
                'established_year' => $validated['established_year'] ?? null,
                'total_employee' => $validated['total_employee'] ?? null,
                'business_classification' => $validated['business_classification'],
                'business_classification_detail' => $validated['business_classification_detail'] ?? null,
                'website_address' => $validated['website_address'] ?? null,
                'system_management' => $validated['system_management'] ?? null,
                'email_address' => $validated['email_address'] ?? null,
                'npwp' => $validated['register_number_as_in_tax_invoice'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'term_of_payment' => $validated['term_of_payment'] ?? null,
                'status' => 'checking',
            ]);

            Log::info('✅ Step 1 - Company created: ' . $company->id);  // ← TAMBAH INI

            // 2. Simpan Liable Persons
            if (!empty($validated['liable_person'])) {
                foreach ($validated['liable_person'] as $index => $person) {
                    CompanyLiablePerson::create([
                        'company_id' => $company->id,
                        'name' => $person,
                        'nik' => $validated['nik'][$index] ?? null,
                        'position' => $validated['liable_position'][$index] ?? null,
                    ]);
                }
            }
            Log::info('✅ Step 2 - Liable persons saved');  // ← TAMBAH INI
            // 3. Simpan Contacts
            if (!empty($validated['contact_name'])) {
                foreach ($validated['contact_name'] as $index => $name) {
                    CompanyContact::create([
                        'company_informations_id' => $company->id,
                        'name' => $name,
                        'department' => $validated['contact_department'][$index] ?? null,
                        'position' => $validated['contact_position'][$index] ?? null,
                        'email' => $validated['contact_email'][$index] ?? null,
                        'telephone' => $validated['contact_telephone'][$index] ?? null,
                    ]);
                }
            }
            Log::info('✅ Step 3 - Contacts saved');  // ← TAMBAH INI
            // 4. Simpan Addresses
            if (!empty($validated['address'])) {
                foreach ($validated['address'] as $index => $address) {
                    $addressData = [
                        'company_id' => $company->id,
                        'address_role' => $validated['address_role'][$index] ?? null,
                        'address' => $address,
                        'country_code' => $validated['country_code'][$index] ?? null,
                        'country' => $validated['country'][$index] ?? null,
                        'province' => $validated['province'][$index] ?? null,
                        'city' => $validated['city'][$index] ?? null,
                        'zip_code' => $validated['zip_code'][$index] ?? null,
                        'telephone' => $validated['telephone'][$index] ?? null,
                        'fax' => $validated['fax'][$index] ?? null,
                    ];

                    $addressData['latitude'] = $validated['latitude'][$index] ?? null;
                    $addressData['longitude'] = $validated['longitude'][$index] ?? null;

                    CompanyAddress::create($addressData);
                }
            }

            Log::info('✅ Step 4 - Addresses saved');  // ← TAMBAH INI

            // 5. Simpan Banks
            if (!empty($validated['bank_name'])) {
                foreach ($validated['bank_name'] as $index => $bankName) {
                    CompanyBank::create([
                        'company_id' => $company->id,
                        'name' => $bankName,
                        'account_name' => $validated['account_name'][$index] ?? null,
                        'account_number' => $validated['account_number'][$index] ?? null,
                    ]);
                }
            }

            // 6. Simpan Survey Data (Conditional)
            $hasSurveyData = !empty($request->input('survey_ownership_status')) ||
                            !empty($request->input('survey_pick_up')) ||
                            !empty($request->input('survey_truck')) ||
                            !empty($request->input('product_survey'));

            if ($validated['company_type'] === 'customer' && $hasSurveyData) {
                // Simpan SalesSurvey
                SalesSurvey::create([
                    'company_id' => $company->id,
                    'ownership_status' => $validated['survey_ownership_status'] ?? null,
                    'rental_year' => $validated['rental_year'] ?? null,
                    'pick_up' => $validated['survey_pick_up'] ?? null,
                    'truck' => $validated['survey_truck'] ?? null,
                ]);

                // Simpan Product Customers
                if (!empty($validated['product_survey'])) {
                    foreach ($validated['product_survey'] as $index => $product) {
                        if (!empty($product)) {
                            ProductCustomer::create([
                                'company_id' => $company->id,
                                'name' => $product,
                                'merk' => $validated['merk_survey'][$index] ?? null,
                                'distributor' => $validated['distributor_survey'][$index] ?? null,
                            ]);
                        }
                    }
                }

                Log::info('Survey data saved for company: ' . $company->id);
            } else {
                Log::info('Survey data skipped - Type: ' . $validated['company_type'] . ', Has data: ' . ($hasSurveyData ? 'Yes' : 'No'));
            }

            // 7. Handle File Uploads
            foreach ($uploads as $index => $file) {
                $storedAttachments[] = $this->attachmentService->store(
                    $company,
                    $file,
                    $attachmentMetadata[$index],
                    $index + 1
                );
            }

            // 8. Increment submission count
            $formLink->incrementSubmissionCount();

            // ✅ ============================================
            // 9. CREATE APPROVAL PROCESS
            // ✅ ============================================
            try {
                $approvalProcess = $this->approvalService->createApprovalFromFormLink($company);

                if ($approvalProcess) {
                    Log::info("✅ Approval process created successfully for company ID: {$company->id}, Approval ID: {$approvalProcess->id}");
                } else {
                    Log::warning("⚠️ No approval template found for company ID: {$company->id}, Office ID: {$formLink->office_id}, Department ID: {$formLink->department_id}");
                }
            } catch (\Exception $e) {
                // Log error but don't fail the entire transaction
                Log::error("❌ Failed to create approval for company ID: {$company->id}. Error: " . $e->getMessage());
            }

            DB::commit();

            return FormatResponseJson::success(true, 'Data berhasil dikirim! Terima kasih.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->attachmentService->deleteStored($storedAttachments);
            $requestId = (string) Str::uuid();
            $request->attributes->set('request_id', $requestId);
            Log::error(
                'Public form submission failed',
                app(SecurityLogSanitizer::class)->exceptionContext($e, $request)
            );

            return FormatResponseJson::error(
                false,
                'Gagal memproses formulir. Silakan coba lagi. | Failed to process the form. Please try again.',
                500,
                $requestId
            );
        }
    }

    public function success()
    {
        return view('public.form_success');
    }

    public function provinces() { return response()->json(['data' => Provinces::orderBy('name')->get(['id','name'])]); }
    public function regencies($province) { return response()->json(['data' => Regencies::where('province_id', $province)->orderBy('name')->get(['id','name'])]); }
    public function postalCodes($regency) { return response()->json(['data' => PostalCode::where('regency_id', $regency)->orderBy('postal_code')->get(['id','district','village','postal_code'])]); }

    private function validateSubmission(Request $request, $formType)
    {
        if ($request->filled('website_address')) {
            $request->merge([
                'website_address' => PublicHttpsUrl::normalize($request->input('website_address')),
            ]);
        }

        $rules = [
            // Master Information
            'company_type' => 'required|in:vendor,customer',
            'company_name' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'company_group_name' => 'nullable|string|max:255|regex:/^[\p{L} ]+$/u',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_employee' => 'nullable|integer|min:1|max:1000000',

            // Liable Persons
            'liable_person.*' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'liable_position.*' => 'required|string|max:255',
            'nik.*' => 'required|digits_between:1,16',

            // Business
            'business_classification' => 'required|string|max:255',
            'business_classification_detail' => 'nullable|string|max:1000',
            'register_number_as_in_tax_invoice' => 'required|string|max:255',
            'website_address' => ['nullable', 'string', 'max:255', new PublicHttpsUrl()],
            'system_management' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'term_of_payment' => 'nullable|string|max:50',

            // Contacts
            'contact_department.*' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'contact_position.*' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'contact_name.*' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'contact_email.*' => 'required|email|max:255',
            'contact_telephone.*' => 'required|regex:/^\+[1-9][0-9]{2,16}$/',

            // Addresses
            'address_role' => 'required|array|min:1',
            'address_role.*' => 'required|in:legal,operational,branch',
            'address.*' => 'required|string|max:1000',
            'country_code' => 'required|array|min:1',
            'country_code.*' => 'required|string|size:2',
            'country.*' => 'required|string|max:100',
            'province.*' => 'required|string|max:100',
            'city.*' => 'required|string|max:100',
            'zip_code.*' => 'required|string|max:20',
            'telephone.*' => 'required|regex:/^\+[1-9][0-9]{2,16}$/',
            'fax.*' => 'required|regex:/^\+[1-9][0-9]{2,16}$/',
            'latitude.*' => 'nullable|numeric|between:-90,90',
            'longitude.*' => 'nullable|numeric|between:-180,180',

            // Banks
            'bank_name.*' => 'required|string|max:255|regex:/^[\p{L}\p{N} .-]+$/u',
            'account_name.*' => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'account_number.*' => 'required|digits_between:1,16',

            // Files
            'input-multiple-file' => 'nullable|array|max:10',
            'input-multiple-file.*' => 'nullable|file|max:5120',
        ];

        if ($formType === 'customer') {
            $rules = array_merge($rules, [
                'survey_ownership_status' => 'nullable|in:own,rented',
                'rental_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'survey_pick_up' => 'nullable|string|max:255',
                'survey_truck' => 'nullable|string|max:255',
                'product_survey.*' => 'nullable|string|max:255',
                'merk_survey.*' => 'nullable|string|max:255',
                'distributor_survey.*' => 'nullable|string|max:255',
            ]);
        }

        $messages = [
            'company_type.required' => 'Jenis perusahaan wajib diisi. | Company type is required.',
            'business_classification.required' => 'Klasifikasi bisnis wajib diisi. | Business classification is required.',
            'email_address.email' => 'Format alamat email tidak valid. | Email address format is invalid.',
            'contact_email.*.email' => 'Format email kontak tidak valid. | Contact email format is invalid.',
            'contact_telephone.*.regex' => 'Format atau panjang nomor telepon kontak tidak valid. | Contact telephone format or length is invalid.',
            'telephone.*.regex' => 'Format atau panjang nomor telepon tidak valid. | Telephone format or length is invalid.',
            'fax.*.regex' => 'Format atau panjang nomor fax tidak valid. | Fax format or length is invalid.',
            'company_type.in' => 'Jenis perusahaan harus vendor atau customer. | Company type must be vendor or customer.',
            'company_name.required' => 'Nama perusahaan wajib diisi. | Company name is required.',
            'company_name.string' => 'Nama perusahaan harus berupa teks. | Company name must be a string.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter. | Company name may not exceed 255 characters.',
            'company_name.regex' => 'Nama perusahaan hanya boleh berisi huruf dan spasi. | Company name may only contain letters and spaces.',
            'company_group_name.regex' => 'Nama grup perusahaan hanya boleh berisi huruf dan spasi. | Company group name may only contain letters and spaces.',
            'established_year.integer' => 'Tahun berdiri harus berupa empat digit angka. | Established year must be a four-digit number.',
            'established_year.min' => 'Tahun berdiri minimal 1900. | Established year must be at least 1900.',
            'established_year.max' => 'Tahun berdiri tidak boleh melebihi tahun berjalan. | Established year may not exceed the current year.',
            'liable_person.*.regex' => 'Nama penanggung jawab hanya boleh berisi huruf dan spasi. | Liable person name may only contain letters and spaces.',
            'contact_department.*.regex' => 'Departemen hanya boleh berisi huruf dan spasi. | Department may only contain letters and spaces.',
            'contact_position.*.regex' => 'Jabatan hanya boleh berisi huruf dan spasi. | Position may only contain letters and spaces.',
            'contact_name.*.regex' => 'Nama kontak hanya boleh berisi huruf dan spasi. | Contact name may only contain letters and spaces.',
            'bank_name.*.regex' => 'Nama bank hanya boleh berisi huruf, angka, spasi, titik, dan tanda hubung. | Bank name may only contain letters, numbers, spaces, periods, and hyphens.',
            'account_name.*.regex' => 'Nama akun hanya boleh berisi huruf dan spasi. | Account name may only contain letters and spaces.',
            // ... (rest of messages sama)
        ];

        $validated = $request->validate($rules, $messages);
        $this->normalizeAndValidateAddresses($validated);

        return $validated;
    }

    private function normalizeAndValidateAddresses(array &$validated): void
    {
        $fields = ['address_role', 'country_code', 'country', 'province', 'city', 'zip_code', 'latitude', 'longitude', 'telephone', 'fax'];
        $count = count($validated['address'] ?? []);
        foreach ($fields as $field) {
            if (!isset($validated[$field]) || count($validated[$field]) !== $count) {
                throw ValidationException::withMessages([$field => 'Data alamat tidak lengkap atau tidak sejajar.']);
            }
        }
        if (($validated['address_role'][0] ?? null) !== 'legal' || count(array_filter($validated['address_role'], function ($role) { return $role === 'legal'; })) !== 1) {
            throw ValidationException::withMessages(['address_role' => 'Alamat legal wajib ada tepat satu.']);
        }
        foreach ($validated['address'] as $index => $address) {
            $country = CountryCatalog::find($validated['country_code'][$index]);
            if (!$country) throw ValidationException::withMessages(["country_code.$index" => 'Negara tidak valid.']);
            $validated['country_code'][$index] = $country['code'];
            $validated['country'][$index] = $country['name'];
            if ($country['code'] !== 'ID') continue;
            $province = Provinces::find($validated['province'][$index]);
            $regency = $province ? Regencies::where('province_id', $province->id)->find($validated['city'][$index]) : null;
            $postal = $regency ? PostalCode::where('province_id', $province->id)->where('regency_id', $regency->id)->where('postal_code', $validated['zip_code'][$index])->exists() : false;
            if (!$province || !$regency || !$postal) throw ValidationException::withMessages(["zip_code.$index" => 'Relasi provinsi, kota, dan kode pos tidak valid.']);
            $validated['province'][$index] = $province->name;
            $validated['city'][$index] = $regency->name;
        }
    }

    private function getClosedMessage(FormLink $formLink)
    {
        if (!$formLink->is_active) {
            return 'Form ini sudah tidak aktif.';
        }
        if ($formLink->isExpired()) {
            return 'Form ini sudah kadaluarsa pada ' . $formLink->expires_at->format('d M Y H:i');
        }
        if ($formLink->hasReachedMaxSubmissions()) {
            return 'Form ini sudah mencapai batas maksimal submission.';
        }
        return 'Form ini sudah tidak dapat menerima submission.';
    }
}
