<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('enum_dev_role');

        Schema::create('enum_dev_role', function (Blueprint $table) {
            $table->id();
            $table->string('value', 30)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enum_dev_role');
    }
};