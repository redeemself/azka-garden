<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('order_management');

        Schema::create('order_management', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('restrict');

            $table->foreignId('admin_id')
                  ->constrained('admins')
                  ->onDelete('restrict');

            $table->string('action', 100);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_management');
    }
};
