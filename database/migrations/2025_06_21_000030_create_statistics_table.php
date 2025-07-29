<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('statistics');

        Schema::create('statistics', function (Blueprint $table) {
            $table->id();

            // Ganti kolom FK menjadi enum_stats_type_id → enum_stats_type(id)
            $table->foreignId('enum_stats_type_id')
                  ->constrained('enum_stats_type')
                  ->onDelete('restrict');

            $table->string('period', 50)->nullable();
            $table->json('data')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
