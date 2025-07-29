<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('orders');

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->string('order_code', 30)->unique();
            $table->dateTime('order_date');
            $table->foreignId('enum_order_status_id')
                  ->constrained('enum_order_status');
            $table->decimal('total_price', 14, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->text('note')->nullable();
            $table->foreignId('interface_id')
                  ->default(1)
                  ->constrained('interfaces');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
