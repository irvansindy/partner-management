<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HardenApprovalConstraints extends Migration
{
    public function up()
    {
        $this->assertNoDuplicates('approval_templates', ['location_id', 'department_id']);
        $this->assertNoDuplicates('approval_template_details', ['approval_id', 'step_ordering']);
        $this->assertNoDuplicates('approval_template_details', ['approval_id', 'user_id']);
        $this->assertNoDuplicates('approval_processes', ['company_information_id']);
        $this->assertNoDuplicates('approval_process_steps', ['approval_id', 'step_ordering']);

        Schema::table('approval_templates', function (Blueprint $table) {
            $table->unique(['location_id', 'department_id'], 'approval_templates_location_department_unique');
        });
        Schema::table('approval_template_details', function (Blueprint $table) {
            $table->unique(['approval_id', 'step_ordering'], 'approval_template_steps_unique');
            $table->unique(['approval_id', 'user_id'], 'approval_template_users_unique');
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->unique('company_information_id', 'approval_process_company_unique');
        });
        Schema::table('approval_process_steps', function (Blueprint $table) {
            $table->unique(['approval_id', 'step_ordering'], 'approval_process_steps_unique');
        });
    }

    public function down()
    {
        Schema::table('approval_process_steps', function (Blueprint $table) {
            $table->dropUnique('approval_process_steps_unique');
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->dropUnique('approval_process_company_unique');
        });
        Schema::table('approval_template_details', function (Blueprint $table) {
            $table->dropUnique('approval_template_steps_unique');
            $table->dropUnique('approval_template_users_unique');
        });
        Schema::table('approval_templates', function (Blueprint $table) {
            $table->dropUnique('approval_templates_location_department_unique');
        });
    }

    private function assertNoDuplicates(string $table, array $columns): void
    {
        $query = DB::table($table)->select($columns);
        foreach ($columns as $column) {
            $query->groupBy($column);
        }
        if ($query->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException(sprintf(
                'Duplicate approval data found in %s for columns %s. Resolve duplicates before migrating.',
                $table,
                implode(', ', $columns)
            ));
        }
    }
}
