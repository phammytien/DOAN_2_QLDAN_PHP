<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('TienDo', function (Blueprint $table) {
            if (!Schema::hasColumn('TienDo', 'LinkFile')) {
                $table->string('LinkFile', 500)->nullable();
            }
            if (!Schema::hasColumn('TienDo', 'TenFile')) {
                $table->string('TenFile', 255)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('TienDo', function (Blueprint $table) {
            $table->dropColumn(['LinkFile', 'TenFile']);
        });
    }
};
