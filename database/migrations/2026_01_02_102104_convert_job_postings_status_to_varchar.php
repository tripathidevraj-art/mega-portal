<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Handle the 'expired' status you found (not in original ENUM)
        // Convert any unexpected status to 'rejected' or keep as-is in VARCHAR
        
        // Optional: normalize statuses (e.g., treat 'expired' as 'rejected')
        // DB::table('job_postings')->where('status', 'expired')->update(['status' => 'rejected']);
        
        // Just convert to VARCHAR(20) — it will keep all values: pending, approved, rejected, expired
        DB::statement("ALTER TABLE job_postings MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        // If rolling back, convert back to ENUM (but include 'expired'!)
        DB::statement("ALTER TABLE job_postings MODIFY status ENUM('pending','approved','rejected','expired') NOT NULL DEFAULT 'pending'");
    }
};