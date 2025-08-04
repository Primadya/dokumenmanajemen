<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            $table->string('vendor_name')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('transmital_details', function (Blueprint $table) {
            $table->dropColumn('vendor_name');
        });
    }
};

