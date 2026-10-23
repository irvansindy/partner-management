<?php

namespace App\Console\Commands;

use App\Models\CompanyAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateCompanyAttachmentsToPrivateCommand extends Command
{
    protected $signature = 'company-attachments:migrate-private {--dry-run}';
    protected $description = 'Move legacy public company attachments into verified private storage';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $migrated = 0;
        $failed = 0;

        CompanyAttachment::where(function ($query) {
            $query->whereNull('storage_disk')->orWhere('storage_disk', 'public');
        })->orderBy('id')->chunkById(100, function ($attachments) use ($dryRun, &$migrated, &$failed) {
            foreach ($attachments as $attachment) {
                if ($dryRun) {
                    $migrated++;
                    continue;
                }
                try {
                    $this->migrate($attachment);
                    $migrated++;
                } catch (\Throwable $exception) {
                    $failed++;
                    $this->error("Attachment {$attachment->id}: ".$exception->getMessage());
                }
            }
        });

        $this->info(($dryRun ? 'Would migrate' : 'Migrated').": {$migrated}; failed: {$failed}");
        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function migrate(CompanyAttachment $attachment): void
    {
        $source = Storage::disk('public');
        $sourcePath = $attachment->filepath;
        if (!$source->exists($sourcePath)) throw new \RuntimeException('source file is missing');

        $contents = $source->get($sourcePath);
        $hash = hash('sha256', $contents);
        $extension = strtolower(pathinfo($attachment->filename, PATHINFO_EXTENSION) ?: $attachment->filetype ?: 'bin');
        $targetPath = 'companies/'.$attachment->company_id.'/'.Str::uuid().'.'.$extension;
        $target = Storage::disk('company_attachments');
        if (!$target->put($targetPath, $contents) || $target->size($targetPath) !== strlen($contents) || hash('sha256', $target->get($targetPath)) !== $hash) {
            $target->delete($targetPath);
            throw new \RuntimeException('target verification failed');
        }

        try {
            DB::transaction(function () use ($attachment, $targetPath, $hash) {
                $attachment->update([
                    'filepath' => $targetPath,
                    'storage_disk' => 'company_attachments',
                    'sha256' => $hash,
                    'original_name' => $attachment->original_name ?: $attachment->filename,
                ]);
            });
        } catch (\Throwable $exception) {
            $target->delete($targetPath);
            throw $exception;
        }

        if (!$source->delete($sourcePath)) {
            throw new \RuntimeException('source cleanup failed after verified migration');
        }
    }
}
