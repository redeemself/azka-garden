<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('enum_order_status');

        Schema::create('enum_order_status', function (Blueprint $table) {
            $table->id();
            $table->string('value', 20)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enum_order_status');
    }
};
