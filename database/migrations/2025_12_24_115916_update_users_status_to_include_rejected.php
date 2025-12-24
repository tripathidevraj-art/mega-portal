<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change ENUM to VARCHAR to support 'rejected' and future statuses
            $table->string('status')->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Optional: revert to ENUM (but risky if 'rejected' data exists)
            DB::statement("ALTER TABLE users MODIFY status ENUM('pending','verified','suspended','active') NOT NULL DEFAULT 'pending'");
        });
    }
};