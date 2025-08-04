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
    Schema::create('eps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
        $table->string('title');
        $table->date('execution_date');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eps');
    }
};
