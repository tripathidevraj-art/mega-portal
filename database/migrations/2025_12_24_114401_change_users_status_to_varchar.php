<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('status')->default('pending')->change();
    });
}

public function down()
{
    // Revert to ENUM if needed (optional)
    DB::statement("ALTER TABLE users MODIFY status ENUM('pending', 'verified', 'suspended', 'active') NOT NULL DEFAULT 'pending'");
}
};
