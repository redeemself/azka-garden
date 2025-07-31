@extends('layouts.app')

@section('title', 'Checkout - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @php
        $user = auth()->user();
        $cartItems = \App\Models\Cart::with(['product', 'product.images'])
            ->where('user_id', $user->id)
            ->get();
        $promo_code = session('promo_code') ?? '';
        $localMethods = \DB::table('local_payment_methods')->where('status', 1)->get();
        $globalMethods = \DB::table('global_payment_methods')->where('status', 1)->get();
        $allMethods = collect($localMethods)->merge($globalMethods);
        $selected_payment = old('payment_method', session('payment_method') ?? ($allMethods->first()->code ?? null));
        $shippingMethods = [
            [
                'code' => 'KURIR_TOKO',
                'name' => 'Kurir Toko',
                'desc' =>
                    'Pengiriman langsung dari toko Azka Garden. <b>Ongkir flat sesuai jarak:</b> <br><span style="color:#047857;font-size:0.98em;">(&lt;5km) Rp10.000, (5-10km) Rp15.000, (&gt;10km) Rp20.000</span>',
                'icon' => 'truck',
            ],
            [
                'code' => 'GOSEND',
                'name' => 'GoSend Sameday',
                'desc' => 'Kirim instan via GoSend (estimasi Rp15.000-30.000 sesuai aplikasi)',
                'icon' => 'bicycle',
            ],
            [
                'code' => 'JNE',
                'name' => 'JNE REG',
                'desc' => 'Reguler via JNE (8.000-20.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'box',
            ],
            [
                'code' => 'JNT',
                'name' => 'J&T EZ',
                'desc' => 'J&T EZ (10.000-22.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'truck-fast',
            ],
            [
                'code' => 'SICEPAT',
                'name' => 'SiCepat BEST',
                'desc' => 'SiCepat BEST (10.000-18.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'bolt',
            ],
            [
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri di Toko',
                'desc' => 'Ambil langsung ke Azka Garden, <b style="color:#16a34a;">bebas ongkir</b>!',
                'icon' => 'store',
            ],
        ];
        $selected_shipping = old('shipping_method', session('shipping_method') ?? $shippingMethods[0]['code']);

        // Properly get user addresses
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();

        // Get primary address or first address
        $primaryAddress = null;
        if ($hasAddress) {
            $primaryAddress = $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first();
        }

        // Set store location coordinates - Toko Bunga Hendrik di Jalan Raya KSU
        $storeLocation = [
            'lat' => -6.4122794,
            'lng' => 106.829692,
            'address' => 'Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412',
            'plus_code' => 'HRQH+3VP',
        ];

        // Pajak 10%
        $tax_rate = 0.1;

        // Calculate totals
        $grand_total = 0;
        $total_discount = 0;
        $total_weight = 0;

        foreach ($cartItems as $item) {
            $promo = $item->promo_code ?? $promo_code;
            $promotion = $promo ? \App\Models\Promotion::where('promo_code', $promo)->first() : null;
            $discount = 0;
            $unit_price = $item->product->price ?? 0;
            $qty = $item->quantity ?? 0;

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
            $grand_total += $item_total;
            $total_discount += $discount * $qty;
            $total_weight += ($item->product->weight ?? 0) * $qty;
        }

        // Hitung pajak 10%
        $tax_total = round($grand_total * $tax_rate);

        // Total akhir termasuk pajak (tanpa shipping yang akan dikalkulasi dengan JS)
        $final_total = $grand_total + $tax_total;
    @endphp

    <style>
        /* Styling for checkout page */
        :root {
            --primary: #166534;
            --primary-light: #16a34a;
            --primary-dark: #14532d;
            --primary-bg: #f0fdf4;
            --primary-bg-hover: #dcfce7;
            --accent: #4ade80;
            --white: #ffffff;
            --black: #111827;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --error: #b91c1c;
            --error-bg: #fee2e2;
            --warning: #ea580c;
            --warning-bg: #ffedd5;
            --success: #16a34a;
            --success-bg: #dcfce7;
            --info: #0284c7;
            --info-bg: #e0f2fe;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto 3rem;
            padding: 0 1rem;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .checkout-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .checkout-header p {
            color: var(--gray-600);
            font-size: 1.1rem;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .checkout-main {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .checkout-panel {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .checkout-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .checkout-panel-header {
            background: var(--primary-bg);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .checkout-panel-header h2 {
            color: var(--primary);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkout-panel-body {
            padding: 1.5rem;
        }

        /* Order Items */
        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .order-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .order-item-image {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            margin-right: 1rem;
        }

        .order-item-details {
            flex: 1;
        }

        .order-item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .order-item-price {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .order-item-quantity {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .order-item-total {
            font-weight: 600;
            color: var(--primary);
            white-space: nowrap;
        }

        /* Address Panel */
        .address-panel {
            background: var(--info-bg);
            border: 1px solid #bae6fd;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .address-panel h3 {
            color: var(--info);
            font-size: 1rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .address-card {
            background: var(--white);
            border: 1px solid #93c5fd;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .address-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .address-detail {
            color: var(--gray-600);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        /* Shipping Methods */
        .shipping-methods {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .shipping-method {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .shipping-method:hover {
            border-color: var(--primary-light);
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        .shipping-method.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        .shipping-method-radio {
            margin-top: 0.25rem;
            margin-right: 0.75rem;
            accent-color: var(--primary);
        }

        .shipping-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 0.5rem;
            margin-right: 0.75rem;
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .shipping-method.selected .shipping-method-icon {
            background: var(--primary);
            color: var(--white);
        }

        .shipping-method-details {
            flex: 1;
        }

        .shipping-method-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .shipping-method-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Payment Methods */
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-light);
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        .payment-method-radio {
            margin-right: 0.75rem;
            accent-color: var(--primary);
        }

        .payment-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 2rem;
            margin-right: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary);
            transition: all 0.2s ease;
        }

        .payment-method.selected .payment-method-icon {
            transform: scale(1.1);
        }

        .payment-method-details {
            flex: 1;
        }

        .payment-method-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        .payment-method-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Order Notes */
        .order-notes-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.2s ease;
            resize: vertical;
            min-height: 80px;
        }

        .order-notes-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        /* Order Summary */
        .order-summary {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: sticky;
            top: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            color: var(--gray-600);
        }

        .summary-row.summary-total {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.25rem;
            border-top: 1px solid var(--gray-200);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }

        /* Button Styles */
        .back-button {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
            transform: translateY(-1px);
            color: var(--gray-700);
            text-decoration: none;
        }

        .checkout-button {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            padding: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .checkout-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .checkout-button:active {
            transform: translateY(0);
        }

        .checkout-button:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            border: 4px solid var(--gray-200);
            border-radius: 50%;
            border-top: 4px solid var(--primary);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
                margin-top: 1rem;
            }
        }

        @media (max-width: 768px) {
            .checkout-container {
                margin-top: 1rem;
                padding: 0 0.5rem;
            }

            .checkout-header h1 {
                font-size: 1.75rem;
            }

            .checkout-panel-body {
                padding: 1rem;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .order-item-image {
                width: 50px;
                height: 50px;
            }
        }

        /* Error states */
        .error-message {
            background: var(--error-bg);
            color: var(--error);
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #fca5a5;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shipping-estimate {
            background: var(--primary-bg);
            border: 1px solid #a7f3d0;
            padding: 1rem;
            border-radius: 0.75rem;
            margin: 1rem 0;
        }

        .shipping-estimate-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shipping-estimate-text {
            color: var(--gray-600);
        }

        /* Map preview styles */
        .map-preview {
            height: 200px;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-top: 1rem;
            border: 1px solid var(--gray-300);
        }

        /* Tax highlight */
        .tax-highlight {
            color: var(--primary);
            font-weight: 500;
        }
    </style>

    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Konfirmasi pesanan Anda sebelum melanjutkan pembayaran</p>
        </div>

        @if ($cartItems->count() == 0)
            <div class="error-message">
                <i data-feather="alert-circle"></i>
                <div>
                    <strong>Keranjang Kosong</strong>
                    <p>Tidak ada item di keranjang Anda. Silakan <a href="{{ route('products.index') }}"
                            class="text-primary underline">belanja terlebih dahulu</a>.</p>
                </div>
            </div>
        @elseif(!$hasAddress)
            <div class="error-message">
                <i data-feather="map-pin"></i>
                <div>
                    <strong>Alamat Pengiriman Diperlukan</strong>
                    <p>Anda perlu menambahkan alamat pengiriman terlebih dahulu. <a
                            href="{{ route('user.address.create') }}" class="text-primary underline">Tambah Alamat</a>.</p>
                </div>
            </div>
        @else
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
                <input type="hidden" name="tax_amount" id="tax_amount" value="{{ $tax_total }}">
                <div class="checkout-grid">
                    <div class="checkout-main">
                        <!-- Order Items -->
                        <div class="checkout-panel">
                            <div class="checkout-panel-header">
                                <h2>
                                    <i data-feather="shopping-bag"></i>
                                    Ringkasan Pesanan ({{ $cartItems->count() }} item)
                                </h2>
                            </div>
                            <div class="checkout-panel-body">
                                @foreach ($cartItems as $item)
                                    @php
                                        $promo = $item->promo_code ?? $promo_code;
                                        $promotion = $promo
                                            ? \App\Models\Promotion::where('promo_code', $promo)->first()
                                            : null;
                                        $discount = 0;
                                        $unit_price = $item->product->price ?? 0;
                                        $qty = $item->quantity ?? 0;

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
                                    @endphp
                                    <div class="order-item">
                                        <img src="{{ asset($item->product->image_url ?? ($item->product->images->first()->url ?? 'images/no-image.png')) }}"
                                            alt="{{ $item->product->name }}" class="order-item-image">
                                        <div class="order-item-details">
                                            <div class="order-item-name">{{ $item->product->name }}</div>
                                            <div class="order-item-price">
                                                @if ($discount > 0)
                                                    <span style="text-decoration: line-through; color: var(--gray-400);">Rp
                                                        {{ number_format($unit_price, 0, ',', '.') }}</span>
                                                @endif
                                                Rp {{ number_format($discounted_price, 0, ',', '.') }}
                                            </div>
                                            <div class="order-item-quantity">Qty: {{ $qty }}</div>
                                            @if ($promotion)
                                                <div class="mt-1" style="font-size: 0.8rem; color: var(--primary);">
                                                    <i data-feather="tag" style="width: 12px; height: 12px;"></i>
                                                    {{ $promotion->promo_code }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="order-item-total">Rp {{ number_format($item_total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="checkout-panel">
                            <div class="checkout-panel-header">
                                <h2>
                                    <i data-feather="map-pin"></i>
                                    Alamat Pengiriman
                                </h2>
                            </div>
                            <div class="checkout-panel-body">
                                <div class="address-panel">
                                    <div class="address-card">
                                        <div class="address-name">{{ $primaryAddress->label ?? 'Alamat Utama' }}</div>
                                        <div class="address-detail"><strong>{{ $primaryAddress->recipient }}</strong></div>
                                        <div class="address-detail">{{ $primaryAddress->full_address }}</div>
                                        <div class="address-detail">{{ $primaryAddress->city }},
                                            {{ $primaryAddress->zip_code }}</div>
                                        <div class="address-detail">{{ $primaryAddress->phone_number }}</div>
                                    </div>

                                    <!-- Menampilkan preview peta dan jarak -->
                                    <div id="map-container" class="map-preview mt-4">
                                        <!-- Map will be loaded here -->
                                    </div>
                                    <div id="distance-info" class="mt-2 text-sm text-gray-600">
                                        Menghitung jarak ke toko...
                                    </div>
                                </div>
                                <input type="hidden" name="shipping_address_id" value="{{ $primaryAddress->id }}">
                                <input type="hidden" id="customer_lat" name="customer_lat"
                                    value="{{ $primaryAddress->latitude ?? '' }}">
                                <input type="hidden" id="customer_lng" name="customer_lng"
                                    value="{{ $primaryAddress->longitude ?? '' }}">
                                <input type="hidden" id="distance_km" name="distance_km" value="0">
                            </div>
                        </div>

                        <!-- Shipping Methods -->
                        <div class="checkout-panel">
                            <div class="checkout-panel-header">
                                <h2>
                                    <i data-feather="truck"></i>
                                    Metode Pengiriman
                                </h2>
                            </div>
                            <div class="checkout-panel-body">
                                <div class="shipping-methods">
                                    @foreach ($shippingMethods as $method)
                                        <label
                                            class="shipping-method{{ $selected_shipping == $method['code'] ? ' selected' : '' }}">
                                            <input type="radio" name="shipping_method" value="{{ $method['code'] }}"
                                                class="shipping-method-radio"
                                                {{ $selected_shipping == $method['code'] ? 'checked' : '' }} required>
                                            <div class="shipping-method-icon">
                                                <i data-feather="{{ $method['icon'] }}"></i>
                                            </div>
                                            <div class="shipping-method-details">
                                                <div class="shipping-method-name">{!! $method['name'] !!}</div>
                                                <div class="shipping-method-desc">{!! $method['desc'] !!}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <div id="shippingCalc" class="shipping-estimate">
                                    <div class="shipping-estimate-title">
                                        <i data-feather="map"></i>
                                        Estimasi Ongkir
                                    </div>
                                    <div id="shippingEstimateText" class="shipping-estimate-text">
                                        Silakan pilih metode pengiriman untuk estimasi biaya kirim.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="checkout-panel">
                            <div class="checkout-panel-header">
                                <h2>
                                    <i data-feather="credit-card"></i>
                                    Metode Pembayaran
                                </h2>
                            </div>
                            <div class="checkout-panel-body">
                                @if ($allMethods->count())
                                    <div class="payment-methods">
                                        @foreach ($allMethods as $method)
                                            <label
                                                class="payment-method{{ $selected_payment === $method->code ? ' selected' : '' }}">
                                                <input type="radio" name="payment_method" value="{{ $method->code }}"
                                                    class="payment-method-radio"
                                                    {{ $selected_payment === $method->code ? 'checked' : '' }} required>
                                                <div class="payment-method-icon">
                                                    @switch($method->code)
                                                        @case('CASH')
                                                            <i data-feather="dollar-sign"></i>
                                                        @break

                                                        @case('COD_QRIS')
                                                            <i data-feather="smartphone"></i>
                                                        @break

                                                        @case('QRIS')
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M3 3H9V9H3V3Z" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M15 3H21V9H15V3Z" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M3 15H9V21H3V15Z" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M15 15H21V21H15V15Z" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        @break

                                                        @case('EWALLET')
                                                            <i data-feather="smartphone"></i>
                                                        @break

                                                        @default
                                                            <i data-feather="credit-card"></i>
                                                    @endswitch
                                                </div>
                                                <div class="payment-method-details">
                                                    <div class="payment-method-name">{{ $method->name }}</div>
                                                    {{-- Safe check untuk description --}}
                                                    @if (property_exists($method, 'description') && $method->description)
                                                        <div class="payment-method-desc">{{ $method->description }}</div>
                                                    @elseif(isset($method->config))
                                                        @php
                                                            $desc = '';
                                                            if (is_array($method->config)) {
                                                                $desc = $method->config['desc'] ?? '';
                                                            } elseif (is_object($method->config)) {
                                                                $desc = $method->config->desc ?? '';
                                                            } else {
                                                                $json = @json_decode($method->config);
                                                                $desc = $json->desc ?? '';
                                                            }
                                                        @endphp
                                                        @if ($desc)
                                                            <div class="payment-method-desc">{{ $desc }}</div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="error-message">
                                        <i data-feather="alert-circle"></i>
                                        <div>
                                            <strong>Tidak ada metode pembayaran yang tersedia.</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="checkout-panel">
                            <div class="checkout-panel-header">
                                <h2>
                                    <i data-feather="message-square"></i>
                                    Catatan Pesanan (Opsional)
                                </h2>
                            </div>
                            <div class="checkout-panel-body">
                                <textarea name="note" class="order-notes-input"
                                    placeholder="Tambahkan catatan untuk pesanan Anda (misalnya: instruksi khusus, permintaan waktu pengiriman, dll.)">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="order-summary">
                        <div class="checkout-panel-header">
                            <h2>
                                <i data-feather="receipt"></i>
                                Ringkasan Pembayaran
                            </h2>
                        </div>
                        <div class="checkout-panel-body">
                            <!-- Back to Cart Button -->
                            <a href="{{ route('cart.index') }}" class="back-button">
                                <i data-feather="arrow-left"></i>
                                Kembali ke Keranjang
                            </a>

                            <div class="summary-row">
                                <span>Subtotal ({{ $cartItems->count() }} item)</span>
                                <span id="checkout-subtotal">Rp
                                    {{ number_format($grand_total + $total_discount, 0, ',', '.') }}</span>
                            </div>
                            @if ($total_discount > 0)
                                <div class="summary-row">
                                    <span>Diskon</span>
                                    <span id="checkout-discount" style="color: var(--success);">-Rp
                                        {{ number_format($total_discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="summary-row tax-highlight">
                                <span>Pajak (10%)</span>
                                <span id="checkout-tax">Rp {{ number_format($tax_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Berat Total</span>
                                <span>{{ number_format($total_weight, 1) }} kg</span>
                            </div>
                            <div class="summary-row">
                                <span>Ongkir</span>
                                <span id="checkout-shipping" data-value="0">Pilih metode pengiriman</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Total Pembayaran</span>
                                <span id="checkout-total">Rp {{ number_format($final_total, 0, ',', '.') }}</span>
                            </div>

                            @if ($allMethods->count())
                                <button type="submit" class="checkout-button">
                                    <i data-feather="credit-card"></i>
                                    Bayar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        /**
         * Checkout System
         * Enhanced checkout experience with validation and calculations
         */

        // Checkout state management
        const checkoutState = {
            subtotal: {{ $grand_total + $total_discount }},
            discount: {{ $total_discount }},
            shipping: 0,
            tax: {{ $tax_total }},
            total: {{ $final_total }},
            weight: {{ $total_weight }},

            updateTotal() {
                this.total = this.subtotal - this.discount + this.tax + this.shipping;
                this.displayTotal();

                // Update hidden shipping fee field
                const shippingFeeInput = document.getElementById('shipping_fee');
                if (shippingFeeInput) {
                    shippingFeeInput.value = this.shipping;
                }
            },

            displayTotal() {
                const totalElement = document.getElementById('checkout-total');
                const shippingElement = document.getElementById('checkout-shipping');
                const taxElement = document.getElementById('checkout-tax');

                if (totalElement) {
                    totalElement.textContent = `Rp ${this.formatNumber(this.total)}`;
                }

                if (shippingElement && this.shipping > 0) {
                    shippingElement.textContent = `Rp ${this.formatNumber(this.shipping)}`;
                    shippingElement.setAttribute('data-value', this.shipping);
                } else if (shippingElement) {
                    shippingElement.textContent = this.shipping === 0 ? 'Gratis' : 'Pilih metode pengiriman';
                }

                if (taxElement) {
                    taxElement.textContent = `Rp ${this.formatNumber(this.tax)}`;
                }
            },

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        };

        /**
         * Initialize map and distance calculation
         */
        function initMap() {
            // Koordinat toko - Azka Garden (Toko Bunga Hendrik di Jalan Raya KSU)
            const storeLat = {{ $storeLocation['lat'] }};
            const storeLng = {{ $storeLocation['lng'] }};
            const storeLocation = {
                lat: storeLat,
                lng: storeLng
            };

            // Koordinat pelanggan dari alamat tersimpan
            let customerLat = {{ $primaryAddress && $primaryAddress->latitude ? $primaryAddress->latitude : 'null' }};
            let customerLng = {{ $primaryAddress && $primaryAddress->longitude ? $primaryAddress->longitude : 'null' }};

            // Jika tidak ada koordinat tersimpan, coba geocode alamat
            if (!customerLat || !customerLng) {
                const customerAddress =
                    "{{ $primaryAddress ? $primaryAddress->full_address . ', ' . $primaryAddress->city . ', ' . $primaryAddress->zip_code : '' }}";

                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    address: customerAddress
                }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        customerLat = results[0].geometry.location.lat();
                        customerLng = results[0].geometry.location.lng();

                        // Set nilai koordinat ke input hidden
                        document.getElementById('customer_lat').value = customerLat;
                        document.getElementById('customer_lng').value = customerLng;

                        // Render peta dengan lokasi yang sudah digeocoding
                        renderMap(storeLocation, {
                            lat: customerLat,
                            lng: customerLng
                        });

                        // Hitung jarak dan perbarui tampilan
                        calculateDistance({
                            lat: customerLat,
                            lng: customerLng
                        }, storeLocation);
                    } else {
                        console.error('Geocode failed: ' + status);
                        // Render peta hanya dengan lokasi toko jika geocoding gagal
                        renderMap(storeLocation);

                        const distanceInfo = document.getElementById('distance-info');
                        if (distanceInfo) {
                            distanceInfo.innerHTML = 'Tidak dapat menghitung jarak karena alamat tidak lengkap.';
                        }
                    }
                });
            } else {
                // Jika koordinat sudah ada, langsung render peta
                renderMap(storeLocation, {
                    lat: customerLat,
                    lng: customerLng
                });

                // Hitung jarak dan perbarui tampilan
                calculateDistance({
                    lat: customerLat,
                    lng: customerLng
                }, storeLocation);
            }
        }

        /**
         * Render map with markers
         */
        function renderMap(storeLocation, customerLocation = null) {
            const mapContainer = document.getElementById('map-container');
            if (!mapContainer) return;

            const map = new google.maps.Map(mapContainer, {
                zoom: customerLocation ? 12 : 15,
                center: customerLocation ? midpoint(storeLocation, customerLocation) : storeLocation,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
            });

            // Marker untuk toko
            new google.maps.Marker({
                position: storeLocation,
                map: map,
                title: 'Azka Garden (Toko Bunga Hendrik)',
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                }
            });

            // Marker untuk alamat pelanggan jika tersedia
            if (customerLocation) {
                new google.maps.Marker({
                    position: customerLocation,
                    map: map,
                    title: 'Lokasi Pengiriman Anda',
                });

                // Gambar garis penghubung
                new google.maps.Polyline({
                    path: [storeLocation, customerLocation],
                    geodesic: true,
                    strokeColor: '#10b981',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    map: map
                });

                // Pastikan kedua marker terlihat
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(storeLocation);
                bounds.extend(customerLocation);
                map.fitBounds(bounds);
            }
        }

        /**
         * Calculate midpoint between two coordinates
         */
        function midpoint(point1, point2) {
            return {
                lat: (point1.lat + point2.lat) / 2,
                lng: (point1.lng + point2.lng) / 2
            };
        }

        /**
         * Calculate distance between customer and store
         */
        function calculateDistance(customerLocation, storeLocation) {
            const distanceService = new google.maps.DistanceMatrixService();
            distanceService.getDistanceMatrix({
                origins: [customerLocation],
                destinations: [storeLocation],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC,
            }, function(response, status) {
                const distanceInfo = document.getElementById('distance-info');

                if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
                    const distanceText = response.rows[0].elements[0].distance.text;
                    const distanceValue = response.rows[0].elements[0].distance.value / 1000; // km
                    const durationText = response.rows[0].elements[0].duration.text;

                    // Simpan jarak untuk perhitungan ongkir
                    document.getElementById('distance_km').value = distanceValue.toFixed(2);

                    if (distanceInfo) {
                        distanceInfo.innerHTML =
                            `<strong>Jarak ke toko:</strong> ${distanceText} (waktu tempuh: ${durationText})`;
                    }

                    // Update ongkir otomatis jika metode pengiriman adalah kurir toko
                    if (document.querySelector('input[name="shipping_method"]:checked')?.value === 'KURIR_TOKO') {
                        updateShippingFeeByDistance(distanceValue);
                    }
                } else {
                    if (distanceInfo) {
                        distanceInfo.innerHTML = 'Tidak dapat menghitung jarak secara akurat.';
                    }
                }
            });
        }

        /**
         * Update shipping fee based on distance
         */
        function updateShippingFeeByDistance(distanceKm) {
            let fee = 10000; // Default untuk < 5km

            if (distanceKm > 10) {
                fee = 20000;
            } else if (distanceKm > 5) {
                fee = 15000;
            }

            // Update state and UI
            checkoutState.shipping = fee;
            checkoutState.updateTotal();

            // Update shipping estimate text
            const shippingEstimateText = document.getElementById('shippingEstimateText');
            if (shippingEstimateText) {
                let zoneLabel = '';
                if (distanceKm > 10) zoneLabel = '&gt;10km';
                else if (distanceKm > 5) zoneLabel = '5-10km';
                else zoneLabel = '&lt;5km';

                shippingEstimateText.innerHTML =
                    `<b>Kurir Toko</b> | Jarak: ${distanceKm.toFixed(2)} km<br>Zona ${zoneLabel}, Ongkir: <b>Rp ${checkoutState.formatNumber(fee)}</b>`;
            }
        }

        /**
         * Initialize shipping calculation
         */
        function initCheckoutShippingCalc() {
            const shippingEstimateText = document.getElementById('shippingEstimateText');
            const radios = document.querySelectorAll('.shipping-method-radio');
            const distanceKm = parseFloat(document.getElementById('distance_km')?.value || 0);

            function calculateShipping() {
                try {
                    let selectedShipping = document.querySelector('.shipping-method-radio:checked')?.value || 'KURIR_TOKO';

                    // Update visual selection
                    document.querySelectorAll('.shipping-method').forEach(method => {
                        method.classList.remove('selected');
                    });
                    const selectedMethod = document.querySelector(`.shipping-method input[value="${selectedShipping}"]`);
                    if (selectedMethod) {
                        selectedMethod.closest('.shipping-method').classList.add('selected');
                    }

                    if (!shippingEstimateText) return;

                    if (selectedShipping === 'KURIR_TOKO') {
                        // Gunakan jarak yang sudah dihitung sebelumnya
                        updateShippingFeeByDistance(distanceKm);
                    } else if (selectedShipping === 'AMBIL_SENDIRI') {
                        checkoutState.shipping = 0;
                        checkoutState.updateTotal();
                        shippingEstimateText.innerHTML =
                            `<b>Ambil Sendiri di Toko</b> | <a href="https://www.google.com/maps/place/Toko+Bunga+Hendrik/@-6.4122794,106.829692" target="_blank" class="underline" style="color: var(--primary);">Lihat Lokasi Toko</a><br>Bebas Ongkir!`;
                    } else if (selectedShipping === 'GOSEND') {
                        checkoutState.shipping = 25000;
                        checkoutState.updateTotal();
                        shippingEstimateText.innerHTML = `<b>GoSend Sameday</b> | Estimasi aplikasi Rp15.000-30.000`;
                    } else if (selectedShipping === 'JNE') {
                        // Estimasi berdasarkan berat
                        const weight = {{ $total_weight }};
                        const baseFee = 12000;
                        const weightFee = Math.ceil(weight) * 5000;
                        const jneFee = baseFee + weightFee;

                        checkoutState.shipping = jneFee;
                        checkoutState.updateTotal();
                        shippingEstimateText.innerHTML =
                            `<b>JNE REG</b> | Berat: ${weight.toFixed(1)} kg<br>Estimasi: Rp ${checkoutState.formatNumber(jneFee)}`;
                    } else if (selectedShipping === 'JNT') {
                        // Estimasi berdasarkan berat
                        const weight = {{ $total_weight }};
                        const baseFee = 14000;
                        const weightFee = Math.ceil(weight) * 6000;
                        const jntFee = baseFee + weightFee;

                        checkoutState.shipping = jntFee;
                        checkoutState.updateTotal();
                        shippingEstimateText.innerHTML =
                            `<b>J&T EZ</b> | Berat: ${weight.toFixed(1)} kg<br>Estimasi: Rp ${checkoutState.formatNumber(jntFee)}`;
                    } else if (selectedShipping === 'SICEPAT') {
                        // Estimasi berdasarkan berat
                        const weight = {{ $total_weight }};
                        const baseFee = 11000;
                        const weightFee = Math.ceil(weight) * 5500;
                        const sicepatFee = baseFee + weightFee;

                        checkoutState.shipping = sicepatFee;
                        checkoutState.updateTotal();
                        shippingEstimateText.innerHTML =
                            `<b>SiCepat BEST</b> | Berat: ${weight.toFixed(1)} kg<br>Estimasi: Rp ${checkoutState.formatNumber(sicepatFee)}`;
                    }
                } catch (error) {
                    console.error('Error in shipping calculation:', error);
                    if (shippingEstimateText) {
                        shippingEstimateText.innerHTML =
                            'Terjadi kesalahan saat menghitung ongkir. Silakan refresh halaman.';
                    }
                }
            }

            // Add event listeners to shipping method radios
            radios.forEach(function(radio) {
                if (radio) {
                    radio.addEventListener('change', calculateShipping);
                }
            });

            // Initialize shipping calculation
            try {
                calculateShipping();
            } catch (error) {
                console.error('Error initializing shipping estimate:', error);
            }
        }

        /**
         * Initialize payment method selection
         */
        function initPaymentMethods() {
            document.querySelectorAll('.payment-method-radio').forEach(function(radio) {
                if (radio) {
                    radio.addEventListener('change', function() {
                        // Update visual selection
                        document.querySelectorAll('.payment-method').forEach(method => {
                            method.classList.remove('selected');
                        });
                        const parent = this.closest('.payment-method');
                        if (parent) {
                            parent.classList.add('selected');
                        }
                    });
                }
            });
        }

        /**
         * Initialize form validation
         */
        function initFormValidation() {
            const form = document.getElementById('checkout-form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const loadingOverlay = document.querySelector('.loading-overlay');

                // Show loading
                if (loadingOverlay) {
                    loadingOverlay.classList.add('active');
                }

                // Validate shipping method
                const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
                if (!shippingMethod) {
                    e.preventDefault();
                    if (loadingOverlay) loadingOverlay.classList.remove('active');
                    alert('Silakan pilih metode pengiriman');
                    return;
                }

                // Validate payment method
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    e.preventDefault();
                    if (loadingOverlay) loadingOverlay.classList.remove('active');
                    alert('Silakan pilih metode pembayaran');
                    return;
                }

                // Make sure shipping fee is set
                const shippingFee = document.getElementById('shipping_fee');
                if (shippingFee && (shippingFee.value === '' || isNaN(parseFloat(shippingFee.value)))) {
                    shippingFee.value = checkoutState.shipping;
                }

                // Basic validation passed, allow form submission
                console.log('Form submitted with:', {
                    shipping: shippingMethod.value,
                    payment: paymentMethod.value,
                    shipping_fee: shippingFee ? shippingFee.value : 0,
                    tax: checkoutState.tax,
                    total: checkoutState.total,
                    created_by: 'DenuJanuari',
                    timestamp: '2025-07-31 09:33:32'
                });
            });
        }

        /**
         * Initialize checkout page
         */
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Initialize Feather Icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }

                // Initialize checkout functions
                initPaymentMethods();
                initFormValidation();

                // Load Google Maps for shipping calculations if address is available
                if ({{ $hasAddress ? 'true' : 'false' }}) {
                    if (!window.google) {
                        const loadGoogleMaps = () => {
                            const googleMapsScript = document.createElement('script');
                            googleMapsScript.src =
                                "https://maps.googleapis.com/maps/api/js?key=AIzaSyCTUfem9YaXy7FPguX6wa26V4lRuYOgF4w&libraries=places&callback=initMap";
                            googleMapsScript.async = true;
                            googleMapsScript.defer = true;
                            document.head.appendChild(googleMapsScript);
                        };

                        setTimeout(loadGoogleMaps, 100);
                    } else {
                        initMap();
                    }

                    // Initialize shipping calculation after map is loaded
                    setTimeout(initCheckoutShippingCalc, 500);
                } else {
                    // If no address, still initialize shipping methods
                    initCheckoutShippingCalc();
                }

                // Add hover effects to payment and shipping methods
                document.querySelectorAll('.payment-method, .shipping-method').forEach(method => {
                    method.addEventListener('click', function(e) {
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio && !radio.checked) {
                            radio.checked = true;
                            const event = new Event('change', {
                                bubbles: true
                            });
                            radio.dispatchEvent(event);
                        }
                    });
                });

            } catch (error) {
                console.error('Error in checkout initialization:', error);
            }
        });

        // Handle page unload during checkout process
        window.addEventListener('beforeunload', function(e) {
            const loadingOverlay = document.querySelector('.loading-overlay');
            if (loadingOverlay && loadingOverlay.classList.contains('active')) {
                const message = 'Proses checkout sedang berlangsung. Yakin ingin meninggalkan halaman?';
                e.returnValue = message;
                return message;
            }
        });
    </script>
@endsection
