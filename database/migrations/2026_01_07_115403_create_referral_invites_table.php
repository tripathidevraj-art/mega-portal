<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referral_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('contact'); // phone or email
            $table->enum('type', ['whatsapp', 'email']);
            $table->string('referral_code');
            $table->boolean('accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            // ✅ Index INSIDE the closure
            $table->index(['user_id', 'accepted']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_invites');
    }
};