<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('admin_roles');

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enum_admin_role_id')
                  ->constrained('enum_admin_role')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
};
