<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('performances');

        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name', 50);
            $table->decimal('value', 10, 2);
            $table->string('unit', 10)->nullable();
            $table->dateTime('timestamp');

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
