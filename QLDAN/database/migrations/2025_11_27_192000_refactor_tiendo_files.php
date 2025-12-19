<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('TienDo', function (Blueprint $table) {
            // Add new foreign keys only if they don't exist
            if (!Schema::hasColumn('TienDo', 'FileID')) {
                $table->unsignedBigInteger('FileID')->nullable();
            }
            if (!Schema::hasColumn('TienDo', 'FileCodeID')) {
                $table->unsignedBigInteger('FileCodeID')->nullable();
            }

            // Drop old columns if they exist
            if (Schema::hasColumn('TienDo', 'TenFile')) {
                $table->dropColumn(['TenFile', 'LinkFile']);
            }
        });
    }

    public function down()
    {
        Schema::table('TienDo', function (Blueprint $table) {
            $table->string('TenFile')->nullable();
            $table->string('LinkFile')->nullable();

            $table->dropColumn(['FileID', 'FileCodeID']);
        });
    }
};
