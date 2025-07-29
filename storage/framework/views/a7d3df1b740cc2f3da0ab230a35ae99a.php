<?php $__env->startSection('title', 'Konfirmasi Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\DB;
    
    // Informasi waktu dan pengguna saat ini
    $currentDateTime = '2025-07-28 14:41:10';
    $currentUserLogin = 'redeemself';
    
    // Data user & alamat
    $user = Auth::user();
    $addresses = $user && method_exists($user, 'addresses') ? $user->addresses()->get() : collect();
    
    // Get cart items - try to retrieve from multiple possible session keys
    $cartItems = session('cart_items') ?? session('cartItems') ?? collect();
    
    // Convert to collection if needed
    if (!($cartItems instanceof \Illuminate\Support\Collection)) {
        $cartItems = collect($cartItems);
    }
    
    // Lokasi toko Azka Garden (fixed as per requirement)
    $toko_name = 'Azka Garden';
    $toko_address = 'Jl. Raya KSU, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16412';
    $toko_lat = -6.4122794;
    $toko_lng = 106.829692;

    // Get selected shipping method from session (or use default)
    $shipping_method = session('shipping_method', 'JNT');
    $payment_method = session('payment_method', 'CASH');
    $shipping_address_id = session('shipping_address_id');
    $shipping_cost_session = session('shipping_cost'); // Try to get shipping cost from session
    
    // Fetch payment method data from database
    try {
        $localMethods = DB::table('local_payment_methods')->where('status', 1)->get();
        $globalMethods = DB::table('global_payment_methods')->where('status', 1)->get();
        $allMethods = collect($localMethods)->merge($globalMethods);
        
        // Build payment methods map from database
        $paymentMethodLabels = [];
        foreach($allMethods as $method) {
            $paymentMethodLabels[$method->code] = $method->name;
        }
    } catch (\Exception $e) {
        // If query fails, use fallback
        $paymentMethodLabels = [];
    }
    
    // If payment method labels is empty, use fallback
    if (empty($paymentMethodLabels)) {
        $paymentMethodLabels = [
            'CASH' => 'Uang Tunai',
            'COD_QRIS' => 'COD dengan QRIS/E-Wallet',
            'QRIS' => 'QRIS',
            'EWALLET' => 'E-Wallet',
            'TRANSFER' => 'Transfer Bank',
            'VA' => 'Virtual Account'
        ];
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
        'KURIR_TOKO' => 'Pengiriman langsung dari toko Azka Garden. Ongkir flat sesuai jarak: (<5km) Rp10.000, (5-10km) Rp15.000, (>10km) Rp20.000',
        'GOSEND' => 'Pengiriman cepat via GoSend (estimasi Rp15.000-30.000 sesuai aplikasi)',
        'JNE' => 'Pengiriman reguler via JNE (8.000-20.000/kg, estimasi aplikasi atau admin)',
        'JNT' => 'Pengiriman reguler via J&T (10.000-22.000/kg, estimasi aplikasi atau admin)',
        'SICEPAT' => 'Pengiriman reguler via SiCepat (10.000-18.000/kg, estimasi aplikasi atau admin)',
        'AMBIL_SENDIRI' => 'Ambil langsung di toko Azka Garden, bebas ongkir!',
    ];
    
    // Get readable shipping method name
    $shipping_method_display = $shippingMethodLabels[$shipping_method] ?? $shipping_method;
    
    // Default shipping cost
    $shippingCost = 0;
    $selectedAddress = null;
    
    // Get the promo code from session
    $promo_code = session('promo_code');
    
    // Make sure we have an address selected
    if($addresses->count()) {
        // Select address: first try to get from shipping_address_id, then primary address, then first address
        $selectedAddress = $shipping_address_id 
            ? $addresses->where('id', $shipping_address_id)->first() 
            : ($addresses->where('is_primary', 1)->first() ?? $addresses->first());
    }
    
    // Fetch shipping costs directly from the database
    try {
        $shippingData = DB::table('shippings')
            ->select('courier', 'shipping_cost')
            ->get();
        
        // Map shipping costs to array
        $shippingCostsFromDB = [];
        foreach ($shippingData as $data) {
            // Normalize courier name - replace space with underscore and uppercase
            $courier = strtoupper(str_replace(' ', '_', $data->courier));
            $shippingCostsFromDB[$courier] = floatval($data->shipping_cost);
        }
    } catch (\Exception $e) {
        // If query fails, use empty array
        $shippingCostsFromDB = [];
    }
    
    // Fallback shipping costs from SQL file if DB query doesn't provide complete data
    $fixedShippingCosts = [
        'KURIR_TOKO' => 10000, // Base cost (<5km) - adjusts based on distance
        'GOSEND' => 25000,
        'JNE' => 12000,
        'JNT' => 14000,
        'SICEPAT' => 15000,
        'AMBIL_SENDIRI' => 0
    ];
    
    // IMPORTANT: Handle 'AMBIL_SENDIRI' case first to ensure it's always free
    if ($shipping_method == 'AMBIL_SENDIRI') {
        $shippingCost = 0; // Always free for pickup
    }
    // Check if shipping cost is already in session
    elseif ($shipping_cost_session !== null) {
        $shippingCost = $shipping_cost_session;
    }
    // Otherwise calculate based on shipping method
    else {
        // First check if we have a value from the database
        $courier = strtoupper(str_replace(' ', '_', $shipping_method));
        
        if (isset($shippingCostsFromDB[$courier])) {
            $shippingCost = $shippingCostsFromDB[$courier];
        }
        // If not in database, calculate based on method with fallbacks
        else {
            switch ($shipping_method) {
                case 'KURIR_TOKO':
                    // Handle different distance tiers for KURIR_TOKO
                    $shippingCost = $fixedShippingCosts['KURIR_TOKO']; // Default to base cost
                    
                    // If we have selected address and coordinates, adjust based on distance
                    if ($selectedAddress && isset($selectedAddress->latitude) && isset($selectedAddress->longitude)) {
                        $lat = floatval($selectedAddress->latitude);
                        $lng = floatval($selectedAddress->longitude);
                        
                        // Calculate distance using Haversine formula (approximate)
                        $distance = sqrt(pow($lat - $toko_lat, 2) + pow($lng - $toko_lng, 2)) * 111.32; // km
                        
                        // Apply tiered pricing based on distance
                        if ($distance > 10) {
                            $shippingCost = 20000; // > 10km (from shippings.sql record 2003)
                        } elseif ($distance > 5) {
                            $shippingCost = 15000; // 5-10km (from shippings.sql record 2002)
                        } else {
                            $shippingCost = 10000; // < 5km (from shippings.sql record 2001)
                        }
                    } else {
                        // No coordinates, default to middle tier (most common)
                        $shippingCost = 15000; // Default to 5-10km rate
                    }
                    break;
                    
                case 'GOSEND':
                    $shippingCost = $fixedShippingCosts['GOSEND']; // 25000 from shippings.sql
                    break;
                    
                case 'JNE':
                    $shippingCost = $fixedShippingCosts['JNE']; // 12000 from shippings.sql
                    break;
                    
                case 'JNT':
                    $shippingCost = $fixedShippingCosts['JNT']; // 14000 from shippings.sql
                    break;
                    
                case 'SICEPAT':
                    $shippingCost = $fixedShippingCosts['SICEPAT']; // 15000 from shippings.sql
                    break;
                    
                default:
                    $shippingCost = 0;
            }
        }
    }
    
    // Double-check for AMBIL_SENDIRI to ensure it's always free regardless of DB or session values
    if ($shipping_method == 'AMBIL_SENDIRI') {
        $shippingCost = 0;
    }
    
    // Store the calculated shipping cost in session for future use
    Session::put('shipping_cost', $shippingCost);
    
    // Calculate cart totals and apply discounts
    $subtotal = 0;
    $total_discount = 0;
    $original_price_total = 0;
    
    if (isset($cartItems) && $cartItems->count()) {
        foreach($cartItems as $item) {
            $product = $item->product ?? null;
            if (!$product) continue;
            
            // Cek promo code dari item atau dari session
            $item_promo = $item->promo_code ?? $promo_code;
            $promotion = $item_promo ? \App\Models\Promotion::where('promo_code', $item_promo)->first() : null;
            $discount = 0;
            $unit_price = $product->price ?? 0;
            $qty = $item->quantity ?? 0;
            
            // Track original price for display purposes (harga sebelum diskon)
            $original_price_total += $unit_price * $qty;
            
            // Apply discount if promotion exists
            if ($promotion) {
                if ($promotion->discount_type === 'percent') {
                    $percent = $promotion->discount_value ?: 10;
                    $discount = round($unit_price * ($percent / 100));
                } elseif ($promotion->discount_type === 'fixed') {
                    $discount = min($promotion->discount_value ?: 0, $unit_price);
                }
            }
            
            // Calculate final price after discount
            $discounted_price = max(0, $unit_price - $discount);
            $item_total = $discounted_price * $qty;
            
            // Add to totals
            $subtotal += $item_total;
            $total_discount += $discount * $qty;
        }
    } elseif (isset($order) && isset($order->details) && count($order->details)) {
        // If we have an order object instead of cart items
        $subtotalProducts = 0;
        $hargaCoret = 0;
        
        foreach($order->details as $detail) {
            // Calculate original price and discounted price
            $hargaCoret += isset($detail->product->price) ? $detail->product->price * $detail->quantity : $detail->subtotal;
            $subtotalProducts += $detail->subtotal;
        }
        
        $subtotal = $subtotalProducts;
        $total_discount = $hargaCoret - $subtotalProducts;
        $original_price_total = $hargaCoret;
    }
    
    // Calculate all costs
    $handlingFee = 0;   // Could be added if needed
    $paymentFee = 0;    // Could be calculated based on payment method
    
    // Calculate subtotals and tax
    $totalBeforeTax = $subtotal + $handlingFee + $shippingCost + $paymentFee;
    $taxAmount = round($totalBeforeTax * 0.11); // PPN 11%
    $totalWithTax = $totalBeforeTax + $taxAmount;
    
    // Store calculations in session for order creation
    Session::put('order_summary', [
        'subtotal' => $subtotal,
        'original_total' => $original_price_total,
        'discount' => $total_discount,
        'handling_fee' => $handlingFee,
        'shipping_cost' => $shippingCost,
        'payment_fee' => $paymentFee,
        'tax_amount' => $taxAmount,
        'total' => $totalWithTax,
        'shipping_method' => $shipping_method,
        'shipping_method_display' => $shipping_method_display
    ]);
?>

<style>
:root {
    --green: #166534;
    --green-light: #15803d;
    --green-dark: #14532d;
    --white: #fff;
    --gray: #888e92;
    --gray-soft: #e5e7eb;
    --gray-dark: #374151;
    --black: #111827;
    --alert-red: #b91c1c;
    --alert-bg: #fef2f2;
    --warning-color: #b45309;
    --warning-bg: #fff3cd;
}

.confirm-container {
    max-width: 768px;
    margin: 2rem auto 3.5rem;
    padding: 0;
    background: var(--white);
}

.confirm-header {
    text-align: center;
    margin-bottom: 2rem;
}

.confirm-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--green);
    margin-bottom: 0.5rem;
}

