<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            // Add new foreign keys only if they don't exist
            if (!Schema::hasColumn('BaoCao', 'FileID')) {
                $table->unsignedBigInteger('FileID')->nullable();
            }
            if (!Schema::hasColumn('BaoCao', 'FileCodeID')) {
                $table->unsignedBigInteger('FileCodeID')->nullable();
            }

            // Drop old columns if they exist
            if (Schema::hasColumn('BaoCao', 'TenFile')) {
                $table->dropColumn(['TenFile', 'LinkFile', 'TenFileCode', 'LinkFileCode']);
            }
        });
    }

    public function down()
    {
        Schema::table('BaoCao', function (Blueprint $table) {
            $table->string('TenFile')->nullable();
            $table->string('LinkFile')->nullable();
            $table->string('TenFileCode')->nullable();
            $table->string('LinkFileCode')->nullable();

            $table->dropColumn(['FileID', 'FileCodeID']);
        });
    }
};
