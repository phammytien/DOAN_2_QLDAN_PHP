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
        Schema::table('ChamDiem', function (Blueprint $table) {
            $table->string('MaSV')->after('MaDeTai'); // thêm cột MaSV
        });
    }

    public function down(): void
    {
        Schema::table('ChamDiem', function (Blueprint $table) {
            $table->dropColumn('MaSV'); // rollback nếu cần
        });
    }
};