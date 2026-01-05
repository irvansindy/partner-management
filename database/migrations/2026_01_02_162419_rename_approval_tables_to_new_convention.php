<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameApprovalTablesToNewConvention extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename template tables (master setting)
        Schema::rename('master_approval_models', 'approval_templates');
        Schema::rename('detail_approval_models', 'approval_template_details');

        // Rename process tables (real approval)
        Schema::rename('approval_masters', 'approval_processes');
        Schema::rename('approval_details', 'approval_process_steps');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('approval_templates', 'master_approval_models');
        Schema::rename('approval_template_details', 'detail_approval_models');
        Schema::rename('approval_processes', 'approval_masters');
        Schema::rename('approval_process_steps', 'approval_details');
    }
}
