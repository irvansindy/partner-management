<?php

namespace App\Services;

use App\Models\CompanyAttachment;
use App\Models\CompanyInformation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyAttachmentService
{
    private const TYPES = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
    ];

    public function validate(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $size = $file->getSize();
        if (!$path || $size === false || $size > 5 * 1024 * 1024) {
            $this->invalid('Ukuran file maksimal 5 MB.');
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        if (!isset(self::TYPES[$mime])) {
            $this->invalid('Isi file harus berupa PDF, JPEG, atau PNG yang valid.');
        }

        $width = null;
        $height = null;
        if ($mime === 'application/pdf') {
            $prefix = file_get_contents($path, false, null, 0, 5);
            if ($prefix !== '%PDF-') $this->invalid('Signature PDF tidak valid.');
        } else {
            $image = @getimagesize($path);
            $expectedType = $mime === 'image/png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
            $contents = file_get_contents($path);
            $decoded = function_exists('imagecreatefromstring') ? @imagecreatefromstring($contents) : true;
            if (!$image || $image[2] !== $expectedType || !$decoded) {
                $this->invalid('File gambar rusak atau tidak sesuai format.');
            }
            if (is_resource($decoded) || is_object($decoded)) imagedestroy($decoded);
            [$width, $height] = $image;
            if ($width > 6000 || $height > 6000) $this->invalid('Dimensi gambar maksimal 6000 × 6000 piksel.');
        }

        $original = basename(str_replace('\\', '/', $file->getClientOriginalName()));
        $original = preg_replace('/[^\p{L}\p{N}._ -]/u', '_', $original) ?: 'attachment.'.self::TYPES[$mime];
        $original = str_replace('..', '_', $original);

        return [
            'mime' => $mime,
            'extension' => self::TYPES[$mime],
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'original_name' => mb_substr($original, 0, 255),
            'sha256' => hash_file('sha256', $path),
        ];
    }

    public function store(CompanyInformation $company, UploadedFile $file, array $metadata, int $sortOrder): CompanyAttachment
    {
        $filename = Str::uuid().'.'.$metadata['extension'];
        $path = 'companies/'.$company->id.'/'.$filename;
        Storage::disk('company_attachments')->putFileAs('companies/'.$company->id, $file, $filename);

        try {
            return CompanyAttachment::create([
                'company_id' => $company->id,
                'filename' => $filename,
                'original_name' => $metadata['original_name'],
                'filepath' => $path,
                'filesize' => $metadata['size'],
                'filetype' => $metadata['extension'],
                'mime_type' => $metadata['mime'],
                'storage_disk' => 'company_attachments',
                'width' => $metadata['width'],
                'height' => $metadata['height'],
                'sha256' => $metadata['sha256'],
                'sort_order' => $sortOrder,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('company_attachments')->delete($path);
            throw $exception;
        }
    }

    public function deleteStored(iterable $attachments): void
    {
        foreach ($attachments as $attachment) {
            Storage::disk($attachment->storage_disk ?: 'company_attachments')->delete($attachment->filepath);
        }
    }

    private function invalid(string $message): void
    {
        throw ValidationException::withMessages(['input-multiple-file' => $message]);
    }
}
