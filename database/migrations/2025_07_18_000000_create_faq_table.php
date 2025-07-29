<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaikan: cek dulu jika tabel belum ada, baru create.
        if (!Schema::hasTable('faq')) {
            Schema::create('faq', function (Blueprint $table) {
                $table->id();
                $table->string('category', 50)->nullable();
                $table->string('question', 150)->nullable();
                $table->text('answer')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('order')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->bigInteger('interface_id')->unsigned()->default(8);
            });
        }
        // Jika sudah ada, migrasi tidak akan error dan tidak akan mengubah tabel yang sudah ada
    }

    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
