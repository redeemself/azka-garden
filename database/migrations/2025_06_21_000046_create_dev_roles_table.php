<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dev_roles');

        Schema::create('dev_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enum_dev_role_id')
                  ->constrained('enum_dev_role')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dev_roles');
    }
};