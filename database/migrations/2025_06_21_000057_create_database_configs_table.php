<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('database_configs');

        Schema::create('database_configs', function (Blueprint $table) {
            $table->id();
            $table->string('db_name', 50);
            $table->string('host', 100);
            $table->integer('port');
            $table->string('username', 50);
            $table->string('password', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_configs');
    }
};
