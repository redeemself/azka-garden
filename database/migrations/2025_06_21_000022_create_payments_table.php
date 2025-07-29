<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('payments');

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade')
                  ->unique();
            $table->foreignId('method_id')
                  ->constrained('payment_methods')
                  ->onDelete('restrict');
            $table->string('transaction_code', 50);
            $table->string('bank_account', 100)->nullable();
            $table->decimal('total', 14, 2);
            $table->foreignId('enum_payment_status_id')
                  ->constrained('enum_payment_status');
            $table->string('proof_of_payment', 255)->nullable();
            $table->dateTime('expired_at')->nullable();

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
        Schema::dropIfExists('payments');
    }
};
