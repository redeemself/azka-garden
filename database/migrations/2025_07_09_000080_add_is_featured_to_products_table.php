<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom hanya jika belum ada
        if (! Schema::hasColumn('products', 'is_featured')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_featured')
                      ->default(false)
                      ->after('price');
            });
        }
    }

    public function down(): void
    {
        // Hapus kolom hanya jika memang ada
        if (Schema::hasColumn('products', 'is_featured')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }
};
