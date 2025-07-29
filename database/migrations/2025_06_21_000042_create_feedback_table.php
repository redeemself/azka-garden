<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('feedback');

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('jenis', 50)->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->string('status', 50)->nullable();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
