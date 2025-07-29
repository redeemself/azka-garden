<?php $__env->startSection('title', 'Detail Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<style>
body { background: #eaf8f0; }
.order-detail-container {
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 8px 40px rgba(80,120,90,0.08);
  border: 1px solid #e8f4ea;
  padding: 2rem 1.5rem;
  margin-bottom: 2rem;
}
.order-detail-section-title {
  color: #295f3a;
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}
.order-detail-label { color: #295f3a; font-weight: 700; }
.order-detail-value { color: #344d3b; font-family: 'Inter', 'Poppins', 'Nunito', sans-serif; }
.order-detail-divider { margin: 2rem 0; border: none; border-top: 2px solid #eaf8f0; }
.order-detail-product-list { display: flex; flex-direction: column; gap: 1.15em; }
.order-detail-product-item { display: flex; align-items: flex-start; gap: 1em; }
.order-detail-product-img {
  width: 52px; height: 52px; object-fit: cover; border-radius: 0.85em;
  border: 2px solid #d1e7d9; background: #eaf8f0;
  box-shadow: 0 2px 8px rgba(120,180,120,0.08); flex-shrink: 0;
}
.order-detail-product-info { display: flex; flex-direction: column; gap: 1px; }
.order-detail-product-title { color: #295f3a; font-weight: 700; font-size: 1.05em; margin-bottom: 0.1em; }
.order-detail-product-qty { color: #5f8b6a; font-size: 0.98em; margin-bottom: 2px; }
.order-detail-product-subtotal { color: #5f8b6a; font-size: 1em; font-weight: 600; }
.order-detail-summary {
  margin: 2.2em 0 0.8em 0; padding: 1.4em 1.1em 1.8em 1.1em;
  background: #f8faf8; border-radius: 1.3em; border: 1px solid #e8f4ea;
  box-shadow: 0 2px 10px #295f3a10;
  max-width: 430px;
  margin-left: auto; margin-right: auto;
}
.order-detail-summary-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 1em; margin-bottom: 0.7em;
}
.order-detail-summary-label { color: #5f8b6a; }
.order-detail-summary-value { color: #295f3a; font-weight: 600; }
.order-detail-summary-discount {
  color: #ee5757; font-weight: 700; font-size: .97em;
}
.order-detail-summary-tax {
  color: #1a7f73; font-weight: 600; font-size: .97em;
}
.order-detail-summary-total {
  font-size: 1.15em; font-weight: 800; color: #295f3a;
  letter-spacing: 0.03em;
}
.price-strikethrough {
  position: relative;
  color: #b2b9bb !important;
  font-weight: 600;
  display: inline-block;
  opacity: 0.97;
}
.price-strikethrough::after {
  content: "";
  position: absolute;
  left: 8%;
  right: 8%;
  top: 52%;
  height: 0;
  border-top: 2.5px solid #ff8585;
  opacity: 0.7;
  transform: rotate(-13deg);
  pointer-events: none;
}
.order-detail-status-badge {
  display: inline-block;
  background: linear-gradient(90deg, #eaf8f0 60%, #f8faf8 100%);
  color: #295f3a;
  font-weight: 600;
  padding: 0.35em 1.1em;
  border-radius: 1em;
  box-shadow: 0 2px 6px rgba(80,120,90,0.06);
  font-size: 1em;
  margin-left: 0.6em;
  text-transform: capitalize;
}
.order-detail-status-badge-canceled, .order-detail-status-badge-expired {
  background: linear-gradient(90deg, #f2f4f5 60%, #f8faf8 100%);
  color: #888e92;
}
.order-detail-status-badge-waiting, .order-detail-status-badge-pending {
  background: linear-gradient(90deg, #fffeea 60%, #fafae8 100%);
  color: #a86a00;
}
.order-detail-status-badge-completed {
  background: linear-gradient(90deg, #eaf8f0 60%, #f8faf8 100%);
  color: #295f3a;
}
.order-detail-payment-method {
  font-size: 1em;
  font-weight: 600;
  color: #295f3a;
  margin-bottom: 0.2em;
  display: block;
}
.order-detail-payment-label {
  font-size: 1em;
  color: #5f8b6a;
  margin-bottom: 0.1em;
  display: block;
}
.order-detail-payment, .order-detail-shipping {
  background: #f8faf8;
  border-radius: 1em;
  border: 1px solid #e8f4ea;
  padding: 1em 1.2em;
  color: #295f3a;
  font-size: 1em;
  margin-bottom: 0.5em;
}
.order-detail-back-btn {
  background: #295f3a;
  color: #fff;
  font-weight: 700;
  border-radius: 0.85em;
  padding: 0.7em 1.5em;
  transition: background 0.18s, box-shadow 0.18s;
  box-shadow: 0 2px 8px #295f3a30;
  font-size: 1em;
  display: inline-flex;
  gap: 0.7em;
  align-items: center;
  text-decoration: none;
}
.order-detail-back-btn:hover {
  background: #22452d;
}
@media (max-width: 640px) {
  .order-detail-container {
    border-radius: 1em;
    padding: 1rem 0.5rem;
  }
  .order-detail-product-img { width: 34px; height: 34px;}
  .order-detail-summary {
    max-width: 100%;
    padding: 1em 0.5em 1.3em 0.5em;
  }
}
</style>
<section class="min-h-screen py-10 bg-[#eaf8f0]">
  <div class="max-w-3xl px-3 mx-auto sm:px-5">
    <h1 class="mb-10 text-3xl font-extrabold tracking-tight text-center" style="color:#295f3a;">
      Detail Pesanan
    </h1>
    <?php if($order): ?>
      <?php
        // STATUS ORDER
        $statusVal = is_object($order->status)
          ? $order->status->value
          : (is_array($order->status) ? ($order->status['value'] ?? $order->status[0] ?? $order->status) : $order->status);
        $statusClass = '';
        $statusText = '';
        $isCanceledOrExpired = in_array(strtoupper($statusVal), ['CANCELED','FAILED','EXPIRED']);
        switch (strtoupper($statusVal)) {
          case 'WAITING_PAYMENT':
          case 'PENDING':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-waiting';
            $statusText = 'Menunggu Pembayaran';
            break;
          case 'PROCESSING':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-processing';
            $statusText = 'Diproses';
            break;
          case 'SHIPPED':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-shipped';
            $statusText = 'Dikirim';
            break;
          case 'COMPLETED':
          case 'SUCCESS':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-completed';
            $statusText = 'Selesai';
            break;
          case 'CANCELED':
          case 'FAILED':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-canceled';
            $statusText = 'Dibatalkan';
            break;
          case 'EXPIRED':
            $statusClass = 'order-detail-status-badge order-detail-status-badge-expired';
            $statusText = 'Kadaluarsa';
            break;
          default:
            $statusClass = 'order-detail-status-badge';
            $statusText = ucfirst(strtolower($statusVal));
        }

        // RANGKUMAN BIAYA
        $totalProduk = 0;
        $hargaCoret = 0;
        foreach($order->details as $detail) {
          if (isset($detail->product->price)) {
            $hargaCoret += $detail->product->price * $detail->quantity;
          }
          $totalProduk += $detail->subtotal;
        }
        $diskon = isset($order->discount) ? $order->discount : (isset($order->promo_discount) ? $order->promo_discount : 0);
        $promoCode = isset($order->promo_code) ? $order->promo_code : (isset($order->promo) ? $order->promo : null); // sesuaikan field
        $tax = isset($order->tax) ? $order->tax : round($totalProduk * 0.11);
        $ongkir = isset($order->shipping) && isset($order->shipping->shipping_cost) ? $order->shipping->shipping_cost : 0;
        $grandTotal = $totalProduk + $tax + $ongkir - $diskon;

        // BATAS WAKTU PEMBAYARAN
        $orderDate = \Carbon\Carbon::parse($order->order_date);
        $expiredAt = null;
        $now = \Carbon\Carbon::now();
        $secondsLeft = null;
        if(strtoupper($statusVal) === 'WAITING_PAYMENT' || strtoupper($statusVal) === 'PENDING') {
          if(isset($order->payment) && isset($order->payment->expired_at) && $order->payment->expired_at) {
            $expiredAt = \Carbon\Carbon::parse($order->payment->expired_at);
          } else {
            $expiredAt = $orderDate->copy()->addHour();
          }
          $secondsLeft = $expiredAt->greaterThan($now) ? $now->diffInSeconds($expiredAt, false) : 0;
        }

        // STATUS PEMBAYARAN YANG LEBIH JELAS
        $paymentStatusText = '-';
        $paymentStatusVal = '';
        if ($order->payment && isset($order->payment->status)) {
          $paymentStatusVal = is_string($order->payment->status)
              ? strtoupper($order->payment->status)
              : (is_array($order->payment->status) && isset($order->payment->status['value'])
                  ? strtoupper($order->payment->status['value'])
                  : '');
        }
        switch($paymentStatusVal) {
          case 'SUCCESS':
          case 'PAID':
            $paymentStatusText = 'Sudah Lunas';
            break;
          case 'WAITING_PAYMENT':
          case 'PENDING':
            $paymentStatusText = 'Menunggu Pembayaran';
            break;
          case 'FAILED':
          case 'CANCELED':
            $paymentStatusText = 'Dibatalkan';
            break;
          case 'EXPIRED':
            $paymentStatusText = 'Kadaluarsa';
            break;
          default:
            $paymentStatusText = $statusText;
        }
      ?>

      <div class="order-detail-container">
        <div class="flex items-center mb-4">
          <span class="order-detail-label">Status:</span>
          <span class="<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
          <?php if($expiredAt && ($statusVal === 'WAITING_PAYMENT' || $statusVal === 'PENDING')): ?>
            <span class="ml-8 text-sm" style="color:#f59e42;">
              Batas pembayaran: <span id="expired-timer"><?php echo e($expiredAt->format('d M Y H:i')); ?></span>
            </span>
          <?php elseif(strtoupper($statusVal) === 'EXPIRED' && isset($order->payment) && isset($order->payment->expired_at)): ?>
            <span class="ml-8 text-sm" style="color:#ee5757;">
              Kadaluarsa: <?php echo e(\Carbon\Carbon::parse($order->payment->expired_at)->format('d M Y H:i')); ?>

            </span>
          <?php endif; ?>
        </div>
        <div class="mb-4">
          <span class="order-detail-label">Kode Order:</span>
          <span class="font-mono order-detail-value"><?php echo e($order->order_code); ?></span>
        </div>
        <div class="mb-4">
          <span class="order-detail-label">Tanggal Order:</span>
          <span class="order-detail-value"><?php echo e($orderDate->format('d M Y H:i')); ?></span>
        </div>
        <div class="mb-4">
          <span class="order-detail-label">Catatan:</span>
          <span class="order-detail-value"><?php echo e($order->note ?? '-'); ?></span>
        </div>
        <hr class="order-detail-divider">

        <h2 class="order-detail-section-title">Produk</h2>
        <div class="order-detail-product-list">
          <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="order-detail-product-item">
              <?php
                $img = isset($detail->product->images) && $detail->product->images->count() > 0
                  ? asset($detail->product->images->first()->image_url)
                  : asset('images/no-image.png');
                $hasDiscount = isset($detail->product->price) && $detail->product->price > $detail->subtotal;
              ?>
              <img src="<?php echo e($img); ?>" alt="<?php echo e($detail->product->name); ?>" class="order-detail-product-img" />
              <div class="order-detail-product-info">
                <span class="order-detail-product-title"><?php echo e($detail->product->name); ?></span>
                <span class="order-detail-product-qty">x<?php echo e($detail->quantity); ?></span>
                <span class="order-detail-product-subtotal">
                  <?php if($hasDiscount): ?>
                    <span class="price-strikethrough">Rp<?php echo e(number_format($detail->product->price * $detail->quantity, 0, ',', '.')); ?></span>
                    <span style="margin-left:.4em;color:#ee5757;font-weight:700;">Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></span>
                  <?php else: ?>
                    Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                  <?php endif; ?>
                </span>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="order-detail-summary">
          <div class="order-detail-summary-row">
            <span class="order-detail-summary-label">Total Produk</span>
            <?php if($hargaCoret > $totalProduk): ?>
              <span>
                <span class="price-strikethrough">Rp<?php echo e(number_format($hargaCoret, 0, ',', '.')); ?></span>
                <span style="margin-left:.5em;color:#ee5757;font-weight:700;">Rp<?php echo e(number_format($totalProduk, 0, ',', '.')); ?></span>
              </span>
            <?php else: ?>
              <span class="order-detail-summary-value">Rp<?php echo e(number_format($totalProduk, 0, ',', '.')); ?></span>
            <?php endif; ?>
          </div>
          <?php if($diskon > 0): ?>
          <div class="order-detail-summary-row">
            <span class="order-detail-summary-label">Diskon
              <?php if($promoCode): ?>
                <span style="background:#eaf8f0;padding:0.15em 0.7em;margin-left:0.5em;border-radius:0.5em;color:#1a7f73;font-size:0.95em;">
                  Kode: <?php echo e($promoCode); ?>

                </span>
              <?php endif; ?>
            </span>
            <span class="order-detail-summary-discount">-Rp<?php echo e(number_format($diskon, 0, ',', '.')); ?></span>
          </div>
          <?php endif; ?>
          <?php if($tax > 0): ?>
          <div class="order-detail-summary-row">
            <span class="order-detail-summary-label">Pajak (PPN 11%)</span>
            <span class="order-detail-summary-tax">+Rp<?php echo e(number_format($tax, 0, ',', '.')); ?></span>
          </div>
          <?php endif; ?>
          <div class="order-detail-summary-row">
            <span class="order-detail-summary-label">Ongkos Kirim</span>
            <span class="order-detail-summary-value">+Rp<?php echo e(number_format($ongkir, 0, ',', '.')); ?></span>
          </div>
          <div class="order-detail-summary-row" style="margin-top:1em;">
            <span class="order-detail-summary-label order-detail-summary-total">Total Pembayaran</span>
            <span class="order-detail-summary-total">
              <?php if($isCanceledOrExpired): ?>
                <span class="price-strikethrough">Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
              <?php else: ?>
                Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?>

              <?php endif; ?>
            </span>
          </div>
        </div>

        <hr class="order-detail-divider">

        <h2 class="order-detail-section-title">Pengiriman</h2>
        <?php if($order->shipping): ?>
          <div class="order-detail-shipping">
            <span class="font-semibold"><?php echo e($order->shipping->courier ?? '-'); ?></span>
            <span class="block"><?php echo e($order->shipping->service ?? '-'); ?></span>
            <span class="block">Resi: <?php echo e($order->shipping->tracking_number ?? '-'); ?></span>
            <span class="block" style="color:#5f8b6a;">Ongkir: Rp<?php echo e(number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.')); ?></span>
            <span class="block" style="color:#5f8b6a;">Status: <?php echo e($order->shipping->status ?? '-'); ?></span>
          </div>
        <?php else: ?>
          <span class="text-gray-400">Belum dikirim</span>
        <?php endif; ?>

        <hr class="order-detail-divider">

        <h2 class="order-detail-section-title">Pembayaran</h2>
        <?php if($order->payment): ?>
          <div class="order-detail-payment">
            <span class="order-detail-payment-method"><?php echo e($order->payment->method->name ?? '-'); ?></span>
            <span class="order-detail-payment-label">Status:
              <span class="<?php echo e($statusClass); ?>"><?php echo e($paymentStatusText); ?></span>
            </span>
            <span class="order-detail-payment-label">Nominal: 
              <?php if($isCanceledOrExpired): ?>
                <span class="price-strikethrough">Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
              <?php else: ?>
                <span style="color:#295f3a;font-weight:bold;">Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
              <?php endif; ?>
            </span>
            <?php if($diskon > 0 && $promoCode): ?>
              <span class="order-detail-payment-label" style="color:#1a7f73;">
                Diskon aktif: <b><?php echo e($promoCode); ?></b> (-Rp<?php echo e(number_format($diskon, 0, ',', '.')); ?>)
              </span>
            <?php endif; ?>
            <?php if($expiredAt && ($statusVal === 'WAITING_PAYMENT' || $statusVal === 'PENDING')): ?>
              <span class="order-detail-payment-label" style="color:#f59e42;">
                Batas pembayaran: <span id="expired-timer-2"><?php echo e($expiredAt->format('d M Y H:i')); ?></span>
              </span>
            <?php elseif(strtoupper($statusVal) === 'EXPIRED' && isset($order->payment) && isset($order->payment->expired_at)): ?>
              <span class="order-detail-payment-label" style="color:#ee5757;">
                Kadaluarsa: <?php echo e(\Carbon\Carbon::parse($order->payment->expired_at)->format('d M Y H:i')); ?>

              </span>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <span class="text-gray-400">Belum dibayar</span>
        <?php endif; ?>

        <div class="flex justify-end gap-3 mt-8">
        <a href="<?php echo e(route('products.index')); ?>" class="order-detail-back-btn">
            Kembali ke Produk
        </a>
        <a href="<?php echo e(route('user.orders.index')); ?>" class="order-detail-back-btn" style="background:#5f8b6a;">
            Kembali ke Daftar Pesanan
        </a>
        <?php if(!$isCanceledOrExpired && isset($order->canCancel) && $order->canCancel): ?>
            <form action="<?php echo e(route('user.orders.cancel', $order->id)); ?>" method="POST" style="display:inline;">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="order-detail-back-btn"
                style="background:#ee5757;"
                onclick="return confirm('Batalkan pesanan ini? Tindakan tidak dapat dibatalkan!');">
                Batalkan Pesanan
            </button>
            </form>
        <?php endif; ?>
        </div>
      </div>
      <?php if($expiredAt && ($statusVal === 'WAITING_PAYMENT' || $statusVal === 'PENDING')): ?>
      <script>
        var secondsLeft = <?php echo e(max($secondsLeft, 0)); ?>;
        function startCountdown() {
          var timer = setInterval(function() {
            if (secondsLeft <= 0) {
              clearInterval(timer);
              document.getElementById('expired-timer').innerText = 'Waktu pembayaran telah habis';
              var el2 = document.getElementById('expired-timer-2');
              if (el2) el2.innerText = 'Waktu pembayaran telah habis';
              return;
            }
            var h = Math.floor(secondsLeft / 3600);
            var m = Math.floor((secondsLeft % 3600) / 60);
            var s = secondsLeft % 60;
            var str = (h > 0 ? h + 'j ' : '') + (m > 0 ? m + 'm ' : '') + s + 'd';
            document.getElementById('expired-timer').innerText = str;
            var el2 = document.getElementById('expired-timer-2');
            if (el2) el2.innerText = str;
            secondsLeft--;
          }, 1000);
        }
        if (secondsLeft > 0) startCountdown();
        else {
          document.getElementById('expired-timer').innerText = 'Waktu pembayaran telah habis';
          var el2 = document.getElementById('expired-timer-2');
          if (el2) el2.innerText = 'Waktu pembayaran telah habis';
        }
      </script>
      <?php endif; ?>
    <?php else: ?>
      <div class="py-24 text-center">
        <h2 class="mb-6 text-2xl font-bold" style="color:#295f3a;">Pesanan tidak ditemukan</h2>
        <a href="<?php echo e(route('products.index')); ?>" class="order-detail-back-btn">
          Kembali ke Produk
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/user/orders/show.blade.php ENDPATH**/ ?>