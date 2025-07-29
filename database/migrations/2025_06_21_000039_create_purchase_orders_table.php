<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('purchase_orders');

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                  ->constrained('supplier_management')
                  ->onDelete('restrict');

            $table->string('status', 50)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->string('payment_status', 50)->nullable();
            $table->dateTime('delivery_date')->nullable();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
