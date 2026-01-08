<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ Tambah kolom notes & timestamps
        Schema::table('approval_process_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('approval_process_steps', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('approval_process_steps', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('approval_process_steps', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
        });

        // ✅ Tambah index menggunakan raw SQL (tidak pakai Doctrine)
        $this->addIndexIfNotExists(
            'approval_process_steps',
            ['approval_id', 'status'],
            'idx_approval_process_steps_process_status'
        );

        $this->addIndexIfNotExists(
            'approval_process_steps',
            ['user_id', 'status'],
            'idx_approval_process_steps_user_status'
        );

        // ✅ Index untuk approval_processes
        $this->addIndexIfNotExists(
            'approval_processes',
            ['company_information_id', 'status'],
            'idx_approval_processes_company_status'
        );

        $this->addIndexIfNotExists(
            'approval_processes',
            ['location_id', 'department_id'],
            'idx_approval_processes_location_dept'
        );
    }

    public function down()
    {
        // Drop indexes
        $this->dropIndexIfExists('approval_process_steps', 'idx_approval_process_steps_process_status');
        $this->dropIndexIfExists('approval_process_steps', 'idx_approval_process_steps_user_status');
        $this->dropIndexIfExists('approval_processes', 'idx_approval_processes_company_status');
        $this->dropIndexIfExists('approval_processes', 'idx_approval_processes_location_dept');

        // Drop columns
        Schema::table('approval_process_steps', function (Blueprint $table) {
            if (Schema::hasColumn('approval_process_steps', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('approval_process_steps', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('approval_process_steps', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }

    /**
     * Helper: Add index if not exists
     */
    private function addIndexIfNotExists(string $table, array $columns, string $indexName): void
    {
        try {
            // Cek apakah index sudah ada
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

            if (empty($indexes)) {
                $columnsList = implode('`, `', $columns);
                DB::statement("CREATE INDEX `{$indexName}` ON `{$table}` (`{$columnsList}`)");
            }
        } catch (\Exception $e) {
            // Index mungkin sudah ada atau error lain, skip
            \Log::warning("Failed to create index {$indexName}: " . $e->getMessage());
        }
    }

    /**
     * Helper: Drop index if exists
     */
    private function dropIndexIfExists(string $table, string $indexName): void
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

            if (!empty($indexes)) {
                DB::statement("DROP INDEX `{$indexName}` ON `{$table}`");
            }
        } catch (\Exception $e) {
            // Index tidak ada, skip
            \Log::warning("Failed to drop index {$indexName}: " . $e->getMessage());
        }
    }
};
