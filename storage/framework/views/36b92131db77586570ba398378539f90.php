<?php $__env->startSection('title', 'Konfirmasi Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Auth;
    // Data user & alamat
    $user = Auth::user();
    $addresses = $user && method_exists($user, 'addresses') ? $user->addresses()->get() : collect();
    
    // Lokasi toko Azka Garden (fixed as per requirement)
    $toko_name = 'Azka Garden';
    $toko_address = 'Jl. Raya KSU, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16412';
    $toko_lat = -6.4122794;
    $toko_lng = 106.829692;

    // Get selected shipping method from session (or use default)
    $shipping_method = session('shipping_method', 'KURIR_TOKO');
    $payment_method = session('payment_method', 'CASH');
    $shipping_address_id = session('shipping_address_id');
    
    // Map payment methods to readable names
    $paymentMethodLabels = [
        'CASH' => 'Uang Tunai',
        'COD_QRIS' => 'COD dengan QRIS/E-Wallet',
        'QRIS' => 'QRIS',
        'EWALLET' => 'E-Wallet'
    ];
    
    // Default shipping cost
    $shippingCost = 0;
    $selectedAddress = null;
    
    // Make sure we have an address selected
    if($addresses->count()) {
        $selectedAddress = $shipping_address_id 
            ? $addresses->where('id', $shipping_address_id)->first() 
            : ($addresses->where('is_primary', 1)->first() ?? $addresses->first());
            
        $lat = floatval($selectedAddress->latitude ?? 0);
        $lng = floatval($selectedAddress->longitude ?? 0);
        
        // Set shipping cost based on method from database values
        if ($shipping_method == 'KURIR_TOKO') {
            $shippingCost = 10000; // Base cost, will be adjusted by distance
            if ($lat && $lng) {
                // Calculate straight-line distance (approximate)
                $distance = sqrt(pow($lat - $toko_lat, 2) + pow($lng - $toko_lng, 2)) * 111.32;
                if ($distance > 10) $shippingCost = 20000;
                elseif ($distance > 5) $shippingCost = 15000;
            }
        } elseif ($shipping_method == 'GOSEND') {
            $shippingCost = 25000;
        } elseif ($shipping_method == 'JNE') {
            $shippingCost = 12000;
        } elseif ($shipping_method == 'JNT') {
            $shippingCost = 14000;
        } elseif ($shipping_method == 'SICEPAT') {
            $shippingCost = 15000;
        }
        // AMBIL_SENDIRI remains 0
    }
    
    // Map shipping codes to readable names
    $shippingMethodLabels = [
        'KURIR_TOKO' => 'Kurir Toko',
        'GOSEND' => 'GoSend Sameday',
        'JNE' => 'JNE REG',
        'JNT' => 'J&T EZ',
        'SICEPAT' => 'SiCepat BEST',
        'AMBIL_SENDIRI' => 'Ambil Sendiri di Toko',
    ];
    
    // Display names for shipping methods
    $shippingMethodDescriptions = [
        'KURIR_TOKO' => 'Pengiriman langsung dari Azka Garden ke alamat tujuan',
        'GOSEND' => 'Pengiriman cepat via GoSend',
        'JNE' => 'Pengiriman reguler via JNE',
        'JNT' => 'Pengiriman reguler via J&T',
        'SICEPAT' => 'Pengiriman reguler via SiCepat',
        'AMBIL_SENDIRI' => 'Ambil langsung di toko Azka Garden',
    ];
?>
<style>
    :root {
        --green: #166534;
        --green-light: #4c9352;
        --white: #fff;
        --gray: #888e92;
        --gray-soft: #e5e7eb;
        --gray-dark: #374151;
        --black: #111827;
        --alert-red: #b91c1c;
        --alert-bg: #fef2f2;
    }
    .confirm-container {
        max-width: 640px;
        margin: 0 auto 3.5rem auto;
        padding: 2.5rem 1.5rem;
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 6px 32px 0 rgba(22,101,52,0.08);
        animation: fadeInDown 0.6s cubic-bezier(.4,2.7,.5,1) both;
    }
    @media (max-width: 768px) {
        .confirm-container {
            margin-bottom: 2rem;
        }
    }
    @keyframes fadeInDown { 0% { opacity: 0; transform: translateY(-30px);} 100% { opacity: 1; transform: none;}}
    .confirm-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--green);
        text-align: center;
        letter-spacing: -1px;
        margin-bottom: 2rem;
        animation: fadeIn 1s;
        font-family: 'Montserrat', 'Quicksand', 'sans-serif';
    }
    @keyframes fadeIn { from {opacity:0} to {opacity:1} }
    .order-summary-card {
        background: var(--white);
        border-radius: 18px;
        box-shadow: 0 1px 12px 0 rgba(22,101,52,.07);
        padding: 2rem 1.5rem 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--gray-soft);
        animation: fadeInUp 0.7s cubic-bezier(.4,2.7,.5,1) both;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px);}
        100% { opacity: 1; transform: none;}
    }
    .order-summary-header {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--green);
        margin-bottom: 1rem;
        border-bottom: 2px solid var(--gray-soft);
        padding-bottom: .5rem;
        letter-spacing:.2px;
    }
    .order-items-list { padding-left: 0; margin-bottom: 1.1rem; list-style: none;}
    .order-items-list li {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: .6rem 0; border-bottom: 1px dotted var(--gray-soft); font-size: 1rem;
        color: var(--green); transition: background 0.2s;
        font-family: 'Quicksand', 'Montserrat', 'sans-serif';
    }
    .order-items-list li:hover { background: #f3f4f6; }
    .order-items-list li:last-child { border-bottom: none; }
    .order-total { font-size: 1.18rem; font-weight: 700; color: var(--green); margin-top: 1rem;}
    .order-info { margin-top: .5rem; font-size: 1rem; color: var(--black);}
    .order-info span { font-weight: 500; color: var(--green);}
    .confirm-actions {
        display: flex; gap: 1rem; justify-content: space-between; margin-top: 2rem;
        flex-wrap: wrap; animation: fadeIn 1.2s;
    }
    .btn-modern {
        font-size: 1.06rem; font-weight: 700; padding: .9rem 2.2rem; border-radius: 12px;
        border: none; outline: none; transition: box-shadow .2s, background .2s, transform .13s;
        cursor: pointer; box-shadow: 0 2px 8px 0 rgba(22,101,52,0.07);
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        text-decoration: none; will-change: transform;
        font-family: 'Quicksand', 'Montserrat', 'sans-serif';
    }
    .btn-modern:active { transform: scale(0.97);}
    .btn-pay {
        background: linear-gradient(90deg, var(--green-light) 60%, var(--green) 100%);
        color: #fff; border: none;
    }
    .btn-pay:hover, .btn-pay:focus {
        background: linear-gradient(90deg, #388e3c 70%, #111827 100%);
        box-shadow: 0 4px 18px 0 rgba(22,101,52,0.10);
    }
    .btn-detail, .btn-cancel {
        background: var(--gray-soft); color: var(--gray-dark); border: 1.5px solid var(--gray);
    }
    .btn-detail:hover, .btn-detail:focus,
    .btn-cancel:hover, .btn-cancel:focus {
        background: #d1d5db; color: var(--black);
    }
    .btn-cancel { 
        margin-right: .5rem; 
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1.5px solid #fecaca;
    }
    .btn-cancel:hover {
        background-color: #fecaca;
        color: #991b1b;
    }
    .breakdown-list {
        margin-top: 1.2rem; margin-bottom: 1.1rem; padding: 0; list-style: none; font-size:1rem;
    }
    .breakdown-list li {
        display: flex; justify-content: space-between; margin-bottom: 0.4rem;
        color: var(--green); font-size: 1rem;
    }
    .breakdown-list .label { font-weight: 500; }
    .breakdown-list .value { font-weight: 500;}
    .breakdown-list .discount, .breakdown-list .packing, .breakdown-list .tax { color: var(--gray);}
    .price-strikethrough {
        position: relative; color: var(--gray) !important; font-weight: 600; display: inline-block; opacity: 0.97;
    }
    .price-strikethrough::after {
        content: ""; position: absolute; left: 8%; right: 8%; top: 52%; height: 0;
        border-top: 2.5px solid #b91c1c; opacity: 0.7; transform: rotate(-13deg); pointer-events: none;
    }
    .order-detail-status-badge {
        display: inline-block; background: var(--gray-soft); color: var(--green); font-weight: 600;
        padding: 0.35em 1.1em; border-radius: 1em; box-shadow: 0 2px 6px rgba(80,120,90,0.06);
        font-size: 1em; margin-left: 0.6em; text-transform: capitalize;
    }
    .order-detail-status-badge-waiting, .order-detail-status-badge-pending {
        background: #f9fafb; color: #b45309;
    }
    .address-list { margin-bottom: 2.5rem; padding: 0; list-style: none;}
    .address-list li {
        background: #f5f5f5; border-radius: 12px; border: 1px solid #e5e7eb;
        margin-bottom: 1em; padding: 1em 1em 1.1em 1em;
        color: var(--green); font-size: 1rem; position: relative;
    }
    .address-list .primary { background: #e6fbe7; border-color: #c8e6c9;}
    .address-list .label { font-weight: bold; color: var(--green-dark); margin-right: 0.5em;}
    .address-list .shipping-est {
        font-size: .98em; color: var(--gray-dark); margin-top: 2px; display: block;
    }
    .address-list .incomplete {
        color: var(--alert-red);
        font-weight: 500;
        margin-top: 5px;
    }
    .address-list .btn-geocode {
        padding: 3px 10px;
        font-size: .98em;
        border-radius: 7px;
        border: none;
        background: var(--gray-soft);
        color: var(--gray-dark);
        cursor: pointer;
        margin-left: 0.5em;
        margin-top: 3px;
        transition: background 0.13s;
    }
    .address-list .btn-geocode:hover {
        background: #d1d5db;
        color: var(--black);
    }
    #confirmModalModern { opacity:0; pointer-events:none; transition:opacity 0.22s;}
    #confirmModalModern.active { opacity:1; pointer-events:auto;}
    .info-box {
        background-color: #e6fbe7;
        border: 1px solid #c8e6c9;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .info-title {
        color: var(--green);
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .payment-qris {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 20px 0;
        padding: 15px;
        background-color: #f0fdf4;
        border-radius: 12px;
        border: 1px solid #dcfce7;
    }
    .qr-code-container {
        margin: 15px 0;
        padding: 15px;
        background-color: white;
        border-radius: 8px;
        border: 1px solid #d1d5db;
    }
    .qr-code {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }
    .qr-instructions {
        text-align: center;
        font-size: 0.9rem;
        color: #4b5563;
        margin-top: 10px;
    }
    .payment-wait-message {
        margin-top: 15px;
        text-align: center;
        font-weight: 600;
        color: #b45309;
    }
    .strikethrough-text {
        color: var(--gray) !important;
        text-decoration: line-through;
        text-decoration-color: #b91c1c;
        text-decoration-thickness: 2px;
    }
</style>

<div class="confirm-container">
    <h2 class="confirm-title">Konfirmasi Pesanan</h2>

    
    <div class="order-summary-card" style="margin-bottom:1.8rem;">
        <div class="order-summary-header">
            <span>Informasi Pengirim</span>
        </div>
        <div>
            <strong>Nama Pengirim:</strong> <?php echo e($toko_name); ?><br>
            <strong>Alamat Pengirim:</strong> <?php echo e($toko_address); ?>

        </div>
    </div>

    
    <div class="order-summary-card" style="margin-bottom:1.8rem;">
        <div class="order-summary-header">
            <span>Profil & Alamat Pengiriman</span>
        </div>
        <div style="margin-bottom:1em;">
            <strong>Nama:</strong> <?php echo e($user->name ?? '-'); ?><br>
            <strong>Email:</strong> <?php echo e($user->email ?? '-'); ?><br>
            <strong>No HP:</strong> <?php echo e($user->phone ?? '-'); ?>

        </div>
        
        <?php if($selectedAddress): ?>
        <div class="info-box">
            <div class="info-title">Alamat Pengiriman</div>
            <div><strong>Label:</strong> <?php echo e($selectedAddress->label ?? 'Alamat Utama'); ?></div>
            <div><strong>Penerima:</strong> <?php echo e($selectedAddress->recipient); ?></div>
            <div><strong>Alamat:</strong> <?php echo e($selectedAddress->full_address); ?></div>
            <div><strong>Kota:</strong> <?php echo e($selectedAddress->city); ?>, <strong>Kode Pos:</strong> <?php echo e($selectedAddress->zip_code); ?></div>
            <div><strong>No HP:</strong> <?php echo e($selectedAddress->phone_number); ?></div>
        </div>
        
        <div class="info-box">
            <div class="info-title">Metode Pengiriman</div>
            <div><strong><?php echo e($shippingMethodLabels[$shipping_method] ?? $shipping_method); ?></strong></div>
            <div><?php echo e($shippingMethodDescriptions[$shipping_method] ?? 'Pengiriman standar'); ?></div>
            <?php if($shipping_method == 'KURIR_TOKO'): ?>
                <div class="mt-2 text-sm text-green-700">Estimasi ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></div>
            <?php elseif($shipping_method == 'AMBIL_SENDIRI'): ?>
                <div class="mt-2 text-sm font-bold text-green-700">Gratis (Rp0)</div>
            <?php else: ?>
                <div class="mt-2 text-sm text-green-700">Estimasi ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="alert-warning">
            <strong>Perhatian:</strong> Alamat pengiriman belum dipilih. Silahkan tambahkan alamat di profil Anda.
        </div>
        <?php endif; ?>
    </div>

    
    <?php if(isset($cartItems) && $cartItems->count()): ?>
        <?php
            $subtotal = 0;
            foreach($cartItems as $item) {
                $subtotal += $item->product->price * $item->quantity;
            }
            
            // Calculate all costs
            $discountTotal = 0; // Could be calculated from promo codes
            $handlingFee = 0;   // Could be added if needed
            $paymentFee = 0;    // Could be calculated based on payment method
            
            $totalBeforeTax = $subtotal + $handlingFee + $shippingCost + $paymentFee - $discountTotal;
            $taxAmount = round($totalBeforeTax * 0.11); // PPN 11%
            $totalWithTax = $totalBeforeTax + $taxAmount;
        ?>
        <div class="order-summary-card">
            <div class="order-summary-header">
                <span>Ringkasan Keranjang</span>
            </div>
            <ul class="order-items-list">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <span>
                            <strong><?php echo e($item->product->name); ?></strong>
                            <span style="font-weight:400;color:var(--gray);">x <?php echo e($item->quantity); ?></span>
                        </span>
                        <span>
                            Rp<?php echo e(number_format($item->product->price * $item->quantity, 0, ',', '.')); ?>

                        </span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="order-info">
                <div>Alamat Pengiriman:
                    <span><?php echo e($selectedAddress ? $selectedAddress->full_address : 'Belum dipilih'); ?></span>
                </div>
                <div>Metode Pengiriman:
                    <span><?php echo e($shippingMethodLabels[$shipping_method] ?? $shipping_method); ?></span>
                </div>
                <div>Metode Pembayaran:
                    <span><?php echo e($paymentMethodLabels[$payment_method] ?? $payment_method); ?></span>
                </div>
            </div>
        </div>
        <div class="breakdown-list" id="breakdown-list">
            <li>
                <span class="label">Total Produk</span>
                <span class="value">Rp<?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
            </li>
            <?php if($discountTotal > 0): ?>
                <li>
                    <span class="label discount">Diskon</span>
                    <span class="value discount">-Rp<?php echo e(number_format($discountTotal, 0, ',', '.')); ?></span>
                </li>
            <?php endif; ?>
            <?php if($handlingFee > 0): ?>
                <li>
                    <span class="label">Biaya Penanganan</span>
                    <span class="value">Rp<?php echo e(number_format($handlingFee, 0, ',', '.')); ?></span>
                </li>
            <?php endif; ?>
            <li>
                <span class="label">Ongkos Kirim</span>
                <span class="value">Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></span>
            </li>
            <?php if($paymentFee > 0): ?>
                <li>
                    <span class="label">Biaya Pembayaran</span>
                    <span class="value">Rp<?php echo e(number_format($paymentFee, 0, ',', '.')); ?></span>
                </li>
            <?php endif; ?>
            <li>
                <span class="label tax">PPN 11%</span>
                <span class="value tax">Rp<?php echo e(number_format($taxAmount, 0, ',', '.')); ?></span>
            </li>
            <hr>
            <li style="font-weight:bold;color:var(--green);font-size:1.11rem;">
                <span class="label">Total Pembayaran <span style="font-size:0.95em;color:var(--gray);">(sudah termasuk pajak)</span></span>
                <span class="value">Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?></span>
            </li>
        </div>

        <?php if($payment_method === 'QRIS'): ?>
            <div class="payment-qris">
                <h3>Pembayaran dengan QRIS</h3>
                <div class="qr-code-container">
                    <div class="qr-code">
                        <img src="<?php echo e(asset('images/qris-example.png')); ?>" alt="QRIS Code" style="width: 100%; height: auto;" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%3E%3Cpath%20d%3D%22M73%2C20H20v53h53V20z%20M111%2C20H85v27h26V20z%20M137%2C20h-16v27h16V20z%20M177%2C20h-30v53h30V20z%20M73%2C83H20v17h53V83z%20M127%2C83H85v17h42V83z%20M177%2C83h-40v17h40V83z%20M73%2C110H20v20h53V110z%20M153%2C110H85v20h68V110z%20M177%2C110h-14v20h14V110z%20M73%2C140H20v16h53V140z%20M111%2C140H85v16h26V140z%20M137%2C140h-16v16h16V140z%20M177%2C140h-30v40h30V140z%20M53%2C166H20v14h33V166z%20M80%2C166H63v14h17V166z%22%20fill%3D%22%2333bb33%22%2F%3E%3C%2Fsvg%3E'">
                    </div>
                    <div class="qr-instructions">
                        Scan QR Code di atas dengan aplikasi e-wallet Anda<br>
                        (OVO, GoPay, DANA, LinkAja, dll)
                    </div>
                </div>
                <p class="payment-wait-message">Menunggu konfirmasi pembayaran dari Admin...</p>
            </div>
        <?php endif; ?>

        <div class="confirm-actions">
            <form id="cancelForm" action="<?php echo e(route('user.orders.cancel-draft')); ?>" method="POST" style="margin:0;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-modern btn-cancel">
                    Batalkan
                </button>
            </form>
            
            <form id="createOrderForm" action="<?php echo e(route('user.orders.create')); ?>" method="POST" style="margin:0;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="shipping_method" value="<?php echo e($shipping_method); ?>">
                <?php if($selectedAddress): ?>
                <input type="hidden" name="shipping_address_id" value="<?php echo e($selectedAddress->id); ?>">
                <?php endif; ?>
                <input type="hidden" name="payment_method" value="<?php echo e($payment_method); ?>">
                <button type="submit" class="btn-modern btn-pay">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>

    <?php elseif(isset($order) && isset($order->details) && count($order->details)): ?>
        <?php
            $subtotalProducts = 0;
            $hargaCoret = 0;
            foreach($order->details as $detail) {
                $hargaCoret += isset($detail->product->price) ? $detail->product->price * $detail->quantity : $detail->subtotal;
                $subtotalProducts += $detail->subtotal;
            }
            $discountTotal = $order->discount_total ?? ($order->discount ?? 0);
            $handlingFee = $order->handling_fee ?? 0;
            $shippingCost = optional($order->shipping)->shipping_cost ?? ($order->shipping_cost ?? 0);
            $paymentFee = $order->payment_fee ?? 0;
            $totalBeforeTax = $subtotalProducts + $handlingFee + $shippingCost + $paymentFee - $discountTotal;
            $taxAmount = round($totalBeforeTax * 0.11);
            $totalWithTax = $totalBeforeTax + $taxAmount;
            $statusVal = is_object($order->status)
                ? $order->status->value
                : (is_array($order->status) ? ($order->status['value'] ?? $order->status[0] ?? $order->status) : $order->status);
            $isCanceledOrExpired = in_array(strtoupper($statusVal), ['CANCELED','FAILED','EXPIRED']);

            // Convert payment method code to readable text
            $displayPaymentMethod = $paymentMethodLabels[$order->payment_method] ?? $order->payment_method;
        ?>

        <div class="order-summary-card">
            <div class="order-summary-header">
                <span>Ringkasan Pesanan</span>
            </div>
            <ul class="order-items-list">
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $hasDiscount = isset($detail->product->price) && $detail->product->price > $detail->subtotal;
                    ?>
                    <li>
                        <span>
                            <strong><?php echo e($detail->product->name ?? 'Produk'); ?></strong>
                            <span style="font-weight:400;color:var(--gray);">x <?php echo e($detail->quantity); ?></span>
                        </span>
                        <span>
                            <?php if($hasDiscount): ?>
                                <span class="strikethrough-text">Rp<?php echo e(number_format($detail->product->price * $detail->quantity, 0, ',', '.')); ?></span>
                                <span style="color:#b91c1c;margin-left:.5em;font-weight:700;">Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></span>
                            <?php else: ?>
                                Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="mt-3 order-info">
                <div>Alamat Pengiriman: 
                    <span>
                        <?php if($order->shipping_address): ?>
                            <?php echo e(is_string($order->shipping_address) ? $order->shipping_address : json_encode($order->shipping_address)); ?>

                        <?php elseif($selectedAddress): ?>
                            <?php echo e($selectedAddress->full_address ?? '-'); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </span>
                </div>
                <div>Metode Pengiriman: 
                    <span>
                        <?php echo e($shippingMethodLabels[$order->shipping_method] ?? ($order->shipping_method ?? '-')); ?>

                    </span>
                </div>
                <div>Metode Pembayaran: <span><?php echo e($displayPaymentMethod); ?></span></div>
                <div>
                  Status:
                  <span>
                    <?php if($statusVal == 'WAITING_PAYMENT' || $statusVal == 'PENDING'): ?>
                        <span class="order-detail-status-badge order-detail-status-badge-waiting">Menunggu Pembayaran</span>
                    <?php elseif($isCanceledOrExpired): ?>
                        <span class="order-detail-status-badge" style="color:var(--gray);">Dibatalkan</span>
                    <?php else: ?>
                        <span class="order-detail-status-badge"><?php echo e(ucfirst(strtolower($statusVal))); ?></span>
                    <?php endif; ?>
                  </span>
                </div>
            </div>
        </div>
        <div class="breakdown-list">
            <li>
                <span class="label">Total Produk</span>
                <?php if($hargaCoret > $subtotalProducts): ?>
                    <span>
                        <span class="strikethrough-text">Rp<?php echo e(number_format($hargaCoret, 0, ',', '.')); ?></span>
                        <span style="margin-left:.5em;color:#b91c1c;font-weight:700;">Rp<?php echo e(number_format($subtotalProducts, 0, ',', '.')); ?></span>
                    </span>
                <?php else: ?>
                    <span class="value">Rp<?php echo e(number_format($subtotalProducts, 0, ',', '.')); ?></span>
                <?php endif; ?>
            </li>
            <?php if($discountTotal > 0): ?>
                <li><span class="label discount">Diskon</span> <span class="value discount">-Rp<?php echo e(number_format($discountTotal, 0, ',', '.')); ?></span></li>
            <?php endif; ?>
            <?php if($handlingFee > 0): ?>
                <li><span class="label packing">Biaya Packing</span> <span class="value packing">Rp<?php echo e(number_format($handlingFee, 0, ',', '.')); ?></span></li>
            <?php endif; ?>
            <li><span class="label">Ongkos Kirim</span> <span class="value">Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></span></li>
            <?php if($paymentFee > 0): ?>
                <li><span class="label">Biaya Pembayaran</span> <span class="value">Rp<?php echo e(number_format($paymentFee, 0, ',', '.')); ?></span></li>
            <?php endif; ?>
            <li><span class="label tax">PPN 11%</span> <span class="value tax">Rp<?php echo e(number_format($taxAmount, 0, ',', '.')); ?></span></li>
            <hr>
            <li style="font-weight:bold;color:var(--green);font-size:1.11rem;">
                <span class="label">Total Pembayaran <span style="font-size:0.95em;color:var(--gray);">(sudah termasuk pajak)</span></span>
                <span class="value">
                    <?php if($isCanceledOrExpired): ?>
                        <span class="strikethrough-text">Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?></span>
                    <?php else: ?>
                        Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?>

                    <?php endif; ?>
                </span>
            </li>
        </div>

        <?php if($order->payment_method === 'QRIS'): ?>
            <div class="payment-qris">
                <h3>Pembayaran dengan QRIS</h3>
                <div class="qr-code-container">
                    <div class="qr-code">
                        <img src="<?php echo e(asset('images/qris-example.png')); ?>" alt="QRIS Code" style="width: 100%; height: auto;" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%3E%3Cpath%20d%3D%22M73%2C20H20v53h53V20z%20M111%2C20H85v27h26V20z%20M137%2C20h-16v27h16V20z%20M177%2C20h-30v53h30V20z%20M73%2C83H20v17h53V83z%20M127%2C83H85v17h42V83z%20M177%2C83h-40v17h40V83z%20M73%2C110H20v20h53V110z%20M153%2C110H85v20h68V110z%20M177%2C110h-14v20h14V110z%20M73%2C140H20v16h53V140z%20M111%2C140H85v16h26V140z%20M137%2C140h-16v16h16V140z%20M177%2C140h-30v40h30V140z%20M53%2C166H20v14h33V166z%20M80%2C166H63v14h17V166z%22%20fill%3D%22%2333bb33%22%2F%3E%3C%2Fsvg%3E'">
                    </div>
                    <div class="qr-instructions">
                        Scan QR Code di atas dengan aplikasi e-wallet Anda<br>
                        (OVO, GoPay, DANA, LinkAja, dll)
                    </div>
                </div>
                <p class="payment-wait-message">Menunggu konfirmasi pembayaran dari Admin...</p>
            </div>
        <?php endif; ?>

        <div class="confirm-actions">
            <?php if(!$isCanceledOrExpired && ($statusVal === 'WAITING_PAYMENT' || $statusVal === 'PENDING')): ?>
                <form id="cancelForm" action="<?php echo e(route('user.orders.cancel', $order->id)); ?>" method="POST" style="margin:0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-modern btn-cancel">
                        Batalkan
                    </button>
                </form>
                
                <form id="payForm" action="<?php echo e(route('user.orders.pay', $order->id)); ?>" method="POST" style="margin:0;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-modern btn-pay">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('user.cart.index')); ?>" class="btn-modern btn-detail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:7px;" class="mr-2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    Kembali ke Keranjang
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="py-24 text-center">
            <h2 class="mb-6 text-2xl font-bold" style="color:var(--green)">Keranjang atau pesanan Anda kosong</h2>
            <a href="<?php echo e(route('user.cart.index')); ?>" class="px-5 py-3 font-bold text-white" style="background:var(--green);border-radius:10px;box-shadow:0 2px 6px #e5e7eb;">
                Kembali ke Keranjang
            </a>
        </div>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // For the cancel button to maintain cart items when canceling
    const cancelForm = document.getElementById('cancelForm');
    if (cancelForm) {
        cancelForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Add a hidden field to tell the controller to preserve cart items
            const preserveCartField = document.createElement('input');
            preserveCartField.type = 'hidden';
            preserveCartField.name = 'preserve_cart';
            preserveCartField.value = '1';
            cancelForm.appendChild(preserveCartField);
            
            // Submit the form
            cancelForm.submit();
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/User/orders/confirm.blade.php ENDPATH**/ ?>