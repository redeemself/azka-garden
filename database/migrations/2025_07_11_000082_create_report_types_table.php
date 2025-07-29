<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('report_types');

        Schema::create('report_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enum_report_type_id')
                  ->constrained('enum_report_type')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_types');
    }
};
