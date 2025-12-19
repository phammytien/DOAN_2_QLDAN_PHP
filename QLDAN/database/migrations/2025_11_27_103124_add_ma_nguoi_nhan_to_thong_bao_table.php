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
        Schema::table('ThongBao', function (Blueprint $table) {
            if (!Schema::hasColumn('ThongBao', 'MaNguoiNhan')) {
                $table->string('MaNguoiNhan', 20)->nullable()->after('DoiTuongNhan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ThongBao', function (Blueprint $table) {
            $table->dropColumn('MaNguoiNhan');
        });
    }
};
