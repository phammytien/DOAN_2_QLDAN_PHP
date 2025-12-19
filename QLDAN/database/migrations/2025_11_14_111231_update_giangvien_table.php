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
    Schema::table('GiangVien', function (Blueprint $table) {

        // Đổi tên MaKH -> MaKhoa (nếu tồn tại)
        if (Schema::hasColumn('GiangVien', 'MaKH')) {
            $table->renameColumn('MaKH', 'MaKhoa');
        }

        // Thêm MaNganh, MaNamHoc nếu chưa có
        if (!Schema::hasColumn('GiangVien', 'MaNganh')) {
            $table->unsignedBigInteger('MaNganh')->nullable();
        }

        if (!Schema::hasColumn('GiangVien', 'MaNamHoc')) {
            $table->unsignedBigInteger('MaNamHoc')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('GiangVien', function (Blueprint $table) {

        if (Schema::hasColumn('GiangVien', 'MaKhoa')) {
            $table->renameColumn('MaKhoa', 'MaKH');
        }

        if (Schema::hasColumn('GiangVien', 'MaNganh')) {
            $table->dropColumn('MaNganh');
        }

        if (Schema::hasColumn('GiangVien', 'MaNamHoc')) {
            $table->dropColumn('MaNamHoc');
        }
    });
}

};