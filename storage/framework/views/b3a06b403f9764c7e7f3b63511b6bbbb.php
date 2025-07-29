

<?php $__env->startSection('title', 'Riwayat Pesanan Anda'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Copy-paste semua CSS dari daftar pesanan di atas, agar tampilan konsisten */
.orders-table-container {
  overflow-x: auto;
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 10px 28px rgba(56, 189, 248, 0.08), 0 2px 4px rgba(16, 185, 129, 0.02);
  margin-bottom: 2rem;
}
.orders-table { min-width: 100%; border-collapse: separate; border-spacing: 0; }
.orders-table th, .orders-table td { padding: 1rem 1rem; vertical-align: top; }
.orders-table thead th { background: #dcfce7; color: #166534; font-weight: bold; font-size: 0.95rem; letter-spacing: 0.05em; border-bottom: 2px solid #bbf7d0; }
.orders-table tbody tr { transition: background 0.15s; }
.orders-table tbody tr:hover { background: #f0fdf4; }
.status-badge { font-weight: 600; border-radius: 0.5rem; padding: 0.35em 0.9em; font-size: 0.95em; display: inline-block; }
.status-completed { background: #22c55e; color: #fff; }
.status-canceled { background: #f87171; color: #fff; }
.status-other { background: #d4d4d8; color: #444; }
.product-list { display: flex; flex-direction: column; gap: 0.65em; }
.product-item { display: flex; align-items: center; gap: 0.5em; margin-bottom: 0.15em; }
.product-img { width: 2.2em; height: 2.2em; object-fit: cover; border-radius: 0.5em; border: 1px solid #bbf7d0; background: #e5e7eb; }
.product-info { display: flex; flex-direction: column; gap: 0.1em; }
.product-title { color: #166534; font-weight: 700; font-size: 1em; }
.product-qty { color: #16a34a; font-size: 0.9em; }
.product-subtotal { color: #22c55e; font-size: 0.9em; font-weight: 500; }
@media (max-width: 900px) { .orders-table th, .orders-table td { padding: 0.6rem 0.5rem; } .orders-table-container { border-radius: 1rem; } }
@media (max-width: 700px) { .orders-table-container { display: none; } .mobile-orders-list { display: block !important; } }
@media (max-width: 600px) { h1 { font-size: 1.5em !important; } .product-img { width: 1.4em; height: 1.4em; } .mobile-order-card { padding: 1.1em 1em;} }
</style>
<section class="min-h-screen py-10 bg-green-50">
  <div class="px-2 mx-auto sm:px-5 max-w-7xl">
    <h1 class="mb-8 text-3xl font-extrabold tracking-tight text-center text-green-800 md:text-4xl">
      Riwayat Pesanan Anda
    </h1>

    
    <?php if(isset($orders) && count($orders)): ?>
      <div class="my-4 orders-table-container">
        <table class="orders-table">
          <thead>
            <tr>
              <th>KODE ORDER</th>
              <th>TANGGAL</th>
              <th>STATUS</th>
              <th>TOTAL HARGA</th>
              <th>PRODUK</th>
              <th>PENGIRIMAN</th>
              <th>PEMBAYARAN</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $statusClass = ($order->enum_order_status_id == 4)
                  ? 'status-badge status-completed'
                  : (($order->enum_order_status_id == 5)
                      ? 'status-badge status-canceled'
                      : 'status-badge status-other');
                $statusText = ($order->enum_order_status_id == 4)
                  ? 'Selesai'
                  : (($order->enum_order_status_id == 5)
                      ? 'Dibatalkan'
                      : 'Lainnya');
              ?>
              <tr>
                <td>
                  <span class="font-mono font-bold text-green-700"><?php echo e($order->order_code); ?></span>
                </td>
                <td>
                  <span class="block"><?php echo e(\Carbon\Carbon::parse($order->order_date)->format('d M Y')); ?></span>
                  <span class="block text-xs text-gray-400"><?php echo e(\Carbon\Carbon::parse($order->order_date)->format('H:i')); ?></span>
                </td>
                <td>
                  <span class="<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                </td>
                <td>
                  <span class="text-lg font-bold text-green-800">Rp<?php echo e(number_format($order->total_price, 0, ',', '.')); ?></span>
                </td>
                <td>
                  <div class="product-list">
                    <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="product-item">
                        <?php
                          $img = isset($detail->product->images) && $detail->product->images->count() > 0
                              ? asset($detail->product->images->first()->image_url)
                              : asset('images/no-image.png');
                        ?>
                        <img src="<?php echo e($img); ?>" alt="<?php echo e($detail->product->name); ?>" class="product-img" />
                        <div class="product-info">
                          <span class="product-title"><?php echo e($detail->product->name); ?></span>
                          <span class="product-qty">x<?php echo e($detail->quantity); ?></span>
                          <span class="product-subtotal">Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></span>
                        </div>
                      </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </td>
                <td>
                  <?php if($order->shipping): ?>
                    <div class="text-xs leading-5">
                      <span class="font-semibold"><?php echo e($order->shipping->courier ?? '-'); ?></span>
                      <span class="block"><?php echo e($order->shipping->service ?? '-'); ?></span>
                      <span class="block text-green-700">Ongkir: Rp<?php echo e(number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.')); ?></span>
                      <span class="block">Resi: <span class="font-mono"><?php echo e($order->shipping->tracking_number ?? '-'); ?></span></span>
                      <span class="block text-green-600">Status: <?php echo e($order->shipping->status ?? '-'); ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-sm text-gray-400">Belum dikirim</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($order->payment): ?>
                    <div class="text-xs leading-5">
                      <span class="font-semibold"><?php echo e($order->payment->method->name ?? '-'); ?></span>
                      <span class="block">Status: <?php echo e($order->payment->status ?? '-'); ?></span>
                      <span class="block">Transfer: Rp<?php echo e(number_format($order->payment->total ?? 0, 0, ',', '.')); ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-sm text-gray-400">Belum dibayar</span>
                  <?php endif; ?>
                </td>
                <td style="min-width:120px;">
                  <a href="<?php echo e(route('user.orders.show', $order->id)); ?>"
                    class="order-action-btn detail">
                    Detail
                  </a>
                  
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      
      <div class="mobile-orders-list" style="display:none;">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $statusClass = ($order->enum_order_status_id == 4)
              ? 'status-badge status-completed'
              : (($order->enum_order_status_id == 5)
                  ? 'status-badge status-canceled'
                  : 'status-badge status-other');
            $statusText = ($order->enum_order_status_id == 4)
              ? 'Selesai'
              : (($order->enum_order_status_id == 5)
                  ? 'Dibatalkan'
                  : 'Lainnya');
          ?>
          <div class="px-4 py-4 mb-4 bg-white border border-green-100 shadow-md mobile-order-card rounded-2xl">
            <div class="flex flex-col gap-1">
              <div class="flex items-center justify-between mb-2">
                <span class="font-mono text-base font-bold text-green-700">
                  <?php echo e($order->order_code); ?>

                </span>
                <span class="<?php echo e($statusClass); ?> text-xs"><?php echo e($statusText); ?></span>
              </div>
              <span class="block mb-2 text-xs text-gray-500">
                <?php echo e(\Carbon\Carbon::parse($order->order_date)->format('d M Y H:i')); ?>

              </span>
              <div class="flex items-center gap-2 mb-2">
                <span class="text-base font-bold text-green-800">
                  Rp<?php echo e(number_format($order->total_price, 0, ',', '.')); ?>

                </span>
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Produk:</div>
                <div class="product-list">
                  <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                      $img = isset($detail->product->images) && $detail->product->images->count() > 0
                          ? asset($detail->product->images->first()->image_url)
                          : asset('images/no-image.png');
                    ?>
                    <div class="product-item">
                      <img src="<?php echo e($img); ?>" alt="<?php echo e($detail->product->name); ?>" class="product-img" />
                      <div class="product-info">
                        <span class="product-title"><?php echo e($detail->product->name); ?></span>
                        <span class="product-qty">x<?php echo e($detail->quantity); ?></span>
                        <span class="product-subtotal">Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></span>
                      </div>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Pengiriman:</div>
                <?php if($order->shipping): ?>
                  <div class="text-xs leading-5">
                    <span class="font-semibold"><?php echo e($order->shipping->courier ?? '-'); ?></span>
                    <span class="block"><?php echo e($order->shipping->service ?? '-'); ?></span>
                    <span class="block text-green-700">Ongkir: Rp<?php echo e(number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.')); ?></span>
                    <span class="block">Resi: <span class="font-mono"><?php echo e($order->shipping->tracking_number ?? '-'); ?></span></span>
                    <span class="block text-green-600">Status: <?php echo e($order->shipping->status ?? '-'); ?></span>
                  </div>
                <?php else: ?>
                  <span class="text-sm text-gray-400">Belum dikirim</span>
                <?php endif; ?>
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Pembayaran:</div>
                <?php if($order->payment): ?>
                  <div class="text-xs leading-5">
                    <span class="font-semibold"><?php echo e($order->payment->method->name ?? '-'); ?></span>
                    <span class="block">Status: <?php echo e($order->payment->status ?? '-'); ?></span>
                    <span class="block">Transfer: Rp<?php echo e(number_format($order->payment->total ?? 0, 0, ',', '.')); ?></span>
                  </div>
                <?php else: ?>
                  <span class="text-sm text-gray-400">Belum dibayar</span>
                <?php endif; ?>
              </div>
              <div class="flex gap-2 mt-3">
                <a href="<?php echo e(route('user.orders.show', $order->id)); ?>"
                  class="order-action-btn detail" style="margin-bottom:0;">
                  Detail
                </a>
                
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-center my-6">
          <?php echo e($orders->links()); ?>

        </div>
      </div>
    <?php else: ?>
      <div class="flex flex-col items-center justify-center py-16 text-center text-green-800">
        <svg class="w-10 h-10 mb-4 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-5.586a2 2 0 01-1.414-.586l-1.414-1.414A2 2 0 009.586 3H8a2 2 0 00-2 2v14z" />
        </svg>
        <h2 class="mb-2 text-2xl font-bold">Belum ada riwayat pesanan</h2>
        <p class="mb-4 text-green-600">Tidak ada pesanan yang selesai atau dibatalkan.</p>
        <a href="<?php echo e(route('products.index')); ?>" class="px-6 py-3 text-base font-bold text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
          Lihat Produk
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/user/orders/history.blade.php ENDPATH**/ ?>