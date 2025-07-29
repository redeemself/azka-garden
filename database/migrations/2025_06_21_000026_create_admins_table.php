<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('admins');

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->char('password', 60);
            $table->string('name', 100); // pakai "name" untuk konsisten dengan model
            $table->string('email', 100)->unique();
            $table->foreignId('role_id')->constrained('admin_roles')->onDelete('restrict');
            $table->foreignId('status_id')->constrained('admin_statuses')->onDelete('restrict');
            $table->dateTime('last_login')->nullable();
            $table->foreignId('interface_id')->default(8)->constrained('interfaces');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
