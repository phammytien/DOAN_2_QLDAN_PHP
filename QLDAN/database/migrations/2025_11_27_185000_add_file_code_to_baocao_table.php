<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->string('TenFileCode')->nullable();
            $table->string('LinkFileCode')->nullable();
        });
    }

    public function down()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->dropColumn(['TenFileCode', 'LinkFileCode']);
        });
    }
};