.order-card {
    background: var(--white);
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--gray-soft);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--green);
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--gray-soft);
    padding-bottom: 0.5rem;
}

.card-header-icon {
    color: var(--green-light);
    display: flex;
    align-items: center;
}

.info-block {
    margin-bottom: 1rem;
    color: var(--gray-dark);
    line-height: 1.5;
}

.info-block strong {
    color: var(--black);
    font-weight: 600;
}

.info-box {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.info-box-title {
    color: var(--green);
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-warning {
    background-color: var(--warning-bg);
    border: 1px solid #ffeeba;
    color: var(--warning-color);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.shipping-method-highlight {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-top: 0.75rem;
    color: var(--green);
    font-weight: 500;
}

.free-shipping-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    background: #15803d;
    color: white;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}

.product-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.product-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-soft);
}

.product-item:last-child {
    border-bottom: none;
}

.product-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.product-image {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    object-fit: cover;
    border: 1px solid var(--gray-soft);
}

.product-details {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: var(--green-dark);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 250px;
}

.product-quantity {
    color: var(--gray);
    font-size: 0.875rem;
}

.product-price {
    font-weight: 600;
    color: var(--green);
    text-align: right;
}

.price-strikethrough {
    color: var(--gray) !important;
    text-decoration: line-through;
    margin-right: 0.5rem;
}

