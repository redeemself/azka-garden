<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('resource_not_found_exception');

        Schema::create('resource_not_found_exception', function (Blueprint $table) {
            $table->string('message', 255);
            $table->integer('errorCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_not_found_exception');
    }
};
