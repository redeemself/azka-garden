<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('security_configs');

        Schema::create('security_configs', function (Blueprint $table) {
            $table->id();
            $table->string('component', 50);
            $table->string('config_key', 50);
            $table->text('config_value')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_configs');
    }
};
