<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('security_audits');

        Schema::create('security_audits', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->text('description')->nullable();
            $table->string('severity', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->text('findings')->nullable();
            $table->foreignId('developer_id')
                  ->nullable()
                  ->constrained('developers')
                  ->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_audits');
    }
};
