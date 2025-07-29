<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dev_statuses');

        Schema::create('dev_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enum_dev_status_id')
                  ->constrained('enum_dev_status')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dev_statuses');
    }
};
