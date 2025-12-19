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
    if (!Schema::hasColumn('ChamDiem', 'TrangThai') ||
        !Schema::hasColumn('ChamDiem', 'DiemCuoi')) {

        Schema::table('ChamDiem', function (Blueprint $table) {

            if (!Schema::hasColumn('ChamDiem', 'TrangThai')) {
                $table->string('TrangThai')->default('Chờ duyệt')->after('NhanXet');
            }

            if (!Schema::hasColumn('ChamDiem', 'DiemCuoi')) {
                $table->decimal('DiemCuoi', 4, 2)->nullable()->after('TrangThai');
            }

        });
    }
}

public function down()
{
    Schema::table('ChamDiem', function (Blueprint $table) {

        if (Schema::hasColumn('ChamDiem', 'TrangThai')) {
            $table->dropColumn('TrangThai');
        }

        if (Schema::hasColumn('ChamDiem', 'DiemCuoi')) {
            $table->dropColumn('DiemCuoi');
        }

    });
}

};