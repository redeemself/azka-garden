<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom order_code jika belum ada
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code', 30)->after('user_id')->unique()->comment('Unique order code identifier');
            }
        });

        // Tambahkan unique index jika belum ada
        // MySQL: cek lewat information_schema
        $uniqueExists = DB::select("
            SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
            WHERE table_schema = DATABASE()
            AND table_name = 'orders'
            AND index_name = 'orders_order_code_unique'
        ")[0]->cnt ?? 0;

        if ($uniqueExists == 0) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unique('order_code');
            });
        }

        // Tambahkan kolom payment_method jika belum ada
        if (!Schema::hasColumn('orders', 'payment_method')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_method', 30)->nullable()->after('note')->comment('Payment method code used for this order');
            });
        }

        // Index tambahan (tidak wajib rollback)
        if (Schema::hasColumn('orders', 'enum_order_status_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['user_id', 'enum_order_status_id'], 'idx_orders_user_status');
                $table->index(['order_date'], 'idx_orders_date');
                $table->index(['enum_order_status_id'], 'idx_orders_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop unique constraint jika memang ada
        $uniqueExists = DB::select("
            SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
            WHERE table_schema = DATABASE()
            AND table_name = 'orders'
            AND index_name = 'orders_order_code_unique'
        ")[0]->cnt ?? 0;

        if ($uniqueExists > 0) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropUnique('orders_order_code_unique');
            });
        }

        // Drop index tambahan jika ada
        if (Schema::hasColumn('orders', 'enum_order_status_id')) {
            Schema::table('orders', function (Blueprint $table) {
                try { $table->dropIndex('idx_orders_user_status'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_orders_date'); } catch (\Exception $e) {}
                try { $table->dropIndex('idx_orders_status'); } catch (\Exception $e) {}
            });
        }

        // Kolom tidak wajib dihapus (jika tidak ingin data hilang)
        // Jika ingin benar-benar drop kolom, uncomment:
        // if (Schema::hasColumn('orders', 'order_code')) {
        //     Schema::table('orders', function (Blueprint $table) {
        //         $table->dropColumn('order_code');
        //     });
        // }
        // if (Schema::hasColumn('orders', 'payment_method')) {
        //     Schema::table('orders', function (Blueprint $table) {
        //         $table->dropColumn('payment_method');
        //     });
        // }
    }
}