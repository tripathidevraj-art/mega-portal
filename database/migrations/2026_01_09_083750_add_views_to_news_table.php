<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('news', function (Blueprint $table) {
        $table->unsignedInteger('views')->default(0)->after('admin_id');
    });
}

public function down()
{
    Schema::table('news', function (Blueprint $table) {
        $table->dropColumn('views');
    });
}
};
