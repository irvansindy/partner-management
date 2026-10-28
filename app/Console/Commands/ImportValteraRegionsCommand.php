<?php

namespace App\Console\Commands;

use App\Services\ValteraRegionImportService;
use Illuminate\Console\Command;

class ImportValteraRegionsCommand extends Command
{
    protected $signature = 'regions:import-valtera
        {directory : Path to Valtera dist/csv directory}
        {--supplement= : Path to kodepos-id kodepos_parsed.csv for Papua Barat Daya}
        {--dry-run : Validate without writing data}';

    protected $description = 'Import Indonesian regions and postal codes from a local Valtera CSV snapshot';

    public function handle(ValteraRegionImportService $importer): int
    {
        try {
            $result = $importer->import(
                (string) $this->argument('directory'),
                (bool) $this->option('dry-run'),
                $this->option('supplement') ? (string) $this->option('supplement') : null
            );
        } catch (\Throwable $exception) {
            $this->error($exception instanceof \InvalidArgumentException ? $exception->getMessage() : 'Import Valtera gagal.');
            return 1;
        }

        $this->line("Provinsi: {$result['provinces']['processed']}; kabupaten/kota: {$result['regencies']['processed']}; kecamatan: {$result['districts']['processed']}.");
        $this->line("Kode pos baru: {$result['postal_codes']['inserted']}; sudah ada: {$result['postal_codes']['existing']}; tanpa kode pos valid: {$result['postal_codes']['without_postal_code']}.");
        if ($result['dry_run']) {
            $this->info('Validasi sumber berhasil. Tidak ada data yang ditulis.');
        } else {
            $this->info('Import wilayah dan kode pos selesai.');
        }

        return 0;
    }
}
