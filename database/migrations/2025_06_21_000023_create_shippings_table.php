<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('shippings');

        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            $table->string('courier', 50);
            $table->string('service', 50);
            $table->string('tracking_number', 50)->nullable();
            $table->decimal('shipping_cost', 12, 2);
            $table->string('status', 50);
            $table->date('estimated_delivery')->nullable();

            // ← perbaikan di sini:
            $table->foreignId('interface_id')
                  ->default(1)
                  ->constrained('interfaces')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
