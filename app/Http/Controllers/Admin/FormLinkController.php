<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormLinkStoreRequest;
use App\Http\Requests\FormLinkUpdateRequest;
use App\Models\FormLink;
use App\Models\ApprovalProcess;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Services\CompanyAttachmentService;
use App\Services\FormLinkService;
use App\Support\CountryCatalog;
use App\Support\EmployeeRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class FormLinkController extends Controller
{
    protected $formLinkService;
    protected $attachmentService;

    public function __construct(FormLinkService $formLinkService, CompanyAttachmentService $attachmentService)
    {
        $this->formLinkService = $formLinkService;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Display a listing of form links
     */
    public function index()
    {
        $formLinks = $this->formLinkService->getFormLinksForCurrentUser();

        return view('admin.form_links.index', compact('formLinks'));
    }

    /**
     * Show the form for creating a new form link
     */
    public function create()
    {
        $pic = $this->formLinkService->getAdminUsers();

        return view('admin.form_links.create', compact('pic'));
    }

    /**
     * Store a newly created form link
     */
    public function store(FormLinkStoreRequest $request)
    {
        $formLink = $this->formLinkService->createFormLink($request->validated());

        return redirect()
            ->route('admin.form-links.show', $formLink)
            ->with('success', 'Form link berhasil dibuat!');
    }

    /**
     * Display the specified form link
     */
    public function show(FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $formLink = $this->formLinkService->getFormLinkWithRecentSubmissions($formLink);

        return view('admin.form_links.show', compact('formLink'));
    }

    /**
     * Show the form for editing the specified form link
     */
    public function edit(FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        return view('admin.form_links.edit', compact('formLink'));
    }

    /**
     * Update the specified form link
     */
    public function update(FormLinkUpdateRequest $request, FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $formLink = $this->formLinkService->updateFormLink($formLink, $request->validated());

        return redirect()
            ->route('admin.form-links.show', $formLink)
            ->with('success', 'Form link berhasil diupdate!');
    }

    /**
     * Toggle the active status of form link
     */
    public function toggleStatus(FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $status = $this->formLinkService->toggleStatus($formLink);

        return back()->with('success', "Form berhasil {$status}!");
    }

    /**
     * Remove the specified form link
     */
    public function destroy(FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        try {
            $this->formLinkService->deleteFormLink($formLink);

            return redirect()
                ->route('admin.form-links.index')
                ->with('success', 'Form link berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display all submissions for a form link
     */
    public function submissions(FormLink $formLink)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $companies = $this->formLinkService->getSubmissions($formLink);
        $companies->getCollection()->each(function ($company) use ($formLink) {
            $company->setAttribute(
                'can_edit_submission',
                $this->formLinkService->canEditSubmission($formLink, $company)
            );
        });

        $submissionStats = [
            'total' => $formLink->companies()->count(),
            'approved' => $formLink->companies()->whereHas('approvalProcess', function ($query) {
                $query->where('status', ApprovalProcess::STATUS_APPROVED);
            })->count(),
            'in_progress' => $formLink->companies()->whereHas('approvalProcess', function ($query) {
                $query->whereIn('status', [ApprovalProcess::STATUS_PENDING, ApprovalProcess::STATUS_IN_PROGRESS]);
            })->count(),
            'rejected' => $formLink->companies()->whereHas('approvalProcess', function ($query) {
                $query->where('status', ApprovalProcess::STATUS_REJECTED);
            })->count(),
        ];

        return view('admin.form_links.submissions', compact('formLink', 'companies', 'submissionStats'));
    }

    /**
     * Display a specific submission detail
     * ✅ UPDATED: Load approval process
     */
    public function submissionDetail(FormLink $formLink, $companyId)
    {
        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $this->formLinkService->authorizeSubmissionRead($formLink, $company);
        $canManageFormLink = $this->formLinkService->canManageFormLink($formLink);

        // Load approval process with all relations
        $company->load([
            'approvalProcess.steps.approver',
            'approvalProcess.initiator',
            'approvalProcess.department',
            'approvalProcess.office'
        ]);
        $canEditSubmission = $this->formLinkService->canEditSubmission($formLink, $company);

        return view('admin.form_links.submission_detail', compact('formLink', 'company', 'canManageFormLink', 'canEditSubmission'));
    }
    public function exportSubmissionPdf(FormLink $formLink, $companyId)
    {
        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $this->formLinkService->authorizeSubmissionRead($formLink, $company);
        $company->load(['contact', 'address', 'bank', 'liablePeople', 'attachment']);

        $footer = '<hr>
        <table width="100%" style="font-size: 9px;">
            <tr>
                <td width="90%" align="left"><b>Disclaimer</b><br>
                This document is strictly private, confidential and personal to recipients.</td>
                <td width="10%" style="text-align:right;">{PAGENO}</td>
            </tr>
        </table>';

        $mpdfTempDirectory = storage_path('framework/cache/mpdf');
        File::ensureDirectoryExists($mpdfTempDirectory, 0775, true);

        $mpdf = new \Mpdf\Mpdf([
            'tempDir'       => $mpdfTempDirectory,
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 10,
            'margin_bottom' => 20,
            'margin_footer' => 8,
        ]);
        $mpdf->SetHTMLFooter($footer);

        $html = view('admin.form_links.export_submission_pdf', compact('company'))->render();
        $mpdf->WriteHTML($html);

        $filename = 'form_penyedia_' . \Str::slug($company->name) . '_' . date('Y-m-d') . '.pdf';
        $pdf = $mpdf->Output('', 'S');

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Content-Length' => strlen($pdf),
        ]);
    }

    /**
     * Show edit form for a submission
     * Only users in the Form Link organization may edit before the final approval stage.
     */
    public function submissionEdit(FormLink $formLink, $companyId)
    {
        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $company->load([
            'contact',
            'address',
            'bank',
            'liablePeople',
            'attachment',
            'approvalProcess',
        ]);

        $this->formLinkService->authorizeSubmissionEdit($formLink, $company);

        return view('admin.form_links.submission_edit', compact('formLink', 'company'));
    }

    /**
     * Update submission data
     * Uses the same organization and approval-stage guard as the edit form.
     */
    public function submissionUpdate(Request $request, FormLink $formLink, $companyId)
    {
        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $company->load(['approvalProcess.steps']);
        $this->formLinkService->authorizeSubmissionEdit($formLink, $company);

        // Validasi input
        $validated = $request->validate([
            // Company Information
            'name'                              => 'required|string|max:255',
            'group_name'                        => 'nullable|string|max:255',
            'established_year'                  => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_employee'                    => ['nullable', Rule::in(EmployeeRange::values())],
            'business_classification'           => 'required|string|max:255',
            'business_classification_detail'    => 'nullable|string|max:255',
            'register_number_as_in_tax_invoice' => 'nullable|string|max:100',
            'website_address'                   => 'nullable|url|max:255',
            'system_management'                 => 'nullable|string|max:255',
            'email_address'                     => 'nullable|email|max:255',
            'credit_limit'                      => 'nullable|numeric|min:0',
            'term_of_payment'                   => 'nullable|string|max:100',

            // Liable Persons
            'liable_people'                     => 'nullable|array',
            'liable_people.*.id'                => [
                'nullable',
                'integer',
                Rule::exists('company_liable_people', 'id')->where('company_id', $company->id),
            ],
            'liable_people.*.name'              => 'required_with:liable_people|string|max:255',
            'liable_people.*.position'          => 'required_with:liable_people|string|max:255',
            'liable_people.*.nik'               => 'required_with:liable_people|string|max:50',

            // Contact Persons
            'contacts'                          => 'nullable|array',
            'contacts.*.id'                     => [
                'nullable',
                'integer',
                Rule::exists('company_contacts', 'id')->where('company_informations_id', $company->id),
            ],
            'contacts.*.department'             => 'required_with:contacts|string|max:255',
            'contacts.*.position'               => 'required_with:contacts|string|max:255',
            'contacts.*.name'                   => 'required_with:contacts|string|max:255',
            'contacts.*.email'                  => 'required_with:contacts|email|max:255',
            'contacts.*.telephone_country_code' => ['required_with:contacts', Rule::in(collect(CountryCatalog::all())->pluck('calling_code')->all())],
            'contacts.*.telephone'              => 'required_with:contacts|string|max:50',

            // Addresses
            'addresses'                         => 'nullable|array',
            'addresses.*.id'                    => [
                'nullable',
                'integer',
                Rule::exists('company_addresses', 'id')->where('company_id', $company->id),
            ],
            'addresses.*.address'               => 'required_with:addresses|string',
            'addresses.*.country'               => 'required_with:addresses|string|max:100',
            'addresses.*.country_code'          => ['required_with:addresses', Rule::in(collect(CountryCatalog::all())->pluck('code')->all())],
            'addresses.*.province'              => 'required_with:addresses|string|max:100',
            'addresses.*.city'                  => 'required_with:addresses|string|max:100',
            'addresses.*.zip_code'              => 'required_with:addresses|string|max:20',
            'addresses.*.telephone_country_code' => ['required_with:addresses', Rule::in(collect(CountryCatalog::all())->pluck('calling_code')->all())],
            'addresses.*.telephone'             => 'required_with:addresses|string|max:50',
            'addresses.*.fax'                   => 'nullable|string|max:50',
            'addresses.*.latitude'              => 'nullable|numeric',
            'addresses.*.longitude'             => 'nullable|numeric',

            // Banks
            'banks'                             => 'nullable|array',
            'banks.*.id'                        => [
                'nullable',
                'integer',
                Rule::exists('company_banks', 'id')->where('company_id', $company->id),
            ],
            'banks.*.name'                      => 'required_with:banks|string|max:255',
            'banks.*.account_name'              => 'required_with:banks|string|max:255',
            'banks.*.account_number'            => 'required_with:banks|string|max:100',

            // Attachments (hanya untuk upload file baru)
            'new_attachments'                   => 'nullable|array',
            'new_attachments.*'                 => 'file|max:' . ((int) config('company_attachments.max_bytes', 5242880) / 1024) . '|mimes:pdf,jpg,jpeg,png',
            'delete_attachment_ids'             => 'nullable|array',
            'delete_attachment_ids.*'           => [
                'integer',
                Rule::exists('company_attachments', 'id')->where('company_id', $company->id),
            ],
        ]);

        foreach ($validated['contacts'] ?? [] as &$contact) {
            $contact['telephone'] = $this->formatInternationalPhone(
                $contact['telephone_country_code'],
                $contact['telephone']
            );
        }
        unset($contact);

        foreach ($validated['addresses'] ?? [] as $index => &$address) {
            $country = CountryCatalog::find($address['country_code']);
            $address['country'] = $country['name'];
            $address['telephone'] = $this->formatInternationalPhone(
                $address['telephone_country_code'],
                $address['telephone']
            );

            if ($country['code'] === 'ID') {
                $province = Provinces::where(function ($query) use ($address) {
                    $query->where('id', $address['province'])
                        ->orWhere('name', $address['province']);
                })->first();
                $regency = $province
                    ? Regencies::where('province_id', $province->id)
                        ->where(function ($query) use ($address) {
                            $query->where('id', $address['city'])
                                ->orWhere('name', $address['city']);
                        })->first()
                    : null;

                if (!$province || !$regency) {
                    return back()->withInput()->withErrors([
                        "addresses.$index.city" => 'The selected province and city/regency do not match.',
                    ]);
                }

                $address['province'] = (string) $province->id;
                $address['city'] = $regency->name;
            }
        }
        unset($address);

        $newAttachments = collect($request->file('new_attachments', []))->values();
        $newAttachmentMetadata = $newAttachments->map(function ($file) {
            return $this->attachmentService->validate($file);
        });

        try {
            \DB::transaction(function () use ($company, $validated, $newAttachments, $newAttachmentMetadata) {

                // ── 1. Update Company Information ──────────────────────────────
                $company->update([
                    'name'                              => $validated['name'],
                    'group_name'                        => $validated['group_name'] ?? null,
                    'established_year'                  => $validated['established_year'] ?? null,
                    'total_employee'                    => $validated['total_employee'] ?? null,
                    'business_classification'           => $validated['business_classification'],
                    'business_classification_detail'    => $validated['business_classification_detail'] ?? null,
                    'npwp'                              => $validated['register_number_as_in_tax_invoice'] ?? null,
                    'website_address'                   => $validated['website_address'] ?? null,
                    'system_management'                 => $validated['system_management'] ?? null,
                    'email_address'                     => $validated['email_address'] ?? null,
                    'credit_limit'                      => $validated['credit_limit'] ?? null,
                    'term_of_payment'                   => $validated['term_of_payment'] ?? null,
                ]);

                // ── 2. Sync Liable Persons ─────────────────────────────────────
                if (isset($validated['liable_people'])) {
                    $existingIds = collect($validated['liable_people'])->pluck('id')->filter()->toArray();
                    // Hapus yang tidak ada di request
                    $company->liablePeople()->whereNotIn('id', $existingIds)->delete();

                    foreach ($validated['liable_people'] as $liable) {
                        if (!empty($liable['id'])) {
                            $company->liablePeople()->where('id', $liable['id'])->update([
                                'name'     => $liable['name'],
                                'position' => $liable['position'],
                                'nik'      => $liable['nik'],
                            ]);
                        } else {
                            $company->liablePeople()->create([
                                'name'     => $liable['name'],
                                'position' => $liable['position'],
                                'nik'      => $liable['nik'],
                            ]);
                        }
                    }
                } else {
                    $company->liablePeople()->delete();
                }

                // ── 3. Sync Contact Persons ────────────────────────────────────
                if (isset($validated['contacts'])) {
                    $existingIds = collect($validated['contacts'])->pluck('id')->filter()->toArray();
                    $company->contact()->whereNotIn('id', $existingIds)->delete();

                    foreach ($validated['contacts'] as $contact) {
                        if (!empty($contact['id'])) {
                            $company->contact()->where('id', $contact['id'])->update([
                                'department' => $contact['department'],
                                'position'   => $contact['position'],
                                'name'       => $contact['name'],
                                'email'      => $contact['email'],
                                'telephone'  => $contact['telephone'],
                            ]);
                        } else {
                            $company->contact()->create([
                                'department' => $contact['department'],
                                'position'   => $contact['position'],
                                'name'       => $contact['name'],
                                'email'      => $contact['email'],
                                'telephone'  => $contact['telephone'],
                            ]);
                        }
                    }
                } else {
                    $company->contact()->delete();
                }

                // ── 4. Sync Addresses ──────────────────────────────────────────
                if (isset($validated['addresses'])) {
                    $existingIds = collect($validated['addresses'])->pluck('id')->filter()->toArray();
                    $company->address()->whereNotIn('id', $existingIds)->delete();

                    foreach ($validated['addresses'] as $addr) {
                        $addrData = [
                            'address'   => $addr['address'],
                            'country'   => $addr['country'],
                            'province'  => $addr['province'],
                            'city'      => $addr['city'],
                            'zip_code'  => $addr['zip_code'],
                            'telephone' => $addr['telephone'],
                            'fax'       => $addr['fax'] ?? null,
                            'latitude'  => $addr['latitude'] ?? null,
                            'longitude' => $addr['longitude'] ?? null,
                        ];
                        if (!empty($addr['id'])) {
                            $company->address()->where('id', $addr['id'])->update($addrData);
                        } else {
                            $company->address()->create($addrData);
                        }
                    }
                } else {
                    $company->address()->delete();
                }

                // ── 5. Sync Banks ──────────────────────────────────────────────
                if (isset($validated['banks'])) {
                    $existingIds = collect($validated['banks'])->pluck('id')->filter()->toArray();
                    $company->bank()->whereNotIn('id', $existingIds)->delete();

                    foreach ($validated['banks'] as $bank) {
                        if (!empty($bank['id'])) {
                            $company->bank()->where('id', $bank['id'])->update([
                                'name'           => $bank['name'],
                                'account_name'   => $bank['account_name'],
                                'account_number' => $bank['account_number'],
                            ]);
                        } else {
                            $company->bank()->create([
                                'name'           => $bank['name'],
                                'account_name'   => $bank['account_name'],
                                'account_number' => $bank['account_number'],
                            ]);
                        }
                    }
                } else {
                    $company->bank()->delete();
                }

                // ── 6. Handle Attachments ──────────────────────────────────────
                // Hapus attachment yang ditandai untuk dihapus
                if (!empty($validated['delete_attachment_ids'])) {
                    $toDelete = $company->attachment()->whereIn('id', $validated['delete_attachment_ids'])->get();
                    $this->attachmentService->deleteStored($toDelete);
                    $company->attachment()->whereIn('id', $validated['delete_attachment_ids'])->delete();
                }

                // Upload attachment baru
                $nextSortOrder = ((int) $company->attachment()->max('sort_order')) + 1;
                foreach ($newAttachments as $index => $file) {
                    $this->attachmentService->store(
                        $company,
                        $file,
                        $newAttachmentMetadata[$index],
                        $nextSortOrder + $index
                    );
                }
            });

            return redirect()
                ->route('admin.form-links.submission-detail', [$company->formLink, $company->id])
                ->with('success', 'Data submission berhasil diupdate!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    private function formatInternationalPhone(string $callingCode, string $number): string
    {
        $callingDigits = preg_replace('/\D+/', '', $callingCode);
        $nationalNumber = ltrim(preg_replace('/\D+/', '', $number), '0');

        return '+' . $callingDigits . $nationalNumber;
    }
}
