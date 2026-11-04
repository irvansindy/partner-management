<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnableParallelApprovalStages extends Migration
{
    public function up()
    {
        Schema::table('approval_template_details', function (Blueprint $table) {
            $table->dropUnique('approval_template_steps_unique');
        });

        Schema::table('approval_process_steps', function (Blueprint $table) {
            $table->dropUnique('approval_process_steps_unique');
        });
    }

    public function down()
    {
        $this->assertNoDuplicateSteps('approval_template_details');
        $this->assertNoDuplicateSteps('approval_process_steps');

        Schema::table('approval_template_details', function (Blueprint $table) {
            $table->unique(['approval_id', 'step_ordering'], 'approval_template_steps_unique');
        });

        Schema::table('approval_process_steps', function (Blueprint $table) {
            $table->unique(['approval_id', 'step_ordering'], 'approval_process_steps_unique');
        });
    }

    private function assertNoDuplicateSteps(string $table): void
    {
        $hasDuplicates = DB::table($table)
            ->select(['approval_id', 'step_ordering'])
            ->groupBy('approval_id', 'step_ordering')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicates) {
            throw new RuntimeException("Cannot restore single-approver constraint while {$table} contains parallel stages.");
        }
    }
}
