<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel product_likes dihapus terlebih dahulu untuk menghindari error constraint
        if (Schema::hasTable('product_likes')) {
            Schema::drop('product_likes');
        }

        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel categories, dengan pembatasan delete (restrict)
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict');

            $table->string('name', 150);
            $table->text('description')->nullable();

            // Stok produk
            $table->integer('stock')->default(0);

            // Harga produk, dengan presisi 12 digit total, 2 desimal
            $table->decimal('price', 12, 2);

            // Berat produk, misal kilogram atau gram
            $table->decimal('weight', 8, 2);

            // URL atau path gambar produk, nullable
            $table->string('image_url')->nullable();

            // Status aktif/tidaknya produk (default aktif)
            $table->boolean('status')->default(true);

            // Foreign key ke tabel interfaces, default 1, pastikan interfaces sudah ada
            $table->foreignId('interface_id')
                  ->default(1)
                  ->constrained('interfaces');

            // Tambahkan kolom is_featured untuk menandai produk unggulan (boolean default false)
            $table->boolean('is_featured')->default(false);

            // Timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus tabel product_likes terlebih dahulu jika ada, agar tidak error constraint
        if (Schema::hasTable('product_likes')) {
            Schema::drop('product_likes');
        }

        Schema::dropIfExists('products');
    }
};
