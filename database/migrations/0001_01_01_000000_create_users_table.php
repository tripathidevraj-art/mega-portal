<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Address
            $table->string('country');
            $table->text('current_address');
            
            // Professional
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->text('skills')->nullable(); // JSON or text
            
            // Documents
            $table->string('civil_id')->nullable()->unique();
            $table->string('passport_number')->nullable()->unique();
            $table->date('passport_expiry')->nullable();
            $table->string('residency_type')->nullable();
            $table->date('residency_expiry')->nullable();
            
            // Volunteer
            $table->text('volunteer_interests')->nullable(); // JSON or text
            
            // Account
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->enum('status', ['active', 'suspended', 'pending', 'verified'])->default('pending');
            $table->string('suspension_reason')->nullable();
            $table->timestamp('suspended_until')->nullable();
            
            // Email Verification
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_token')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};