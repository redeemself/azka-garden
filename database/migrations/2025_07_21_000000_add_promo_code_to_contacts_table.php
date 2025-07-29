<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoCodeToContactsTable extends Migration
{
    public function up()
    {
        // Cek dulu apakah kolom sudah ada
        if (!Schema::hasColumn('contacts', 'promo_code')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('promo_code', 20)->nullable()->after('message');
            });
        }
    }

    public function down()
    {
        // Hapus kolom hanya jika sudah ada
        if (Schema::hasColumn('contacts', 'promo_code')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('promo_code');
            });
        }
    }
}
