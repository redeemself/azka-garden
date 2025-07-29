<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('stats_types');

        Schema::create('stats_types', function (Blueprint $table) {
            // PK bernama stats_type_id
            $table->id('stats_type_id');
            $table->string('code', 50)->unique();
            $table->string('description', 255)->nullable();
            // Hapus foreignId('enum_stats_type_id') karena DDL tidak punya kolom ini

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats_types');
    }
};
