<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add 'handled_job_report' to the ENUM list
        DB::statement("ALTER TABLE admin_actions_logs MODIFY action ENUM(
            'approved_job',
            'rejected_job',
            'approved_offer',
            'rejected_offer',
            'suspended_user',
            'activated_user',
            'updated_user',
            'handled_job_report'
        ) NOT NULL");
    }

    public function down()
    {
        // Remove 'handled_job_report' (optional, or just keep it)
        DB::statement("ALTER TABLE admin_actions_logs MODIFY action ENUM(
            'approved_job',
            'rejected_job',
            'approved_offer',
            'rejected_offer',
            'suspended_user',
            'activated_user',
            'updated_user'
        ) NOT NULL");
    }
};