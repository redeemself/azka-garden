<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('newsletters');

        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 100)->nullable();
            $table->text('content')->nullable();
            $table->string('recipient_type', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
