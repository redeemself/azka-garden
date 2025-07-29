<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus foreign key constraint dari tabel reports
        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                // Pastikan nama FK tepat, sesuaikan 'fk_reports_interface' jika berbeda
                $table->dropForeign('fk_reports_interface');
                // Jika nama FK tidak diketahui, bisa pakai:
                // $table->dropForeign(['interface_id']);
            });
        }

        // Drop tabel interfaces jika ada
        Schema::dropIfExists('interfaces');

        // Buat tabel interfaces
        Schema::create('interfaces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus foreign key constraint di reports terlebih dahulu
        if (Schema::hasTable('reports')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropForeign('fk_reports_interface');
            });
        }

        Schema::dropIfExists('interfaces');
    }
};
