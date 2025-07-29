<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dispute_management');

        Schema::create('dispute_management', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('type', 100);
            $table->text('description')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('resolution', 255)->nullable();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispute_management');
    }
};
