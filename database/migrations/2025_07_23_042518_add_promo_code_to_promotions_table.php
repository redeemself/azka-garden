<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoCodeToPromotionsTable extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            if (!Schema::hasColumn('promotions', 'promo_code')) {
                $table->string('promo_code', 50)->unique()->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            if (Schema::hasColumn('promotions', 'promo_code')) {
                $table->dropColumn('promo_code');
            }
        });
    }
}
