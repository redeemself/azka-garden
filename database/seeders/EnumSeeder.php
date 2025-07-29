<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnumSeeder extends Seeder
{
    public function run()
    {
        $lookups = [
            'enum_roles'           => ['CUSTOMER', 'GUEST'],
            'enum_order_status'    => ['WAITING_PAYMENT', 'PROCESSING', 'SHIPPED', 'COMPLETED', 'CANCELED'],
            'enum_payment_status'  => ['PENDING', 'SUCCESS', 'FAILED', 'EXPIRED'],
            'enum_admin_role'      => ['SUPER_ADMIN', 'PRODUCT_ADMIN', 'ORDER_ADMIN', 'CUSTOMER_SERVICE', 'CONTENT_ADMIN', 'FINANCE_ADMIN'],
            'enum_admin_status'    => ['ACTIVE', 'INACTIVE', 'SUSPENDED', 'DELETED'],
            'enum_stats_type'      => ['SALES', 'ORDERS', 'CUSTOMERS', 'PRODUCTS', 'REVENUE', 'INVENTORY'],
            'enum_report_type'     => ['DAILY_SALES', 'MONTHLY_REVENUE', 'INVENTORY_STATUS', 'USER_ACTIVITY', 'ORDER_SUMMARY', 'FINANCE_STATEMENT'],
            'enum_dev_role'        => ['LEAD_DEVELOPER', 'BACKEND_DEVELOPER', 'FRONTEND_DEVELOPER', 'DATABASE_ADMIN', 'DEVOPS_ENGINEER', 'SECURITY_ENGINEER'],
            'enum_dev_status'      => ['ACTIVE', 'INACTIVE', 'ON_LEAVE', 'TERMINATED'],
        ];

        foreach ($lookups as $table => $values) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            // Cek apakah tabel memiliki kolom created_at & updated_at
            $hasTimestamps = Schema::hasColumn($table, 'created_at') && Schema::hasColumn($table, 'updated_at');

            foreach ($values as $value) {
                if ($hasTimestamps) {
                    DB::table($table)->updateOrInsert(
                        ['value' => $value],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                } else {
                    DB::table($table)->updateOrInsert(
                        ['value' => $value],
                        []
                    );
                }
            }
        }
    }
}
