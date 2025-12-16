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
use App\Models\CompanyAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\FormatResponseJson;

class PublicFormController extends Controller
{
    public function show($token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();

        if (!$formLink->canAcceptSubmission()) {
            return view('public.form_closed', [
                'formLink' => $formLink,
                'message' => $this->getClosedMessage($formLink)
            ]);
        }

        return view('public.form_index', compact('formLink'));
    }

    public function submit(Request $request, $token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();

        if (!$formLink->canAcceptSubmission()) {
            return FormatResponseJson::error(false, 'Form sudah tidak dapat menerima submission.', 503);
        }

        // Validasi data
        $validated = $this->validateSubmission($request, $formLink->form_type);

        DB::beginTransaction();
        try {
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

            // 4. Simpan Addresses
            if (!empty($validated['address'])) {
                foreach ($validated['address'] as $index => $address) {
                    $addressData = [
                        'company_id' => $company->id,
                        'address' => $address,
                        'country' => $validated['country'][$index] ?? null,
                        'province' => $validated['province'][$index] ?? null,
                        'city' => $validated['city'][$index] ?? null,
                        'zip_code' => $validated['zip_code'][$index] ?? null,
                        'telephone' => $validated['telephone'][$index] ?? null,
                        'fax' => $validated['fax'][$index] ?? null,
                    ];

                    // Address index 1 (kedua) memiliki lat/long dari map
                    if ($index == 1) {
                        $addressData['latitude'] = $validated['latitude'] ?? null;
                        $addressData['longitude'] = $validated['longitude'] ?? null;
                    }

                    CompanyAddress::create($addressData);
                }
            }

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

            // ============================================
            // 6. Simpan Survey Data (Conditional)
            // ============================================
            // ✅ HANYA simpan jika:
            // 1. Company type adalah 'customer'
            // 2. DAN minimal ada salah satu field survey yang diisi

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
            if ($request->hasFile('input-multiple-file')) {
                $sortOrder = 1;
                foreach ($request->file('input-multiple-file') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . $sortOrder . '_' . $originalName;
                    $path = $file->storeAs('company_attachments', $filename, 'public');

                    CompanyAttachment::create([
                        'company_id' => $company->id,
                        'filename' => $originalName,
                        'filepath' => $path,
                        'filesize' => $file->getSize(),
                        'filetype' => $file->getMimeType(),
                        'sort_order' => $sortOrder,
                    ]);

                    $sortOrder++;
                }
            }

            // 8. Increment submission count
            $formLink->incrementSubmissionCount();

            DB::commit();

            return FormatResponseJson::success(true, 'Data berhasil dikirim! Terima kasih.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Form submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['input-multiple-file', '_token'])
            ]);

            return FormatResponseJson::error(false, $e->getMessage(), 500);
        }
    }

    public function success()
    {
        return view('public.form_success');
    }

    private function validateSubmission(Request $request, $formType)
    {
        $rules = [
            // Master Information
            'company_type' => 'required|in:vendor,customer',
            'company_name' => 'required|string|max:255',
            'company_group_name' => 'nullable|string|max:255',
            'established_year' => 'nullable',
            'total_employee' => 'nullable|integer|min:1',

            // Liable Persons
            'liable_person.*' => 'required|string|max:255',
            'liable_position.*' => 'required|string|max:255',
            'nik.*' => 'required|string|max:16',

            // Business
            'business_classification' => 'required|string|max:255',
            'business_classification_detail' => 'nullable|string',
            'register_number_as_in_tax_invoice' => 'required|string|max:255',
            'website_address' => 'nullable|max:255',
            'system_management' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'term_of_payment' => 'nullable|string|max:50',

            // Contacts
            'contact_department.*' => 'required|string|max:255',
            'contact_position.*' => 'required|string|max:255',
            'contact_name.*' => 'required|string|max:255',
            'contact_email.*' => 'required|email|max:255',
            'contact_telephone.*' => 'required|string|max:20',

            // Addresses
            'address.*' => 'required|string',
            'country.*' => 'required|string|max:100',
            'province.*' => 'required|string|max:100',
            'city.*' => 'required|string|max:100',
            'zip_code.*' => 'required|string|max:10',
            'telephone.*' => 'required|string|max:20',
            'fax.*' => 'required|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            // Banks
            'bank_name.*' => 'required|string|max:255',
            'account_name.*' => 'required|string|max:255',
            'account_number.*' => 'required|string|max:50',

            // Files
            'input-multiple-file.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        // ============================================
        // ✅ Survey validation - SEMUA NULLABLE
        // Karena user bisa hide survey form
        // ============================================
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
            // ========================= MASTER INFORMATION =========================
            'company_type.required' => 'Jenis perusahaan wajib diisi. | Company type is required.',
            'company_type.in' => 'Jenis perusahaan harus vendor atau customer. | Company type must be vendor or customer.',

            'company_name.required' => 'Nama perusahaan wajib diisi. | Company name is required.',
            'company_name.string' => 'Nama perusahaan harus berupa teks. | Company name must be a string.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter. | Company name may not exceed 255 characters.',

            'company_group_name.string' => 'Nama grup harus berupa teks. | Company group name must be a string.',
            'company_group_name.max' => 'Nama grup maksimal 255 karakter. | Company group name may not exceed 255 characters.',

            'total_employee.integer' => 'Total karyawan harus berupa angka. | Total employee must be an integer.',
            'total_employee.min' => 'Total karyawan minimal 1. | Total employee must be at least 1.',

            // ========================= LIABLE PERSON =========================
            'liable_person.*.required' => 'Nama penanggung jawab wajib diisi. | Liable person name is required.',
            'liable_person.*.string' => 'Nama penanggung jawab harus berupa teks. | Liable person name must be a string.',
            'liable_person.*.max' => 'Nama penanggung jawab maksimal 255 karakter. | Liable person name may not exceed 255 characters.',

            'liable_position.*.required' => 'Jabatan wajib diisi. | Liable position is required.',
            'liable_position.*.string' => 'Jabatan harus berupa teks. | Liable position must be a string.',
            'liable_position.*.max' => 'Jabatan maksimal 255 karakter. | Liable position may not exceed 255 characters.',

            'nik.*.required' => 'NIK wajib diisi. | NIK is required.',
            'nik.*.string' => 'NIK harus berupa teks. | NIK must be a string.',
            'nik.*.max' => 'NIK maksimal 16 karakter. | NIK may not exceed 16 characters.',

            // ========================= BUSINESS =========================
            'business_classification.required' => 'Klasifikasi bisnis wajib diisi. | Business classification is required.',
            'business_classification.string' => 'Klasifikasi bisnis harus berupa teks. | Business classification must be a string.',
            'business_classification.max' => 'Klasifikasi bisnis maksimal 255 karakter. | Business classification may not exceed 255 characters.',

            'business_classification_detail.string' => 'Detail klasifikasi harus berupa teks. | Business classification detail must be a string.',

            'register_number_as_in_tax_invoice.required' => 'Nomor registrasi pajak wajib diisi. | Tax invoice registration number is required.',
            'register_number_as_in_tax_invoice.max' => 'Nomor registrasi maksimal 255 karakter. | Tax invoice registration number may not exceed 255 characters.',

            'website_address.max' => 'Alamat website maksimal 255 karakter. | Website address may not exceed 255 characters.',

            'system_management.string' => 'Sistem manajemen harus berupa teks. | System management must be a string.',
            'system_management.max' => 'Sistem manajemen maksimal 255 karakter. | System management may not exceed 255 characters.',

            'email_address.email' => 'Format email tidak valid. | Email address must be a valid email.',

            'credit_limit.numeric' => 'Limit kredit harus berupa angka. | Credit limit must be numeric.',
            'credit_limit.min' => 'Limit kredit minimal 0. | Credit limit must be at least 0.',

            'term_of_payment.string' => 'Term of payment harus berupa teks. | Term of payment must be a string.',
            'term_of_payment.max' => 'Term of payment maksimal 50 karakter. | Term of payment may not exceed 50 characters.',

            // ========================= CONTACTS =========================
            'contact_department.*.required' => 'Departemen kontak wajib diisi. | Contact department is required.',
            'contact_position.*.required' => 'Jabatan kontak wajib diisi. | Contact position is required.',
            'contact_name.*.required' => 'Nama kontak wajib diisi. | Contact name is required.',
            'contact_email.*.required' => 'Email kontak wajib diisi. | Contact email is required.',
            'contact_email.*.email' => 'Format email kontak tidak valid. | Invalid contact email format.',
            'contact_telephone.*.required' => 'Telepon kontak wajib diisi. | Contact telephone is required.',

            // ========================= ADDRESSES =========================
            'address.*.required' => 'Alamat wajib diisi. | Address is required.',
            'country.*.required' => 'Negara wajib diisi. | Country is required.',
            'province.*.required' => 'Provinsi wajib diisi. | Province is required.',
            'city.*.required' => 'Kota wajib diisi. | City is required.',
            'zip_code.*.required' => 'Kode pos wajib diisi. | Zip code is required.',
            'telephone.*.required' => 'Telepon wajib diisi. | Telephone is required.',
            'fax.*.required' => 'Fax wajib diisi. | Fax is required.',

            // ========================= BANKS =========================
            'bank_name.*.required' => 'Nama bank wajib diisi. | Bank name is required.',
            'account_name.*.required' => 'Nama akun bank wajib diisi. | Account name is required.',
            'account_number.*.required' => 'Nomor rekening wajib diisi. | Account number is required.',

            // ========================= FILES =========================
            'input-multiple-file.*.mimes' => 'File harus berupa jpg, jpeg, png, atau pdf. | File must be jpg, jpeg, png, or pdf.',
            'input-multiple-file.*.max' => 'Ukuran file maksimal 5MB. | File size may not exceed 5MB.',

            // ========================= SURVEY (Customer Only) =========================
            'survey_ownership_status.in' => 'Status kepemilikan harus own atau rented. | Ownership status must be own or rented.',
            'rental_year.integer' => 'Tahun sewa harus angka. | Rental year must be an integer.',
            'rental_year.min' => 'Tahun sewa minimal 1900. | Rental year must be at least 1900.',
            'rental_year.max' => 'Tahun sewa tidak boleh melebihi tahun sekarang. | Rental year cannot exceed current year.',
        ];

        return $request->validate($rules, $messages);
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