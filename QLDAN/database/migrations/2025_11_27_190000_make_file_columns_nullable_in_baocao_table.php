<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->string('TenFile')->nullable()->change();
            $table->string('LinkFile')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->string('TenFile')->nullable(false)->change();
            $table->string('LinkFile')->nullable(false)->change();
        });
    }
};
