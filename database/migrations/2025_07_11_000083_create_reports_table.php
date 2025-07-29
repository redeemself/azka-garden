<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('reports');

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                  ->constrained('report_types')
                  ->onDelete('restrict');
            $table->string('title', 100)->nullable();
            $table->json('parameters')->nullable();
            $table->json('data')->nullable();
            $table->string('format', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
