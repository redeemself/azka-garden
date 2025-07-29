<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanCanceledExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:clean-canceled-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus pesanan yang dibatalkan dan kadaluarsa beserta data relasinya';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Ambil ID status pesanan CANCELED dan EXPIRED dari enum_order_status
        $statusIds = DB::table('enum_order_status')
            ->whereIn('value', ['CANCELED', 'EXPIRED'])
            ->pluck('id')
            ->toArray();

        // Dapatkan ID semua orders yang akan dihapus
        $orderIds = DB::table('orders')
            ->whereIn('enum_order_status_id', $statusIds)
            ->pluck('id')
            ->toArray();

        // Hapus relasi pada tabel terkait (order_details, payments, shippings, order_management, refund_management, dispute_management, dst)
        DB::table('order_details')->whereIn('order_id', $orderIds)->delete();
        DB::table('payments')->whereIn('order_id', $orderIds)->delete();
        DB::table('shippings')->whereIn('order_id', $orderIds)->delete();
        DB::table('order_management')->whereIn('order_id', $orderIds)->delete();
        DB::table('refund_management')->whereIn('order_id', $orderIds)->delete();
        DB::table('dispute_management')->whereIn('order_id', $orderIds)->delete();

        // Hapus orders status CANCELED & EXPIRED
        DB::table('orders')->whereIn('enum_order_status_id', $statusIds)->delete();

        // Hapus expired_orders jika ada status CANCELED atau EXPIRED
        DB::table('expired_orders')->whereIn('enum_order_status_id', $statusIds)->delete();

        $this->info('Pesanan dibatalkan & kadaluarsa beserta data relasinya berhasil dibersihkan.');

        return 0;
    }
}