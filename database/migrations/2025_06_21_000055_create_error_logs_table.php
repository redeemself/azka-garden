<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('error_logs');

        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level', 50);
            $table->text('message');
            $table->text('stack_trace')->nullable();
            $table->string('source', 50)->nullable();
            $table->dateTime('timestamp');

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
