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

        // Gunakan view public, bukan cs_vendor.index
        return view('public.form_index', compact('formLink'));
    }

    public function submit(Request $request, $token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();

        if (!$formLink->canAcceptSubmission()) {
            return response()->json([
                'success' => false,
                'message' => 'Form sudah tidak dapat menerima submission.'
            ], 403);
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
                // register_number_as_in_tax_invoice
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

            // 6. Simpan Survey Data (khusus customer)
            if ($formLink->form_type === 'customer') {
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

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikirim! Terima kasih.',
                'redirect_url' => route('public.form.success')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Form submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
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

        // Survey only for customer
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

        return $request->validate($rules);
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