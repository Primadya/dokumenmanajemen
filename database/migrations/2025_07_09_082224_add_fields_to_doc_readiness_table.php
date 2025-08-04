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
        $table->string('vendor_name')->nullable();
        $table->string('document_title')->nullable();
        $table->string('transmittal_number')->nullable();
        $table->string('po_list_number')->nullable();
        $table->string('status')->default('Pending');
        $table->string('file_path')->nullable();
    });
}

public function down()
{
    Schema::table('doc_readiness', function (Blueprint $table) {
        $table->dropColumn([
            'vendor_name', 'document_title', 'transmittal_number',
            'po_list_number', 'status', 'file_path'
        ]);
    });
}
};
