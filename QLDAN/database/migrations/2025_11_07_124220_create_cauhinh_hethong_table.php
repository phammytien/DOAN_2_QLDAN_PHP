<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('CauHinhHeThong', function (Blueprint $table) {
    $table->id();
    $table->dateTime('ThoiGianMoDangKy')->nullable();
    $table->dateTime('ThoiGianDongDangKy')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cauhinh_hethong');
    }
};