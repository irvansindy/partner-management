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
}