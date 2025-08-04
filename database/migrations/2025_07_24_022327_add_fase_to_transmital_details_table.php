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
    Schema::table('transmital_details', function (Blueprint $table) {
        $table->string('fase')->nullable()->after('tag_number');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            //
        });
    }
};
