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
    Schema::table('doc_readiness', function (Blueprint $table) {
        $table->string('discipline')->nullable()->after('fase');
    });
}

public function down()
{
    Schema::table('doc_readiness', function (Blueprint $table) {
        $table->dropColumn('discipline');
    });
}

};
