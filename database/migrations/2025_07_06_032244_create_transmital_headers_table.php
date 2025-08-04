<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transmital_headers', function (Blueprint $table) {
            $table->id();
            $table->string('transmittal_number'); // wajib diisi dari controller
            $table->foreignId('po_list_id')->nullable()->constrained('po_list')->onDelete('cascade'); // ✅ Ubah jadi nullable
            $table->date('transmittal_date');
            $table->string('target')->nullable();
            $table->string('from_department');
            $table->string('to_department');
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transmital_headers');
    }
};
