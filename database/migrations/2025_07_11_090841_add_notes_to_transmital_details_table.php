<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesToTransmitalDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('revision');
        });
    }

    public function down()
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}
