<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('addresses');

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('label', 20);
            $table->string('recipient', 50);
            $table->string('phone_number', 20);
            $table->string('full_address', 200);
            $table->string('city', 50);
            $table->string('zip_code', 10);
            $table->boolean('is_primary')->default(false);
            $table->foreignId('interface_id')
                  ->default(1)
                  ->constrained('interfaces');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
