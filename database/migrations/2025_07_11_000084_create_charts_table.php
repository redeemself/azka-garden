<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('charts');

        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
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
        Schema::dropIfExists('charts');
    }
};
