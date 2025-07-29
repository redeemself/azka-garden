<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('interface_methods');

        Schema::create('interface_methods', function (Blueprint $table) {
            $table->id();
            // ON DELETE RESTRICT sesuai DDL
            $table->foreignId('interface_id')
                  ->constrained('interfaces')
                  ->onDelete('restrict');
            $table->string('method_name', 100);
            $table->string('return_type', 60);
            $table->string('description', 255)->nullable();
            // timestamps sesuai DDL
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interface_methods');
    }
};
