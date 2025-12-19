<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('GiangVien', function (Blueprint $table) {
            $table->timestamps(); // thêm created_at và updated_at
        });
    }

    public function down(): void
    {
        Schema::table('GiangVien', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};