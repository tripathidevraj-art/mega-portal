<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_actions_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->enum('action', [
                'approved_job', 'rejected_job', 'approved_offer', 'rejected_offer',
                'suspended_user', 'activated_user', 'updated_user'
            ]);
            $table->foreignId('target_user_id')->constrained('users');
            $table->enum('target_type', ['job', 'offer', 'user']);
            $table->unsignedBigInteger('target_id');
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_actions_logs');
    }
};