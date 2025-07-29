<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpireOrders extends Command
{
    protected $signature = 'orders:expire';
    protected $description = 'Set all orders with expired payment time to expired status';

    public function handle()
    {
        $now = Carbon::now();

        // Get enum ids
        $expiredPaymentId = DB::table('enum_payment_status')->where('value', 'EXPIRED')->value('id');
        $pendingPaymentId = DB::table('enum_payment_status')->where('value', 'PENDING')->value('id');
        $canceledOrderId = DB::table('enum_order_status')->where('value', 'CANCELED')->value('id');
        // Jika ada status EXPIRED pada enum_order_status, gunakan yang ini:
        // $expiredOrderId = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');

        // Semua payment yang pending dan expired_at sudah lewat
        $expiredPayments = DB::table('payments')
            ->where('enum_payment_status_id', $pendingPaymentId)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', $now)
            ->get();

        $affected = 0;

        foreach ($expiredPayments as $payment) {
            // Update status payment jadi EXPIRED
            DB::table('payments')->where('id', $payment->id)
                ->update(['enum_payment_status_id' => $expiredPaymentId, 'updated_at' => $now]);

            // Update order status jadi CANCELED (atau EXPIRED jika ada enum-nya)
            DB::table('orders')->where('id', $payment->order_id)
                ->update(['enum_order_status_id' => $canceledOrderId, 'updated_at' => $now]);
            $affected++;
        }

        $this->info("$affected orders expired!");
    }
}