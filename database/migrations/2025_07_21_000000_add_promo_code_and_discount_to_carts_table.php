<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoCodeAndDiscountToCartsTable extends Migration
{
    public function up()
    {
        // Pastikan tidak error jika kolom sudah ada
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'promo_code')) {
                $table->string('promo_code', 50)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('carts', 'discount')) {
                $table->integer('discount')->default(0)->after('promo_code');
            }
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'promo_code')) {
                $table->dropColumn('promo_code');
            }
            if (Schema::hasColumn('carts', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
}
