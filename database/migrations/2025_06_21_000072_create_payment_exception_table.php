<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('payment_exception');

        Schema::create('payment_exception', function (Blueprint $table) {
            $table->string('message', 255);
            $table->integer('errorCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_exception');
    }
};
