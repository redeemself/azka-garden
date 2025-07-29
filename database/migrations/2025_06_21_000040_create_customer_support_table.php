<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('customer_support');

        Schema::create('customer_support', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('ticket_number', 50)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('subject', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('priority', 20)->nullable();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_support');
    }
};
