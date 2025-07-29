<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('faq');

        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->nullable();
            $table->string('question', 150)->nullable();
            $table->text('answer')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('order')->nullable(); // GANTI dari urutan ke order
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
