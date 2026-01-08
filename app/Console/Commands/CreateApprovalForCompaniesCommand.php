<?php

namespace App\Console\Commands;

use App\Services\ApprovalProcessService;
use Illuminate\Console\Command;

class CreateApprovalForCompaniesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approval:create-for-companies
                            {--company-ids=* : Specific company IDs to process}
                            {--force : Force creation even if approval exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create approval processes for companies without approval';

    /**
     * Execute the console command.
     */
    public function handle(ApprovalProcessService $approvalProcessService)
    {
        $this->info('Starting approval creation process...');

        $companyIds = $this->option('company-ids');

        if (!empty($companyIds)) {
            $this->info('Processing specific company IDs: ' . implode(', ', $companyIds));
        } else {
            $this->info('Processing all companies without approval...');
        }

        try {
            $stats = $approvalProcessService->bulkCreateApprovals($companyIds);

            $this->newLine();
            $this->info('Approval Creation Summary:');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Companies', $stats['total']],
                    ['Successfully Created', $stats['success']],
                    ['Failed', $stats['failed']],
                    ['Skipped (No Template)', $stats['skipped']],
                ]
            );

            if (!empty($stats['errors'])) {
                $this->newLine();
                $this->warn('Errors encountered:');
                foreach ($stats['errors'] as $error) {
                    $this->line('  - ' . $error);
                }
            }

            if ($stats['success'] > 0) {
                $this->newLine();
                $this->info("✓ Successfully created {$stats['success']} approval processes!");
            }

            if ($stats['skipped'] > 0) {
                $this->newLine();
                $this->warn("⚠ Skipped {$stats['skipped']} companies (no matching approval template)");
            }

            if ($stats['failed'] > 0) {
                $this->newLine();
                $this->error("✗ Failed to create {$stats['failed']} approval processes");
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}