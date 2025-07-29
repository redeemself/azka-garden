<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(); // Ubah menjadi nullable
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->text('message');
            $table->string('promo_code', 32)->nullable(); // Tambahkan field promo_code
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
