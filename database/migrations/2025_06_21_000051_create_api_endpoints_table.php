<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('api_endpoints');

        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('path', 255);
            $table->string('method', 10);
            $table->string('version', 10)->nullable();
            $table->text('description')->nullable();
            $table->boolean('auth_required')->default(false);
            $table->integer('rate_limit')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_endpoints');
    }
};
