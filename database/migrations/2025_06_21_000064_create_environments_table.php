<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('environments');

        Schema::create('environments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('url', 255)->nullable();
            $table->string('type', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environments');
    }
};
