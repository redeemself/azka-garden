<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('database_backups');

        Schema::create('database_backups', function (Blueprint $table) {
            $table->id();
            $table->string('db_name', 50);
            $table->string('backup_type', 20)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->bigInteger('size')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_backups');
    }
};
