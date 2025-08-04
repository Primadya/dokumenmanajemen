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
    Schema::create('transmital_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transmital_header_id')->constrained('transmital_headers')->onDelete('cascade');
        $table->string('document_id')->nullable();
        $table->string('document_number');
        $table->string('title');
        $table->string('revision');
        $table->boolean('is_sent')->default(false);
        $table->boolean('is_read')->default(false);
        $table->string('status')->nullable();
        $table->string('document_path')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transmital_details');
    }
};
