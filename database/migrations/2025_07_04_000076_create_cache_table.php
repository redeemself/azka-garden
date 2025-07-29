<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cache');

        Schema::create('cache', function (Blueprint $table) {
            $table->string('cache_key')->primary();
            $table->mediumText('cache_value');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
        });

        Schema::dropIfExists('cache_locks');

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('lock_key')->primary();
            $table->string('locked_by', 50);
            $table->timestamp('locked_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};
