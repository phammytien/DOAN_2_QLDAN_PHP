<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIslateToBaocao extends Migration
{
    public function up()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->boolean('isLate')->default(false)->after('TrangThai');
        });
    }

    public function down()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->dropColumn('isLate');
        });
    }
}