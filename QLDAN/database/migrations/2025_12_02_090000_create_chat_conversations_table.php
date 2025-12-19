<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('MaSV', 50);
            $table->string('MaGV', 50);

            // FIX: Kiểu MaDeTai phải khớp bảng DeTai (INT thường, không unsigned)
            $table->integer('MaDeTai')->nullable();

            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('MaSV')
                ->references('MaSV')->on('SinhVien')
                ->onDelete('cascade');

            $table->foreign('MaGV')
                ->references('MaGV')->on('GiangVien')
                ->onDelete('cascade');

            $table->foreign('MaDeTai')
                ->references('MaDeTai')->on('DeTai')
                ->onDelete('set null');

            // Indexes
            $table->index('MaSV');
            $table->index('MaGV');
            $table->index('MaDeTai');
            $table->index('last_message_at');

            // Chỉ tạo 1 phòng chat giữa 1 SV và 1 GV
            $table->unique(['MaSV', 'MaGV']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
