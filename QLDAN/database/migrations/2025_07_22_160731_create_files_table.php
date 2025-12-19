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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('path')->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('size')->nullable();
            $table->string('extension', 10)->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            // Tạo index cho các cột thường được query
            $table->index('type');
            $table->index('extension');
            $table->index('is_deleted');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
