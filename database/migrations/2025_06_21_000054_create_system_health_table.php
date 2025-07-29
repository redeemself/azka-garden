<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('system_health');

        Schema::create('system_health', function (Blueprint $table) {
            $table->id();
            $table->string('component', 50);
            $table->string('status', 50);
            $table->decimal('cpu_usage', 5, 2)->nullable();
            $table->decimal('memory_usage', 5, 2)->nullable();
            $table->decimal('disk_usage', 5, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_health');
    }
};
