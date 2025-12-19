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
            if (!Schema::hasColumn('GiangVien', 'HinhAnh')) {
                $table->string('HinhAnh')->nullable()->after('Email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('GiangVien', function (Blueprint $table) {
            $table->dropColumn('HinhAnh');
        });
    }
};
