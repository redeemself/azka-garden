<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('deployments');

        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20);
            $table->dateTime('date');
            $table->text('notes')->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
