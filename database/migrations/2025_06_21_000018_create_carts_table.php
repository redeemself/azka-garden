<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika ingin migrasi baru, sebaiknya jangan drop dulu (kecuali memang ingin menghapus semua data lama)
        // Schema::dropIfExists('carts'); // Hapus jika ingin menambah kolom ke tabel yang sudah ada

        // Jika tabel sudah ada, cukup modify:
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');
                $table->foreignId('product_id')
                      ->constrained('products')
                      ->onDelete('cascade');
                $table->integer('quantity')->default(1); // Pastikan ada default!
                $table->text('note')->nullable();
                $table->foreignId('interface_id')
                      ->default(1)
                      ->constrained('interfaces');
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada, dan ingin menambah/mengubah kolom:
            Schema::table('carts', function (Blueprint $table) {
                if (!Schema::hasColumn('carts', 'quantity')) {
                    $table->integer('quantity')->default(1);
                }
                if (!Schema::hasColumn('carts', 'note')) {
                    $table->text('note')->nullable();
                }
                if (!Schema::hasColumn('carts', 'interface_id')) {
                    $table->foreignId('interface_id')
                          ->default(1)
                          ->constrained('interfaces');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