.summary-list {
    margin: 1rem 0;
    padding: 0;
    list-style: none;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    color: var(--gray-dark);
}

.summary-label {
    font-weight: 500;
}

.summary-value {
    font-weight: 500;
    text-align: right;
}

.summary-item.discount,
.summary-item.tax {
    color: var(--gray);
}

.summary-total {
    border-top: 1px solid var(--gray-soft);
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    font-weight: 700;
    color: var(--green);
    font-size: 1.125rem;
}

.qris-section {
    margin: 1.5rem 0;
    padding: 1.5rem;
    background-color: #f0fdf4;
    border-radius: 0.5rem;
    border: 1px solid #dcfce7;
    text-align: center;
}

.qris-title {
    font-weight: 700;
    color: var(--green);
    margin-bottom: 1rem;
}

.qr-container {
    margin: 1rem auto;
    padding: 1rem;
    background-color: white;
    border-radius: 0.5rem;
    border: 1px solid var(--gray-soft);
    width: 220px;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-instructions {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: var(--gray-dark);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.75rem;
    margin-left: 0.5rem;
}

.status-pending {
    background-color: var(--warning-bg);
    color: var(--warning-color);
}

.status-canceled {
    background-color: var(--gray-soft);
    color: var(--gray);
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
}

.button {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: background-color 0.2s ease;
    text-align: center;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
}

.button-primary {
    background: var(--green);
    color: white;
    flex: 1;
    margin-left: 1rem;
}

.button-primary:hover {
    background: var(--green-dark);
}

.button-secondary {
    background: white;
    color: var(--gray-dark);
    border: 1px solid var(--gray-soft);
}

.button-secondary:hover {
    background: #f9fafb;
}

.empty-message {
    text-align: center;
    padding: 3rem 0;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--green);
    margin-bottom: 1.5rem;
}

