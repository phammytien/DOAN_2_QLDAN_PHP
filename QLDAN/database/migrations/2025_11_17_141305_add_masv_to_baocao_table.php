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
    Schema::table('baocao', function (Blueprint $table) {
        $table->string('MaSV', 20)->nullable()->after('MaDeTai');
    });
}

public function down()
{
    Schema::table('baocao', function (Blueprint $table) {
        $table->dropColumn('MaSV');
    });
}

};