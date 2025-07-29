<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToPaymentMethodsTable extends Migration
{
    /**
     * Jalankan migrasi: tambahkan kolom description ke tabel payment_methods
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
        });
    }

    /**
     * Rollback migrasi: hapus kolom description jika perlu
     */
    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}