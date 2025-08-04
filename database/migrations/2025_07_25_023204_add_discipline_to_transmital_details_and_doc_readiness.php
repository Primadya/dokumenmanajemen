<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek kalau kolom discipline belum ada di transmital_details
        if (!Schema::hasColumn('transmital_details', 'discipline')) {
            Schema::table('transmital_details', function (Blueprint $table) {
                $table->string('discipline')->nullable()->after('status'); // sesuaikan posisi kolom jika mau
            });
        }
    }

    public function down()
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            if (Schema::hasColumn('transmital_details', 'discipline')) {
                $table->dropColumn('discipline');
            }
        });
    }
};
