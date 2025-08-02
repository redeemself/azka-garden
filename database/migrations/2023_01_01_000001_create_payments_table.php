<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('method_id');
                $table->string('transaction_code')->unique();
                $table->decimal('total', 10, 2);
                $table->unsignedBigInteger('enum_payment_status_id');
                $table->string('proof_of_payment')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('method_id')->references('id')->on('payment_methods')->onDelete('cascade');
                $table->foreign('enum_payment_status_id')->references('id')->on('enum_payment_statuses');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
