<?php

namespace App\Services;

use App\Exceptions\PublicFormClosedException;
use App\Models\CompanyInformation;
use App\Models\FormLink;
use Closure;
use Illuminate\Support\Facades\DB;

class PublicFormSubmissionService
{
    public function submit(FormLink $formLink, string $idempotencyKey, Closure $createCompany): array
    {
        return DB::transaction(function () use ($formLink, $idempotencyKey, $createCompany) {
            $lockedLink = FormLink::query()->lockForUpdate()->findOrFail($formLink->id);
            $existing = CompanyInformation::query()
                ->where('form_link_id', $lockedLink->id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();

            if ($existing) {
                return ['company' => $existing, 'replayed' => true];
            }

            if (!$lockedLink->canAcceptSubmission()) {
                throw new PublicFormClosedException('Public form is closed.');
            }

            $company = $createCompany($lockedLink, $idempotencyKey);
            $lockedLink->incrementSubmissionCount();

            return ['company' => $company, 'replayed' => false];
        });
    }
}
