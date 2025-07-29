<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            if (!Schema::hasColumn('promotions', 'promo_code')) {
                $table->string('promo_code')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('promotions', 'discount_type')) {
                $table->string('discount_type')->nullable()->after('promo_code');
            }
            if (!Schema::hasColumn('promotions', 'discount_value')) {
                $table->float('discount_value')->nullable()->after('discount_type');
            }
            if (!Schema::hasColumn('promotions', 'status')) {
                $table->boolean('status')->default(1)->after('discount_value');
            }
            if (!Schema::hasColumn('promotions', 'start_date')) {
                $table->datetime('start_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('promotions', 'end_date')) {
                $table->datetime('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            foreach(['promo_code','discount_type','discount_value','status','start_date','end_date'] as $col) {
                if (Schema::hasColumn('promotions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
