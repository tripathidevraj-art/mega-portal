<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('referrals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('referred_id')->constrained('users')->onDelete('cascade');
        $table->tinyInteger('level')->unsigned(); // 1, 2, 3...
        $table->integer('points_awarded')->default(0);
        $table->timestamps();

        $table->unique('referred_id'); // One referral only
        $table->index(['referrer_id', 'level']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
