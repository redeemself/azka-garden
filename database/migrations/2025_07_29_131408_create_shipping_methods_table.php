<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration untuk tabel master shipping methods
     * Created: 2025-07-29 13:14:08 by mulyadafa
     */
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode metode pengiriman');
            $table->string('name', 100)->comment('Nama metode pengiriman');
            $table->string('service', 50)->comment('Jenis layanan');
            $table->decimal('cost', 12, 2)->default(0)->comment('Biaya pengiriman default');
            $table->text('description')->nullable()->comment('Deskripsi metode pengiriman');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->integer('sort')->default(0)->comment('Urutan tampilan');
            $table->date('start_date')->nullable()->comment('Tanggal mulai aktif');
            $table->date('end_date')->nullable()->comment('Tanggal berakhir aktif');
            $table->json('settings')->nullable()->comment('Pengaturan tambahan dalam JSON');
            $table->timestamps();

            $table->index(['is_active', 'sort']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
