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
            Schema::table('transmital_headers', function (Blueprint $table) {
                $table->string('status', 20)->default('Pending')->change();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transmital_headers', function (Blueprint $table) {
            //
        });
    }
};
