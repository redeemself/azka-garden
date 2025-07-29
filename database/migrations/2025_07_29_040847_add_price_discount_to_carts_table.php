<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambahkan kolom 'discount' setelah 'promo_code' jika belum ada
            if (!Schema::hasColumn('carts', 'discount')) {
                $table->integer('discount')->default(0)->after('promo_code');
            }
            // Tambahkan kolom 'price' setelah 'discount' jika belum ada
            if (!Schema::hasColumn('carts', 'price')) {
                $table->integer('price')->default(0)->after('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Hapus kolom jika ada
            if (Schema::hasColumn('carts', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('carts', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
};