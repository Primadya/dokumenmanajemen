<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('po_list', function (Blueprint $table) {
        $table->id();
        $table->foreignId('eps_id')->constrained('eps')->onDelete('cascade');
        $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
        $table->string('po_number');
        $table->date('po_date');
        $table->date('po_eta');
        $table->string('material_code');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_list');
    }
};
