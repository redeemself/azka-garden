<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Updated: 2025-08-02 01:06:12
     * By: gerrymulyadi709
     * Fix: Added check to prevent "table already exists" error
     */
    public function up(): void
    {
        if (!Schema::hasTable('product_likes')) {
            Schema::create('product_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Prevent duplicate likes
                $table->unique(['user_id', 'product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_likes');
    }
};
