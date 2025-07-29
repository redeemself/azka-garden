<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada, buat baru dengan kolom lengkap
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');
                $table->foreignId('product_id')
                      ->constrained('products')
                      ->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->string('promo_code')->nullable();
                $table->integer('discount')->default(0);
                $table->integer('price')->default(0); // Kolom harga promo/final
                $table->text('note')->nullable();
                $table->foreignId('interface_id')
                      ->default(1)
                      ->constrained('interfaces');
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom jika belum ada
            Schema::table('carts', function (Blueprint $table) {
                if (!Schema::hasColumn('carts', 'promo_code')) {
                    $table->string('promo_code')->nullable();
                }
                if (!Schema::hasColumn('carts', 'discount')) {
                    $table->integer('discount')->default(0);
                }
                if (!Schema::hasColumn('carts', 'price')) {
                    $table->integer('price')->default(0);
                }
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