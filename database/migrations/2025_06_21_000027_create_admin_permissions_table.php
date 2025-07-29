<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('admin_permissions');

        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')
                  ->constrained('admins')
                  ->onDelete('cascade');
            $table->string('module', 100);
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->foreignId('interface_id')
                  ->default(8)
                  ->constrained('interfaces');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_permissions');
    }
};
