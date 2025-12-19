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
        Schema::table('DeTai', function (Blueprint $table) {
            // Add MaKhoa column only if it doesn't exist
            if (!Schema::hasColumn('DeTai', 'MaKhoa')) {
                $table->string('MaKhoa', 10)->nullable()->after('LinhVuc');
            }
            
            // Add MaNganh column only if it doesn't exist
            if (!Schema::hasColumn('DeTai', 'MaNganh')) {
                $table->string('MaNganh', 10)->nullable()->after('MaKhoa');
            }
            
            // Add foreign key constraints if needed
            // $table->foreign('MaKhoa')->references('MaKhoa')->on('Khoa')->onDelete('set null');
            // $table->foreign('MaNganh')->references('MaNganh')->on('Nganh')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DeTai', function (Blueprint $table) {
            // Drop foreign keys first if they exist
            // $table->dropForeign(['MaKhoa']);
            // $table->dropForeign(['MaNganh']);
            
            // Drop columns
            $table->dropColumn(['MaKhoa', 'MaNganh']);
        });
    }
};
