<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @updated 2025-07-30 05:14:57 by mulyadafa
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
            // Check if indexes already exist before creating them
            $userStatusIndexExists = DB::select("
                SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                WHERE table_schema = DATABASE()
                AND table_name = 'orders'
                AND index_name = 'idx_orders_user_status'
            ")[0]->cnt ?? 0;

            $dateIndexExists = DB::select("
                SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                WHERE table_schema = DATABASE()
                AND table_name = 'orders'
                AND index_name = 'idx_orders_date'
            ")[0]->cnt ?? 0;

            $statusIndexExists = DB::select("
                SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                WHERE table_schema = DATABASE()
                AND table_name = 'orders'
                AND index_name = 'idx_orders_status'
            ")[0]->cnt ?? 0;

            Schema::table('orders', function (Blueprint $table) use ($userStatusIndexExists, $dateIndexExists, $statusIndexExists) {
                if ($userStatusIndexExists == 0) {
                    $table->index(['user_id', 'enum_order_status_id'], 'idx_orders_user_status');
                }
                if ($dateIndexExists == 0) {
                    $table->index(['order_date'], 'idx_orders_date');
                }
                if ($statusIndexExists == 0) {
                    $table->index(['enum_order_status_id'], 'idx_orders_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // First check if any foreign keys reference these indexes
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
            AND REFERENCED_TABLE_NAME = 'orders'
            AND REFERENCED_COLUMN_NAME IN ('user_id', 'enum_order_status_id')
        ");

        // Drop the foreign keys first if they exist
        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                Schema::table('orders', function (Blueprint $table) use ($fk) {
                    try {
                        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'orders DROP FOREIGN KEY ' . $fk->CONSTRAINT_NAME);
                    } catch (\Exception $e) {
                        // Log the error and continue
                        \Log::warning('Could not drop foreign key: ' . $fk->CONSTRAINT_NAME . '. Error: ' . $e->getMessage());
                    }
                });
            }
        }

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

        // Drop index tambahan jika ada - with improved error handling
        if (Schema::hasColumn('orders', 'enum_order_status_id')) {
            // Check which indexes actually exist
            $indexes = [
                'idx_orders_user_status' => DB::select("
                    SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                    WHERE table_schema = DATABASE()
                    AND table_name = 'orders'
                    AND index_name = 'idx_orders_user_status'
                ")[0]->cnt ?? 0,

                'idx_orders_date' => DB::select("
                    SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                    WHERE table_schema = DATABASE()
                    AND table_name = 'orders'
                    AND index_name = 'idx_orders_date'
                ")[0]->cnt ?? 0,

                'idx_orders_status' => DB::select("
                    SELECT COUNT(1) as cnt FROM information_schema.STATISTICS
                    WHERE table_schema = DATABASE()
                    AND table_name = 'orders'
                    AND index_name = 'idx_orders_status'
                ")[0]->cnt ?? 0
            ];

            // Drop only indexes that exist
            foreach ($indexes as $indexName => $exists) {
                if ($exists > 0) {
                    try {
                        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'orders DROP INDEX ' . $indexName);
                    } catch (\Exception $e) {
                        // Log the error and continue
                        \Log::warning('Could not drop index: ' . $indexName . '. Error: ' . $e->getMessage());
                    }
                }
            }
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
