<?php

namespace App\Console\Commands;

use App\Services\PostalCodeImportService;
use Illuminate\Console\Command;

class ImportPostalCodesCommand extends Command
{
    protected $signature = 'postal-codes:import {file}';
    protected $description = 'Import Indonesian postal codes from CSV';

    public function handle(PostalCodeImportService $importer): int
    {
        try {
            $result = $importer->import($this->argument('file'));
            $this->info("Inserted: {$result['inserted']}; existing: {$result['existing']}; rejected: {$result['rejected']}");
            foreach ($result['errors'] as $error) $this->warn("Row {$error['row']}: {$error['reason']}");
            return 0;
        } catch (\Throwable $exception) {
            $this->error($exception instanceof \InvalidArgumentException ? $exception->getMessage() : 'Import gagal.');
            return 1;
        }
    }
}
