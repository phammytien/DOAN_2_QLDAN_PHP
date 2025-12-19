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
    Schema::table('ChamDiem', function (Blueprint $table) {
        $table->tinyInteger('TrangThai')->default(0); // 0=Chưa xác nhận
    });
}

public function down()
{
    Schema::table('ChamDiem', function (Blueprint $table) {
        $table->dropColumn('TrangThai');
    });
}

};