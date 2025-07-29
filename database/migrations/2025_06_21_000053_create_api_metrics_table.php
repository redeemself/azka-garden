<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('api_metrics');

        Schema::create('api_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endpoint_id')
                  ->constrained('api_endpoints')
                  ->onDelete('cascade');
            $table->dateTime('timestamp');
            $table->integer('response_time');
            $table->integer('status_code');
            $table->decimal('error_rate', 5, 2)->nullable();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_metrics');
    }
};
