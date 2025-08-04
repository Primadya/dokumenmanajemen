<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Hapus foreign key constraint
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->dropForeign(['po_list_id']);
        });

        // 2. Ubah kolom jadi nullable
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->unsignedBigInteger('po_list_id')->nullable()->change();
        });

        // 3. Tambahkan kembali foreign key constraint
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->foreign('po_list_id')
                ->references('id')
                ->on('po_list')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        // 1. Hapus foreign key constraint
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->dropForeign(['po_list_id']);
        });

        // 2. Pastikan tidak ada nilai NULL sebelum ubah ke NOT NULL
        DB::table('transmital_headers')->whereNull('po_list_id')->update(['po_list_id' => 1]);

        // 3. Ubah kolom jadi NOT NULL
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->unsignedBigInteger('po_list_id')->nullable(false)->change();
        });

        // 4. Tambahkan kembali foreign key constraint
        Schema::table('transmital_headers', function (Blueprint $table) {
            $table->foreign('po_list_id')
                ->references('id')
                ->on('po_list')
                ->onDelete('cascade');
        });
    }
};
