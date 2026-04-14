<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormLinkStoreRequest;
use App\Http\Requests\FormLinkUpdateRequest;
use App\Models\FormLink;
use App\Services\FormLinkService;
use Illuminate\Http\Request;

class FormLinkController extends Controller
{
    protected $formLinkService;

    public function __construct(FormLinkService $formLinkService)
    {
        $this->formLinkService = $formLinkService;
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

        return view('admin.form_links.submissions', compact('formLink', 'companies'));
    }

    /**
     * Display a specific submission detail
     * ✅ UPDATED: Load approval process
     */
    public function submissionDetail(FormLink $formLink, $companyId)
    {
        $this->formLinkService->authorizeAccess($formLink);

        // Get company with approval process loaded
        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);

        // Load approval process with all relations
        $company->load([
            'approvalProcess.steps.approver',
            'approvalProcess.initiator',
            'approvalProcess.department',
            'approvalProcess.office'
        ]);

        return view('admin.form_links.submission_detail', compact('formLink', 'company'));
    }
    public function exportSubmissionPdf(FormLink $formLink, $companyId)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $company->load(['contact', 'address', 'bank', 'liablePeople', 'attachment']);

        $header = '<table width="100%"><tr><td></td></tr></table>'; // spacer, header sudah di dalam view

        $footer = '<hr>
        <table width="100%" style="font-size: 9px;">
            <tr>
                <td width="90%" align="left"><b>Disclaimer</b><br>
                This document is strictly private, confidential and personal to recipients.</td>
                <td width="10%" style="text-align:right;">{PAGENO}</td>
            </tr>
        </table>';

        $mpdf = new \Mpdf\Mpdf([
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 10,
            'margin_bottom' => 20,
            'margin_footer' => 8,
        ]);
        $mpdf->SetHTMLFooter($footer);

        $html = view('admin.form_links.export_submission_pdf', compact('company'))->render();
        $mpdf->WriteHTML($html);

        ob_clean();
        $filename = 'form_penyedia_' . \Str::slug($company->name) . '_' . date('Y-m-d') . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    /**
     * Show edit form for a submission
     * Hanya bisa diakses jika status approval 0 (Menunggu) atau 1 (Dalam Proses)
     */
    public function submissionEdit(FormLink $formLink, $companyId)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $company->load([
            'contact',
            'address',
            'bank',
            'liablePeople',
            'attachment',
            'approvalProcess',
        ]);

        // Cek apakah boleh diedit berdasarkan status approval
        if ($company->hasApproval()) {
            $status = $company->approvalProcess->status;
            // Hanya status 0 (Menunggu) dan 1 (Dalam Proses) yang boleh diedit
            if (!in_array($status, [0, 1])) {
                return redirect()
                    ->route('admin.form-links.submission-detail', [$formLink, $companyId])
                    ->with('error', 'Data tidak dapat diedit karena proses approval sudah ' . $company->getApprovalStatus() . '.');
            }
        }

        return view('admin.form_links.submission_edit', compact('formLink', 'company'));
    }

    /**
     * Update submission data
     * Hanya bisa dilakukan jika status approval 0 (Menunggu) atau 1 (Dalam Proses)
     */
    public function submissionUpdate(Request $request, FormLink $formLink, $companyId)
    {
        $this->formLinkService->authorizeAccess($formLink);

        $company = $this->formLinkService->getSubmissionDetail($formLink, $companyId);
        $company->load(['approvalProcess']);

        // Double-check: validasi status sebelum update
        if ($company->hasApproval()) {
            $status = $company->approvalProcess->status;
            if (!in_array($status, [0, 1])) {
                return redirect()
                    ->route('admin.form-links.submission-detail', [$formLink, $companyId])
                    ->with('error', 'Data tidak dapat diubah karena proses approval sudah ' . $company->getApprovalStatus() . '.');
            }
        }

        // Validasi input
        $validated = $request->validate([
            // Company Information
            'name'                              => 'required|string|max:255',
            'group_name'                        => 'nullable|string|max:255',
            'established_year'                  => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_employee'                    => 'nullable|integer|min:0',
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
            'liable_people.*.id'                => 'nullable|integer',
            'liable_people.*.name'              => 'required_with:liable_people|string|max:255',
            'liable_people.*.position'          => 'required_with:liable_people|string|max:255',
            'liable_people.*.nik'               => 'required_with:liable_people|string|max:50',

            // Contact Persons
            'contacts'                          => 'nullable|array',
            'contacts.*.id'                     => 'nullable|integer',
            'contacts.*.department'             => 'required_with:contacts|string|max:255',
            'contacts.*.position'               => 'required_with:contacts|string|max:255',
            'contacts.*.name'                   => 'required_with:contacts|string|max:255',
            'contacts.*.email'                  => 'required_with:contacts|email|max:255',
            'contacts.*.telephone'              => 'required_with:contacts|string|max:50',

            // Addresses
            'addresses'                         => 'nullable|array',
            'addresses.*.id'                    => 'nullable|integer',
            'addresses.*.address'               => 'required_with:addresses|string',
            'addresses.*.country'               => 'required_with:addresses|string|max:100',
            'addresses.*.province'              => 'required_with:addresses|string|max:100',
            'addresses.*.city'                  => 'required_with:addresses|string|max:100',
            'addresses.*.zip_code'              => 'required_with:addresses|string|max:20',
            'addresses.*.telephone'             => 'required_with:addresses|string|max:50',
            'addresses.*.fax'                   => 'nullable|string|max:50',
            'addresses.*.latitude'              => 'nullable|numeric',
            'addresses.*.longitude'             => 'nullable|numeric',

            // Banks
            'banks'                             => 'nullable|array',
            'banks.*.id'                        => 'nullable|integer',
            'banks.*.name'                      => 'required_with:banks|string|max:255',
            'banks.*.account_name'              => 'required_with:banks|string|max:255',
            'banks.*.account_number'            => 'required_with:banks|string|max:100',

            // Attachments (hanya untuk upload file baru)
            'new_attachments'                   => 'nullable|array',
            'new_attachments.*'                 => 'file|max:10240|mimes:pdf,jpg,jpeg,png',
            'delete_attachment_ids'             => 'nullable|array',
            'delete_attachment_ids.*'           => 'integer',
        ]);

        try {
            \DB::transaction(function () use ($company, $validated, $request) {

                // ── 1. Update Company Information ──────────────────────────────
                $company->update([
                    'name'                              => $validated['name'],
                    'group_name'                        => $validated['group_name'] ?? null,
                    'established_year'                  => $validated['established_year'] ?? null,
                    'total_employee'                    => $validated['total_employee'] ?? null,
                    'business_classification'           => $validated['business_classification'],
                    'business_classification_detail'    => $validated['business_classification_detail'] ?? null,
                    'register_number_as_in_tax_invoice' => $validated['register_number_as_in_tax_invoice'] ?? null,
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
                    foreach ($toDelete as $att) {
                        \Storage::disk('public')->delete($att->filepath);
                        $att->delete();
                    }
                }

                // Upload attachment baru
                if ($request->hasFile('new_attachments')) {
                    foreach ($request->file('new_attachments') as $file) {
                        $path = $file->store('attachments/' . $company->id, 'public');
                        $company->attachment()->create([
                            'filename' => $file->getClientOriginalName(),
                            'filepath' => $path,
                            'filetype' => $file->getMimeType(),
                            'filesize' => $file->getSize(),
                        ]);
                    }
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
}