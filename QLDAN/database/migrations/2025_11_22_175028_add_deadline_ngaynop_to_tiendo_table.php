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
    Schema::table('tiendo', function (Blueprint $table) {
        $table->dateTime('Deadline')->nullable()->after('GhiChu');
        $table->dateTime('NgayNop')->nullable()->after('Deadline');
    });
}

public function down()
{
    Schema::table('tiendo', function (Blueprint $table) {
        $table->dropColumn(['Deadline', 'NgayNop']);
    });
}

};