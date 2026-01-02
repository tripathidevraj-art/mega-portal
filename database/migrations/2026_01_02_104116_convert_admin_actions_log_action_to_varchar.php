<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Convert ENUM to VARCHAR(50) to support any action name
        DB::statement("ALTER TABLE admin_actions_logs MODIFY action VARCHAR(50) NOT NULL");
    }

    public function down()
    {
        // Optional: revert to ENUM (but include all current values)
        DB::statement("ALTER TABLE admin_actions_logs MODIFY action ENUM(
            'approved_job',
            'rejected_job',
            'approved_offer',
            'rejected_offer',
            'suspended_user',
            'activated_user',
            'updated_user',
            'handled_job_report',
            'restored_job'
        ) NOT NULL");
    }
};