<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FormLink;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormLinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles[0]->name;
        $dept_id = $user->dept->id;
        $office_id = $user->office->id;
        // dd([
        //     $role, 'dept' => $dept_id, '' => $office_id
        // ]);
        $formLinks = FormLink::query();
        // $formLinks = FormLink::withCount(['companies', 'creator.dept']);
        switch ($role) {
            case 'super-admin':
                $formLinks = $formLinks->latest()->paginate(20);
                break;
            case 'admin':
            // admin hanya boleh lihat data sesuai department dia
            $formLinks = $formLinks
                ->where('department_id', $dept_id)
                ->where('office_id', $office_id)
                ->latest()
                ->paginate(20);
            break;
            case 'super-user':
            // admin hanya boleh lihat data sesuai department dia
            $formLinks = $formLinks
                ->where('department_id', $dept_id)
                ->where('office_id', $office_id)
                ->latest()
                ->paginate(20);
            break;
        }
        return view('admin.form_links.index', compact('formLinks'));
    }

    public function create()
    {
        $pic = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get(['id', 'users.name']);

        return view('admin.form_links.create', compact('pic'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $role = $user->roles[0]->name;
        $dept_id = $user->dept->id;
        $office_id = $user->office->id;

        $validated = $request->validate([
            'form_type' => 'required|in:vendor,customer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
            'max_submissions' => 'nullable|integer|min:1',
        ]);

        $validated['department_id'] = $dept_id;
        $validated['office_id'] = $office_id;

        $validated['created_by'] = Auth::id();

        $formLink = FormLink::create($validated);

        return redirect()
            ->route('admin.form-links.show', $formLink)
            ->with('success', 'Form link berhasil dibuat!');
    }

    public function show(FormLink $formLink)
    {
        $formLink->load(['companies' => function($query) {
            $query->with(['contact', 'address', 'bank', 'liablePeople', 'attachment'])
                ->latest()
                ->take(10);
        }]);

        return view('admin.form_links.show', compact('formLink'));
    }

    public function edit(FormLink $formLink)
    {
        return view('admin.form_links.edit', compact('formLink'));
    }

    public function update(Request $request, FormLink $formLink)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'max_submissions' => 'nullable|integer|min:1',
        ]);

        $formLink->update($validated);

        return redirect()
            ->route('admin.form-links.show', $formLink)
            ->with('success', 'Form link berhasil diupdate!');
    }

    public function toggleStatus(FormLink $formLink)
    {
        $formLink->update([
            'is_active' => !$formLink->is_active
        ]);

        $status = $formLink->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Form berhasil {$status}!");
    }

    public function destroy(FormLink $formLink)
    {
        if ($formLink->companies()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus form yang sudah memiliki submission!');
        }

        $formLink->delete();

        return redirect()
            ->route('admin.form-links.index')
            ->with('success', 'Form link berhasil dihapus!');
    }

    public function submissions(FormLink $formLink)
    {
        $companies = $formLink->companies()
            ->with(['contact', 'address', 'bank', 'liablePeople', 'salesSurvey', 'productCustomers', 'attachment'])
            ->latest()
            ->paginate(20);

        return view('admin.form_links.submissions', compact('formLink', 'companies'));
    }

    public function submissionDetail(FormLink $formLink, $companyId)
    {
        $company = CompanyInformation::with([
            'contact',
            'address',
            'bank',
            'liablePeople',
            'salesSurvey',
            'productCustomers',
            'attachment'
        ])->findOrFail($companyId);

        if ($company->form_link_id !== $formLink->id) {
            abort(404);
        }

        return view('admin.form_links.submission_detail', compact('formLink', 'company'));
    }
}
