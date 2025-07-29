<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('refund_management');

        Schema::create('refund_management', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('restrict');

            $table->decimal('amount', 14, 2)->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('status', 50)->nullable();
            $table->foreignId('processed_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();
            $table->dateTime('processed_at')->nullable();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_management');
    }
};
