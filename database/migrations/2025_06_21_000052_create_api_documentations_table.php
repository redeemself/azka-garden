<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('api_documentations');

        Schema::create('api_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endpoint_id')
                  ->constrained('api_endpoints')
                  ->onDelete('cascade');
            $table->string('version', 10)->nullable();
            $table->text('content')->nullable();
            $table->json('examples')->nullable();
            $table->foreignId('updated_by')
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
        Schema::dropIfExists('api_documentations');
    }
};
