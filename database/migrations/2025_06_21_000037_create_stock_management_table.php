<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('stock_management');

        Schema::create('stock_management', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('restrict');

            $table->integer('quantity');
            $table->string('type', 50)->nullable();
            $table->string('notes', 150)->nullable();

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_management');
    }
};
