<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('developers');

        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->char('password', 60);
            $table->string('email', 100);
            $table->foreignId('role_id')
                  ->constrained('dev_roles')
                  ->onDelete('restrict');
            $table->foreignId('status_id')
                  ->constrained('dev_statuses')
                  ->onDelete('restrict');
            $table->string('specialization', 50)->nullable();
            $table->string('github_profile', 255)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();

            $table->foreignId('interface_id')
                  ->default(11)
                  ->constrained('interfaces')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
