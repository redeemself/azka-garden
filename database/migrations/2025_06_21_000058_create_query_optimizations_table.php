<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('query_optimizations');

        Schema::create('query_optimizations', function (Blueprint $table) {
            $table->id();
            $table->text('query_text');
            $table->integer('execution_time');
            $table->text('suggested_optimization')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_optimizations');
    }
};
