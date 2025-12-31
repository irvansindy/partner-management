<?php

namespace App\Services;

use App\Models\FormLink;
use App\Models\User;
use App\Models\CompanyInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FormLinkService
{
    /**
     * Get form links filtered by current user's role and permissions
     */
    public function getFormLinksForCurrentUser(): LengthAwarePaginator
    {
        $user = Auth::user();
        $role = $user->roles[0]->name;

        $query = FormLink::query();

        switch ($role) {
            case 'super-admin':
                return $query->latest()->paginate(20);

            case 'admin':
            case 'super-user':
                return $query
                    ->where('department_id', $user->dept->id)
                    ->where('office_id', $user->office->id)
                    ->latest()
                    ->paginate(20);

            default:
                // Return empty paginated result for unauthorized roles
                return $query->where('id', null)->paginate(20);
        }
    }

    /**
     * Get all admin users for selection dropdown
     */
    public function getAdminUsers(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get(['id', 'users.name']);
    }

    /**
     * Create a new form link
     */
    public function createFormLink(array $data): FormLink
    {
        $user = Auth::user();

        // Add user's department and office information
        $data['department_id'] = $user->dept->id;
        $data['office_id'] = $user->office->id;
        $data['created_by'] = $user->id;

        return FormLink::create($data);
    }

    /**
     * Get form link with recent submissions
     */
    public function getFormLinkWithRecentSubmissions(FormLink $formLink): FormLink
    {
        return $formLink->load(['companies' => function($query) {
            $query->with(['contact', 'address', 'bank', 'liablePeople', 'attachment'])
                ->latest()
                ->take(10);
        }]);
    }

    /**
     * Update form link
     */
    public function updateFormLink(FormLink $formLink, array $data): FormLink
    {
        $formLink->update($data);

        return $formLink->fresh();
    }

    /**
     * Toggle form link active status
     */
    public function toggleStatus(FormLink $formLink): string
    {
        $formLink->update([
            'is_active' => !$formLink->is_active
        ]);

        return $formLink->is_active ? 'diaktifkan' : 'dinonaktifkan';
    }

    /**
     * Delete form link if it has no submissions
     */
    public function deleteFormLink(FormLink $formLink): bool
    {
        if ($this->hasSubmissions($formLink)) {
            throw new \Exception('Tidak dapat menghapus form yang sudah memiliki submission!');
        }

        return $formLink->delete();
    }

    /**
     * Get all submissions for a form link
     */
    public function getSubmissions(FormLink $formLink): LengthAwarePaginator
    {
        return $formLink->companies()
            ->with([
                'contact',
                'address',
                'bank',
                'liablePeople',
                'salesSurvey',
                'productCustomers',
                'attachment'
            ])
            ->latest()
            ->paginate(20);
    }

    /**
     * Get specific submission detail
     */
    public function getSubmissionDetail(FormLink $formLink, int $companyId): CompanyInformation
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

        // Verify company belongs to this form link
        if ($company->form_link_id !== $formLink->id) {
            abort(404, 'Submission tidak ditemukan untuk form link ini.');
        }

        return $company;
    }

    /**
     * Check if form link has any submissions
     */
    public function hasSubmissions(FormLink $formLink): bool
    {
        return $formLink->companies()->count() > 0;
    }

    /**
     * Authorize user access to form link
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function authorizeAccess(FormLink $formLink): void
    {
        $user = Auth::user();
        $role = $user->roles[0]->name;

        // Super admin has access to all form links
        if ($role === 'super-admin') {
            return;
        }

        // Admin and super-user can only access form links from their department and office
        if (in_array($role, ['admin', 'super-user'])) {
            if ($formLink->department_id !== $user->dept->id ||
                $formLink->office_id !== $user->office->id) {
                abort(403, 'Anda tidak memiliki akses ke form link ini.');
            }
            return;
        }

        // Other roles are not authorized
        abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
    }

    /**
     * Check if form link can accept new submissions
     */
    public function canAcceptSubmission(FormLink $formLink): bool
    {
        return $formLink->is_active
            && !$formLink->isExpired()
            && !$formLink->hasReachedMaxSubmissions();
    }

    /**
     * Increment submission count
     */
    public function incrementSubmissionCount(FormLink $formLink): void
    {
        $formLink->increment('submission_count');
    }

    /**
     * Get form link statistics
     */
    public function getStatistics(FormLink $formLink): array
    {
        return [
            'total_submissions' => $formLink->submission_count,
            'remaining_slots' => $formLink->max_submissions
                ? ($formLink->max_submissions - $formLink->submission_count)
                : null,
            'is_active' => $formLink->is_active,
            'is_expired' => $formLink->isExpired(),
            'can_accept' => $this->canAcceptSubmission($formLink),
        ];
    }
}