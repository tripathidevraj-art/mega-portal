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
        $table->string('phone_country_code'); // NEW
        $table->string('phone');
        $table->string('whatsapp_country_code')->nullable(); // NEW
        $table->string('whatsapp')->nullable(); // NEW
        $table->date('date_of_birth');
        $table->enum('gender', ['male', 'female', 'other']);
        
        // Address
        $table->string('country');
        $table->string('state'); // NEW
        $table->string('city'); // NEW
        $table->string('zip_code'); // NEW
        $table->text('current_address');
        $table->text('communication_address')->nullable(); // NEW
        
        // Professional (RENAMED + NEW)
        $table->string('designation')->nullable(); // was 'occupation'
        $table->string('company_name')->nullable(); // was 'company'
        $table->string('industry_experience')->nullable(); // NEW (removed 'skills')
        
        // Documents
        $table->string('civil_id')->nullable()->unique();
        $table->string('civil_id_file_path')->nullable(); // NEW
        $table->string('passport_number')->nullable()->unique();
        $table->date('passport_expiry')->nullable();
        $table->string('residency_type')->nullable();
        $table->date('residency_expiry')->nullable();
        
        // Volunteer
        $table->text('volunteer_interests')->nullable();
        
        // Additional Info
        $table->text('additional_info')->nullable(); // NEW
        
        // Account
        $table->string('password');
        $table->string('profile_image')->nullable();
        $table->enum('role', ['admin', 'user', 'superadmin'])->default('user'); // added superadmin
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