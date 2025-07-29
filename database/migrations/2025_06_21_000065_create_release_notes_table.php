<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('release_notes');

        Schema::create('release_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deployment_id')
                  ->constrained('deployments')
                  ->onDelete('cascade');
            $table->text('content')->nullable();
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('developers')
                  ->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('release_notes');
    }
};
