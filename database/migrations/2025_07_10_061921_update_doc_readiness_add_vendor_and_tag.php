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
        $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
        $table->string('tag_number')->nullable()->after('document_title');

        // Drop yang tidak perlu
        $table->dropColumn('vendor_name');
        $table->dropColumn('title');
    });
}

public function down()
{
    Schema::table('doc_readiness', function (Blueprint $table) {
        $table->dropColumn(['vendor_id', 'tag_number']);

        $table->string('vendor_name')->nullable();
        $table->string('title')->nullable();
    });
}

};
