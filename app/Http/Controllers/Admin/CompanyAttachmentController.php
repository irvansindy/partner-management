<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyAttachment;
use App\Services\FormLinkService;
use Illuminate\Support\Facades\Storage;

class CompanyAttachmentController extends Controller
{
    private $formLinkService;

    public function __construct(FormLinkService $formLinkService)
    {
        $this->formLinkService = $formLinkService;
    }

    public function show(CompanyAttachment $companyAttachment)
    {
        return $this->stream($companyAttachment, false);
    }

    public function download(CompanyAttachment $companyAttachment)
    {
        return $this->stream($companyAttachment, true);
    }

    private function stream(CompanyAttachment $attachment, bool $download)
    {
        $attachment->loadMissing('company.formLink');
        abort_unless($attachment->company && $attachment->company->formLink, 404);
        $this->formLinkService->authorizeAccess($attachment->company->formLink);

        $disk = $attachment->storage_disk ?: 'public';
        abort_unless(Storage::disk($disk)->exists($attachment->filepath), 404);
        $mime = $attachment->mime_type ?: $attachment->filetype ?: 'application/octet-stream';
        $name = preg_replace('/[^\p{L}\p{N}._ -]/u', '_', basename($attachment->original_name ?: $attachment->filename)) ?: 'attachment';
        $disposition = ($download ? 'attachment' : 'inline').'; filename="'.addcslashes($name, '"\\').'"';

        return response()->stream(function () use ($disk, $attachment) {
            $stream = Storage::disk($disk)->readStream($attachment->filepath);
            fpassthru($stream);
            fclose($stream);
        }, 200, array_filter([
            'Content-Type' => $mime,
            'Content-Disposition' => $disposition,
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => $download ? null : 'sandbox',
        ]));
    }
}
