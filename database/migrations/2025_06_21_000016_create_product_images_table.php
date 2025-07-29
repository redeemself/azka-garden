<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel interface dan products sudah ada sebelum migrasi ini dijalankan!

        // Hapus tabel product_images jika sudah ada (untuk migrasi ulang/fresh)
        Schema::dropIfExists('product_images');

        // Buat tabel product_images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel products
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            $table->string('image_url');

            $table->boolean('is_primary')->default(false);

            // Foreign key ke tabel interfaces (opsional, jika memang digunakan)
            $table->foreignId('interface_id')
                  ->default(1)
                  ->constrained('interfaces')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
