<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::create('job_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
        $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
        $table->enum('reason', ['incomplete', 'inappropriate', 'fake', 'spam', 'other'])->default('other');
        $table->text('details')->nullable();
        $table->enum('status', ['pending', 'reviewed', 'action_taken'])->default('pending');
        $table->timestamps();
        $table->softDeletes(); // 👈 included here
    });
}

public function down()
{
    Schema::dropIfExists('job_reports');
}
};
