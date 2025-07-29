<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('developer_permissions');

        Schema::create('developer_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')
                  ->constrained('developers')
                  ->onDelete('cascade');
            $table->string('module', 100);
            $table->boolean('can_view')->default(false);
            $table->boolean('can_commit')->default(false);
            $table->boolean('can_merge')->default(false);
            $table->boolean('can_deploy')->default(false);
            $table->timestamps();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_permissions');
    }
};
