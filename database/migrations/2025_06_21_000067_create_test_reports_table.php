<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('test_reports');

        Schema::create('test_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')
                  ->constrained('test_cases')
                  ->onDelete('cascade');
            $table->text('actual_result')->nullable();
            $table->string('status', 20)->nullable();
            $table->foreignId('executed_by')
                  ->nullable()
                  ->constrained('developers')
                  ->nullOnDelete();
            $table->dateTime('executed_at')->nullable();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_reports');
    }
};