.promo-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    background: #f0fdf4;
    color: var(--green);
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: 1px solid var(--green-light);
    margin-top: 0.5rem;
}

/* Remove all animations */
@media (max-width: 768px) {
    .confirm-container {
        margin: 1rem auto 2rem;
    }
    
    .product-name {
        max-width: 150px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 1rem;
    }
    
    .button-primary {
        margin-left: 0;
    }
}
</style>

<div class="confirm-container">
    <div class="confirm-header">
        <h1 class="confirm-title">Konfirmasi Pesanan</h1>
        <?php if($promo_code): ?>
            <div class="promo-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <span>Promo: <?php echo e($promo_code); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Informasi Pengirim (Toko) -->
    <div class="order-card">
        <div class="card-header">
            <span>Informasi Pengirim</span>
            <div class="card-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
        </div>
        <div class="info-block">
            <div><strong>Nama Pengirim:</strong> <?php echo e($toko_name); ?></div>
            <div><strong>Alamat Pengirim:</strong> <?php echo e($toko_address); ?></div>
        </div>
    </div>

    <!-- Profile & alamat user -->
    <div class="order-card">
        <div class="card-header">
            <span>Profil & Alamat Pengiriman</span>
            <div class="card-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
        </div>
        <div class="info-block">
            <div><strong>Nama:</strong> <?php echo e($user->name ?? '-'); ?></div>
            <div><strong>Email:</strong> <?php echo e($user->email ?? '-'); ?></div>
            <div><strong>No HP:</strong> <?php echo e($user->phone ?? '-'); ?></div>
        </div>
        
        <?php if($selectedAddress): ?>
        <div class="info-box">
            <div class="info-box-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Alamat Pengiriman
            </div>
            <div><strong>Label:</strong> <?php echo e($selectedAddress->label ?? 'Alamat Utama'); ?></div>
            <div><strong>Penerima:</strong> <?php echo e($selectedAddress->recipient); ?></div>
            <div><strong>Alamat:</strong> <?php echo e($selectedAddress->full_address); ?></div>
            <div><strong>Kota:</strong> <?php echo e($selectedAddress->city); ?>, <strong>Kode Pos:</strong> <?php echo e($selectedAddress->zip_code); ?></div>
            <div><strong>No HP:</strong> <?php echo e($selectedAddress->phone_number); ?></div>
        </div>
        <?php else: ?>
        <div class="alert-warning">
            <strong>Perhatian:</strong> Alamat pengiriman belum dipilih. Silahkan tambahkan alamat di profil Anda.
        </div>
        <?php endif; ?>
    </div>

    <!-- Metode Pengiriman -->
    <div class="order-card">
        <div class="card-header">
            <span>Metode Pengiriman</span>
            <div class="card-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
        </div>
        <div class="info-block">
            <div><strong><?php echo e($shipping_method_display); ?></strong></div>
            <div><?php echo e($shippingMethodDescriptions[$shipping_method] ?? 'Pengiriman standar'); ?></div>
        </div>
        
        <?php if($shipping_method == 'KURIR_TOKO'): ?>
            <div class="shipping-method-highlight">
                <?php if($shippingCost == 10000): ?>
                    Kurir Toko | Jarak < 5km | Ongkir: Rp10.000
                <?php elseif($shippingCost == 15000): ?>
                    Kurir Toko | Jarak 5-10km | Ongkir: Rp15.000
                <?php elseif($shippingCost == 20000): ?>
                    Kurir Toko | Jarak > 10km | Ongkir: Rp20.000
                <?php else: ?>
                    Kurir Toko | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

                <?php endif; ?>
            </div>
        <?php elseif($shipping_method == 'AMBIL_SENDIRI'): ?>
            <div class="shipping-method-highlight">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 4px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Gratis (Rp0)
                <span class="free-shipping-badge">BEBAS ONGKIR</span>
            </div>
        <?php elseif($shipping_method == 'JNT'): ?>
            <div class="shipping-method-highlight">
                J&T EZ | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

            </div>
        <?php elseif($shipping_method == 'SICEPAT'): ?>
            <div class="shipping-method-highlight">
                SiCepat BEST | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

            </div>
        <?php elseif($shipping_method == 'JNE'): ?>
            <div class="shipping-method-highlight">
                JNE REG | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

            </div>
        <?php elseif($shipping_method == 'GOSEND'): ?>
            <div class="shipping-method-highlight">
                GoSend Sameday | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

            </div>
        <?php else: ?>
            <div class="shipping-method-highlight">
                <?php echo e($shipping_method_display); ?> | Ongkir: Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- RINGKASAN PESANAN - KERANJANG -->
    <?php if(isset($cartItems) && $cartItems->count()): ?>
        <div class="order-card">
            <div class="card-header">
                <span>Ringkasan Keranjang</span>
                <div class="card-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
            </div>
            <ul class="product-list">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $product = $item->product ?? null;
                        if (!$product) continue;
                        
                        $item_promo = $item->promo_code ?? $promo_code;
                        $promotion = $item_promo ? \App\Models\Promotion::where('promo_code', $item_promo)->first() : null;
                        $discount = 0;
                        $unit_price = $product->price ?? 0;
                        $qty = $item->quantity ?? 0;
                        
                        // Calculate discount if applicable
                        if ($promotion) {
                            if ($promotion->discount_type === 'percent') {
                                $percent = $promotion->discount_value ?: 10;
                                $discount = round($unit_price * ($percent / 100));
                            } elseif ($promotion->discount_type === 'fixed') {
                                $discount = min($promotion->discount_value ?: 0, $unit_price);
                            }
                        }
                        
                        $discounted_price = max(0, $unit_price - $discount);
                        $item_total = $discounted_price * $qty;
                        $image_url = $product->image_url ?? 'images/no-image.png';
                    ?>
                    <li class="product-item">
                        <div class="product-content">
                            <img src="<?php echo e(asset($image_url)); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
                            <div class="product-details">
                                <div class="product-name"><?php echo e($product->name); ?></div>
                                <div class="product-quantity">x<?php echo e($qty); ?></div>
                                <?php if($item_promo && $promotion): ?>
                                    <div class="promo-badge" style="font-size: 0.75rem; padding: 0.15rem 0.5rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                        <span><?php echo e($item_promo); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="product-price">
                            <?php if($discount > 0): ?>
                                <span class="price-strikethrough">Rp<?php echo e(number_format($unit_price * $qty, 0, ',', '.')); ?></span>
                                Rp<?php echo e(number_format($item_total, 0, ',', '.')); ?>

                            <?php else: ?>
                                Rp<?php echo e(number_format($item_total, 0, ',', '.')); ?>

                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="info-block">
                <div><strong>Alamat Pengiriman:</strong> <?php echo e($selectedAddress ? $selectedAddress->full_address : 'Belum dipilih'); ?></div>
                <div><strong>Metode Pengiriman:</strong> <?php echo e($shipping_method_display); ?> 
                <?php if($shipping_method == 'AMBIL_SENDIRI'): ?>
                    (Gratis)
                <?php else: ?>
                    (Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>)
                <?php endif; ?>
                </div>
                <div><strong>Metode Pembayaran:</strong> <?php echo e($paymentMethodLabels[$payment_method] ?? $payment_method); ?></div>
            </div>
        </div>
        
        <div class="order-card">
            <div class="card-header">
                <span>Rincian Biaya</span>
                <div class="card-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                        <path d="M7 15h0M2 9.5h20"></path>
                    </svg>
                </div>
            </div>
            <ul class="summary-list">
                <li class="summary-item">
                    <span class="summary-label">Total Produk</span>
                    <span class="summary-value">Rp<?php echo e(number_format($original_price_total, 0, ',', '.')); ?></span>
                </li>
                <?php if($total_discount > 0): ?>
                    <li class="summary-item discount">
                        <span class="summary-label">Diskon</span>
                        <span class="summary-value">-Rp<?php echo e(number_format($total_discount, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <?php if($handlingFee > 0): ?>
                    <li class="summary-item">
                        <span class="summary-label">Biaya Penanganan</span>
                        <span class="summary-value">Rp<?php echo e(number_format($handlingFee, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <li class="summary-item">
                    <span class="summary-label">Ongkos Kirim</span>
                    <?php if($shipping_method == 'AMBIL_SENDIRI'): ?>
                        <span class="summary-value"><strong style="color: #15803d;">Gratis (Rp0)</strong></span>
                    <?php else: ?>
                        <span class="summary-value">Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></span>
                    <?php endif; ?>
                </li>
                <?php if($paymentFee > 0): ?>
                    <li class="summary-item">
                        <span class="summary-label">Biaya Pembayaran</span>
                        <span class="summary-value">Rp<?php echo e(number_format($paymentFee, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <li class="summary-item tax">
                    <span class="summary-label">PPN 11%</span>
                    <span class="summary-value">Rp<?php echo e(number_format($taxAmount, 0, ',', '.')); ?></span>
                </li>
                <li class="summary-item summary-total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?></span>
                </li>
                <li class="summary-item" style="font-size: 0.75rem; color: var(--gray);">
                    <span class="summary-label">*sudah termasuk pajak</span>
                    <span class="summary-value"></span>
                </li>
            </ul>
        </div>

        <?php if($payment_method === 'QRIS'): ?>
            <div class="qris-section">
                <div class="qris-title">Pembayaran dengan QRIS</div>
                <div class="qr-container">
                    <svg width="180" height="180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                        <path d="M73,20H20v53h53V20z M111,20H85v27h26V20z M137,20h-16v27h16V20z M177,20h-30v53h30V20z M73,83H20v17h53V83z M127,83H85v17h42V83z M177,83h-40v17h40V83z M73,110H20v20h53V110z M153,110H85v20h68V110z M177,110h-14v20h14V110z M73,140H20v16h53V140z M111,140H85v16h26V140z M137,140h-16v16h16V140z M177,140h-30v40h30V140z M53,166H20v14h33V166z M80,166H63v14h17V166z" fill="#33bb33"/>
                    </svg>
                </div>
                <div class="qr-instructions">
                    Scan QR Code di atas dengan aplikasi e-wallet Anda<br>
                    (OVO, GoPay, DANA, LinkAja, dll)
                </div>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="<?php echo e(route('user.cart.index')); ?>" class="button button-secondary" id="back-to-cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
            
            <form id="createOrderForm" action="<?php echo e(route('user.orders.create')); ?>" method="POST" style="margin:0; flex: 1;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="shipping_method" value="<?php echo e($shipping_method); ?>">
                <?php if($selectedAddress): ?>
                <input type="hidden" name="shipping_address_id" value="<?php echo e($selectedAddress->id); ?>">
                <?php endif; ?>
                <input type="hidden" name="payment_method" value="<?php echo e($payment_method); ?>">
                <input type="hidden" name="shipping_cost" value="<?php echo e($shippingCost); ?>">
                <input type="hidden" name="subtotal" value="<?php echo e($subtotal); ?>">
                <input type="hidden" name="discount" value="<?php echo e($total_discount); ?>">
                <input type="hidden" name="tax_amount" value="<?php echo e($taxAmount); ?>">
                <input type="hidden" name="total" value="<?php echo e($totalWithTax); ?>">
                <button type="submit" class="button button-primary">
                    Konfirmasi Pembayaran
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </button>
            </form>
        </div>

    <?php elseif(isset($order) && isset($order->details) && count($order->details)): ?>
        <?php
            // Fetch order status from database if possible
            try {
                $orderStatusValues = DB::table('enum_order_status')->pluck('value', 'id')->toArray();
            } catch (\Exception $e) {
                $orderStatusValues = [];
            }
            
            $statusVal = is_object($order->status)
                ? $order->status->value
                : (is_array($order->status) ? ($order->status['value'] ?? $order->status[0] ?? $order->status) : $order->status);
                
            // Convert numeric enum_order_status_id to string value if needed
            if (is_numeric($statusVal) && isset($orderStatusValues[$statusVal])) {
                $statusVal = $orderStatusValues[$statusVal];
            }
            
            $isCanceledOrExpired = in_array(strtoupper($statusVal), ['CANCELED','FAILED','EXPIRED']);

            // Convert payment method code to readable text
            $displayPaymentMethod = $paymentMethodLabels[$order->payment_method] ?? $order->payment_method;
            
            // Get shipping method from order
            $orderShippingMethod = $shippingMethodLabels[$order->shipping_method] ?? $order->shipping_method;
            
            // Get shipping cost from order if available
            if (isset($order->shipping_cost)) {
                $shippingCost = $order->shipping_cost;
            } elseif (isset($order->shipping) && isset($order->shipping->shipping_cost)) {
                $shippingCost = $order->shipping->shipping_cost;
            }
            
            // Make sure AMBIL_SENDIRI is always free
            if ($order->shipping_method == 'AMBIL_SENDIRI') {
                $shippingCost = 0;
            }
        ?>

        <div class="order-card">
            <div class="card-header">
                <span>Ringkasan Pesanan</span>
                <div class="card-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
            </div>
            <ul class="product-list">
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $hasDiscount = isset($detail->product->price) && $detail->product->price > $detail->subtotal;
                        $image_url = $detail->product->image_url ?? 'images/no-image.png';
                    ?>
                    <li class="product-item">
                        <div class="product-content">
                            <img src="<?php echo e(asset($image_url)); ?>" alt="<?php echo e($detail->product->name ?? 'Produk'); ?>" class="product-image">
                            <div class="product-details">
                                <div class="product-name"><?php echo e($detail->product->name ?? 'Produk'); ?></div>
                                <div class="product-quantity">x<?php echo e($detail->quantity); ?></div>
                            </div>
                        </div>
                        <div class="product-price">
                            <?php if($hasDiscount): ?>
                                <span class="price-strikethrough">Rp<?php echo e(number_format($detail->product->price * $detail->quantity, 0, ',', '.')); ?></span>
                                Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                            <?php else: ?>
                                Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="info-block">
                <div><strong>Alamat Pengiriman:</strong>
                    <?php if($order->shipping_address): ?>
                        <?php echo e(is_string($order->shipping_address) ? $order->shipping_address : json_encode($order->shipping_address)); ?>

                    <?php elseif($selectedAddress): ?>
                        <?php echo e($selectedAddress->full_address ?? '-'); ?>

                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
                <div><strong>Metode Pengiriman:</strong> <?php echo e($orderShippingMethod); ?> 
                <?php if($order->shipping_method == 'AMBIL_SENDIRI'): ?>
                    (Gratis)
                <?php else: ?>
                    (Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>)
                <?php endif; ?>
                </div>
                <div><strong>Metode Pembayaran:</strong> <?php echo e($displayPaymentMethod); ?></div>
                <div>
                    <strong>Status:</strong>
                    <?php if($statusVal == 'WAITING_PAYMENT' || $statusVal == 'PENDING'): ?>
                        <span class="status-badge status-pending">Menunggu Pembayaran</span>
                    <?php elseif($isCanceledOrExpired): ?>
                        <span class="status-badge status-canceled">Dibatalkan</span>
                    <?php else: ?>
                        <span class="status-badge"><?php echo e(ucfirst(strtolower($statusVal))); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="order-card">
            <div class="card-header">
                <span>Rincian Biaya</span>
                <div class="card-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                        <path d="M7 15h0M2 9.5h20"></path>
                    </svg>
                </div>
            </div>
            <ul class="summary-list">
                <li class="summary-item">
                    <span class="summary-label">Total Produk</span>
                    <span class="summary-value">
                        <?php if($original_price_total > $subtotal): ?>
                            <span class="price-strikethrough">Rp<?php echo e(number_format($original_price_total, 0, ',', '.')); ?></span>
                            Rp<?php echo e(number_format($subtotal, 0, ',', '.')); ?>

                        <?php else: ?>
                            Rp<?php echo e(number_format($subtotal, 0, ',', '.')); ?>

                        <?php endif; ?>
                    </span>
                </li>
                <?php if($total_discount > 0): ?>
                    <li class="summary-item discount">
                        <span class="summary-label">Diskon</span>
                        <span class="summary-value">-Rp<?php echo e(number_format($total_discount, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <?php if($handlingFee > 0): ?>
                    <li class="summary-item">
                        <span class="summary-label">Biaya Penanganan</span>
                        <span class="summary-value">Rp<?php echo e(number_format($handlingFee, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <li class="summary-item">
                    <span class="summary-label">Ongkos Kirim</span>
                    <?php if($order->shipping_method == 'AMBIL_SENDIRI'): ?>
                        <span class="summary-value"><strong style="color: #15803d;">Gratis (Rp0)</strong></span>
                    <?php else: ?>
                        <span class="summary-value">Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?></span>
                    <?php endif; ?>
                </li>
                <?php if($paymentFee > 0): ?>
                    <li class="summary-item">
                        <span class="summary-label">Biaya Pembayaran</span>
                        <span class="summary-value">Rp<?php echo e(number_format($paymentFee, 0, ',', '.')); ?></span>
                    </li>
                <?php endif; ?>
                <li class="summary-item tax">
                    <span class="summary-label">PPN 11%</span>
                    <span class="summary-value">Rp<?php echo e(number_format($taxAmount, 0, ',', '.')); ?></span>
                </li>
                <li class="summary-item summary-total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">
                        <?php if($isCanceledOrExpired): ?>
                            <span class="price-strikethrough">Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?></span>
                        <?php else: ?>
                            Rp<?php echo e(number_format($totalWithTax, 0, ',', '.')); ?>

                        <?php endif; ?>
                    </span>
                </li>
                <li class="summary-item" style="font-size: 0.75rem; color: var(--gray);">
                    <span class="summary-label">*sudah termasuk pajak</span>
                    <span class="summary-value"></span>
                </li>
            </ul>
        </div>

        <?php if($order->payment_method === 'QRIS'): ?>
            <div class="qris-section">
                <div class="qris-title">Pembayaran dengan QRIS</div>
                <div class="qr-container">
                    <svg width="180" height="180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                        <path d="M73,20H20v53h53V20z M111,20H85v27h26V20z M137,20h-16v27h16V20z M177,20h-30v53h30V20z M73,83H20v17h53V83z M127,83H85v17h42V83z M177,83h-40v17h40V83z M73,110H20v20h53V110z M153,110H85v20h68V110z M177,110h-14v20h14V110z M73,140H20v16h53V140z M111,140H85v16h26V140z M137,140h-16v16h16V140z M177,140h-30v40h30V140z M53,166H20v14h33V166z M80,166H63v14h17V166z" fill="#33bb33"/>
                    </svg>
                </div>
                <div class="qr-instructions">
                    Scan QR Code di atas dengan aplikasi e-wallet Anda<br>
                    (OVO, GoPay, DANA, LinkAja, dll)
                </div>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <?php if(!$isCanceledOrExpired && ($statusVal === 'WAITING_PAYMENT' || $statusVal === 'PENDING')): ?>
                <a href="<?php echo e(route('user.cart.index')); ?>" class="button button-secondary" id="back-to-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                
                <form id="payForm" action="<?php echo e(route('user.orders.pay', $order->id)); ?>" method="POST" style="margin:0; flex: 1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="button button-primary">
                        Konfirmasi Pembayaran
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                            <path d="M7 15h0M2 9.5h20"></path>
                        </svg>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('user.orders.index')); ?>" class="button button-primary" style="margin-left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Pesanan
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="empty-message">
            <h2 class="empty-title">Keranjang atau pesanan Anda kosong</h2>
            <a href="<?php echo e(route('user.cart.index')); ?>" class="button button-primary" id="back-to-cart" style="margin-left: 0; width: 200px; margin: 0 auto;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Kembali
            </a>
        </div>
    <?php endif; ?>
</div>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logging waktu dan user saat ini
    console.log('Current date and time: <?php echo e($currentDateTime); ?>');
    console.log('Current user: <?php echo e($currentUserLogin); ?>');
    
    // Handle form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            // No loading animation
        });
    });
    
    // Add click event to back button
    document.querySelectorAll('#back-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "<?php echo e(route('user.cart.index')); ?>";
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/user/orders/confirm.blade.php ENDPATH**/ ?>