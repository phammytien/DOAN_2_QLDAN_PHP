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
    Schema::table('TaiKhoan', function (Blueprint $table) {
        $table->enum('TrangThai', ['active', 'locked'])->default('active');
    });
}

public function down()
{
    Schema::table('TaiKhoan', function (Blueprint $table) {
        $table->dropColumn('TrangThai');
    });
}

};