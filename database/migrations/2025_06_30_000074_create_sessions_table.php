<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sessions');

        Schema::create('sessions', function (Blueprint $table) {
            // Sesuai DDL: INT AUTO_INCREMENT PK
            $table->id('session_id');

            // FK ke users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Token sesuai DDL
            $table->string('token', 255);

            $table->dateTime('created_at');
            $table->dateTime('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
