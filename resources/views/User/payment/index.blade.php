@extends('layouts.app')

@section('title', 'Pembayaran - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @php
        $user = auth()->user();
        $cartItems = \App\Models\Cart::with(['product', 'product.images'])
            ->where('user_id', $user->id)
            ->get();

        // Get promo info from session
        $promo_code = session('promo_code') ?? '';
        $promo_type = session('promo_type') ?? '';
        $promo_discount = session('promo_discount') ?? 0;

        // Current date/time and user
        $currentDateTime = '2025-07-31 11:21:29';
        $currentUser = 'DenuJanuari';

        // Calculate the raw subtotal
        $raw_subtotal = 0;
        $total_weight = 0;

        foreach ($cartItems as $item) {
            $unit_price = $item->product->price ?? 0;
            $qty = $item->quantity ?? 0;
            $raw_subtotal += $unit_price * $qty;
            $total_weight += ($item->product->weight ?? 0) * $qty;
        }

        // Calculate discount - consistent with checkout.blade.php
        $total_discount = $promo_type === 'percent' ? $raw_subtotal * ($promo_discount / 100) : $promo_discount;

        // Calculate subtotal after discount
        $subtotal_after_discount = max(0, $raw_subtotal - $total_discount);

        // Pajak 10% - calculate on post-discount subtotal
        $tax_rate = 0.1;
        $tax_total = round($subtotal_after_discount * $tax_rate);

        // Get shipping cost from checkout data
        $shipping_cost = $checkoutData['shipping_fee'] ?? 15000; // Default to 15000 if not set

        // Format shipping method name
        $shippingMethodMap = [
            'KURIR_TOKO' => 'Kurir Toko',
            'GOSEND' => 'GoSend Sameday',
            'JNE' => 'JNE REG',
            'JNT' => 'J&T EZ',
            'SICEPAT' => 'SiCepat BEST',
            'AMBIL_SENDIRI' => 'Ambil Sendiri di Toko (Gratis)',
        ];
        $shippingMethodName =
            $shippingMethodMap[$checkoutData['shipping_method'] ?? 'KURIR_TOKO'] ??
            ($checkoutData['shipping_method'] ?? 'Kurir Toko');

        // Format payment method name
        $paymentMethodMap = [
            'CASH' => 'Uang Tunai',
            'QRIS' => 'QRIS',
            'COD_QRIS' => 'COD dengan QRIS/E-Wallet',
            'EWALLET' => 'E-Wallet',
            'TRANSFER' => 'Transfer Bank',
            'VA' => 'Virtual Account',
        ];
        $paymentMethodName =
            $paymentMethodMap[$checkoutData['payment_method'] ?? ''] ??
            ($checkoutData['payment_method'] ?? 'Tidak dipilih');

        // Total akhir termasuk pajak & shipping
        $final_total = $subtotal_after_discount + $tax_total + $shipping_cost;
        $grand_total = $raw_subtotal; // Original total before discount

        // Get shipping address
        $shipping_address = $checkoutData['shipping_address'] ?? null;
        $distance_km = $checkoutData['distance_km'] ?? 0;

        // Get note
        $order_note = $checkoutData['note'] ?? '';
    @endphp
    <style>
        :root {
            --primary: #166534;
            --primary-light: #16a34a;
            --primary-dark: #14532d;
            --primary-bg: #f0fdf4;
            --primary-bg-hover: #dcfce7;
            --success: #16a34a;
            --success-bg: #dcfce7;
            --white: #ffffff;
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
        }

        .payment-container {
            max-width: 800px;
            margin: 2rem auto 3rem;
            padding: 0 1rem;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .payment-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .payment-header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        .payment-header p {
            color: var(--gray-600);
            font-size: 1.1rem;
            margin-top: 1rem;
        }

        .payment-panel {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-100);
        }

        .payment-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .payment-panel-header {
            background: var(--primary-bg);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .payment-panel-header h2 {
            color: var(--primary-dark);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-panel-body {
            padding: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-100);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.summary-total {
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1.25rem;
            border-top: 2px solid var(--primary);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }

        .summary-discount {
            color: var(--success) !important;
            font-weight: 500;
        }

        .tax-highlight {
            color: var(--primary) !important;
            font-weight: 500;
        }

        .payment-info {
            background: var(--success-bg);
            border: 1px solid #a7f3d0;
            padding: 1rem;
            border-radius: 0.75rem;
            margin: 1rem 0;
            transition: var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .payment-info::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 40px;
            background-color: rgba(167, 243, 208, 0.5);
            border-radius: 0 0 0 40px;
        }

        .payment-info:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .payment-info h3 {
            color: var(--success);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .address-info {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            padding: 1rem;
            border-radius: 0.75rem;
            margin: 1rem 0;
        }

        .address-info h3 {
            color: var(--gray-700);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-button {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-right: 1rem;
        }

        .back-button:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
            transform: translateY(-1px);
            color: var(--gray-700);
            text-decoration: none;
        }

        .pay-button {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .pay-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: pay-btn-shine 2s infinite;
        }

        @keyframes pay-btn-shine {
            100% {
                left: 100%;
            }
        }

        .pay-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .pay-button:active {
            transform: translateY(0);
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.15s ease;
        }

        .order-item:hover {
            background-color: var(--gray-50);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item-image {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            margin-right: 1rem;
            transition: all 0.15s ease;
        }

        .order-item:hover .order-item-image {
            transform: scale(1.05);
        }

        .order-item-details {
            flex: 1;
        }

        .order-item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .order-item-price {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .order-item-total {
            font-weight: 600;
            color: var(--primary);
            white-space: nowrap;
        }

        .subtotal-after-discount {
            padding: 0.75rem 0;
            color: var(--gray-600);
            background-color: var(--primary-bg);
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin: 0.25rem 0;
            font-size: 0.9rem;
            color: var(--primary);
        }

        .shipping-info {
            margin-top: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: var(--gray-50);
            border-radius: 0.25rem;
            font-size: 0.9rem;
        }

        .note-info {
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: var(--gray-50);
            border-radius: 0.5rem;
            border-left: 3px solid var(--primary-light);
        }

        .note-info h4 {
            color: var(--gray-800);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .note-info p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin: 0;
        }

        .user-info {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--gray-200);
            display: flex;
            justify-content: space-between;
        }

        .promo-badge {
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: var(--success-bg);
            border-radius: 0.5rem;
            color: var(--success);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Animation classes */
        .updating {
            animation: highlight 0.8s ease;
        }

        @keyframes highlight {

            0%,
            100% {
                color: inherit;
            }

            50% {
                color: var(--primary);
            }
        }

        @media (max-width: 768px) {
            .payment-container {
                margin-top: 1rem;
                padding: 0 0.5rem;
            }

            .payment-header h1 {
                font-size: 1.75rem;
            }

            .payment-panel-body {
                padding: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }

            .back-button,
            .pay-button {
                width: 100%;
                justify-content: center;
                margin-right: 0;
            }
        }
    </style>

    <div class="payment-container">
        <div class="payment-header">
            <h1>Pembayaran</h1>
            <p>Konfirmasi pembayaran untuk pesanan Anda</p>
        </div>

        <!-- Order Summary -->
        <div class="payment-panel">
            <div class="payment-panel-header">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Ringkasan Pesanan ({{ $cartItems->count() }} item)
                </h2>
            </div>
            <div class="payment-panel-body">
                @foreach ($cartItems as $item)
                    @php
                        $unit_price = $item->product->price ?? 0;
                        $qty = $item->quantity ?? 0;
                        $item_total = $unit_price * $qty;
                    @endphp
                    <div class="order-item">
                        <img src="{{ asset($item->product->image_url ?? ($item->product->images->first()->url ?? 'images/no-image.png')) }}"
                            alt="{{ $item->product->name }}" class="order-item-image">
                        <div class="order-item-details">
                            <div class="order-item-name">{{ $item->product->name }}</div>
                            <div class="order-item-price">Qty: {{ $item->quantity }} × Rp
                                {{ number_format($unit_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="order-item-total">Rp {{ number_format($item_total, 0, ',', '.') }}</div>
                    </div>
                @endforeach

                @if ($promo_code)
                    <div class="promo-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        Promo <strong>{{ $promo_code }}</strong> telah diterapkan
                        @if ($promo_type === 'percent')
                            (Diskon {{ $promo_discount }}%)
                        @else
                            (Potongan Rp{{ number_format($promo_discount, 0, ',', '.') }})
                        @endif
                    </div>
                @endif

                @if (!empty($order_note))
                    <div class="note-info">
                        <h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                style="display: inline-block; vertical-align: -2px; margin-right: 5px;">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Catatan Pesanan:
                        </h4>
                        <p>{{ $order_note }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if ($shipping_address)
            <!-- Shipping Address -->
            <div class="payment-panel">
                <div class="payment-panel-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Alamat Pengiriman
                    </h2>
                </div>
                <div class="payment-panel-body">
                    <div class="address-info">
                        <div style="font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                            {{ $shipping_address->label ?? 'Alamat Pengiriman' }}</div>
                        <div style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.25rem;">
                            {{ $shipping_address->recipient ?? 'Nama Penerima' }}</div>
                        <div style="color: var(--gray-700); margin-bottom: 0.25rem;">
                            {{ $shipping_address->full_address ?? 'Alamat Lengkap' }}</div>
                        <div style="color: var(--gray-700); margin-bottom: 0.25rem;">
                            {{ $shipping_address->city ?? 'Kota' }}, {{ $shipping_address->zip_code ?? 'Kode Pos' }}</div>
                        <div style="color: var(--gray-700); margin-bottom: 0.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                style="display: inline-block; vertical-align: -2px; margin-right: 5px;">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            {{ $shipping_address->phone_number ?? 'Nomor Telepon' }}
                        </div>

                        @if ($distance_km > 0)
                            <div
                                style="font-size: 0.9rem; color: var(--gray-600); margin-top: 0.5rem; background: var(--primary-bg); padding: 0.5rem; border-radius: 0.25rem;">
                                <strong>Jarak ke toko:</strong> {{ number_format($distance_km, 1) }} km
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Details -->
        <div class="payment-panel">
            <div class="payment-panel-header">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Detail Pembayaran
                </h2>
            </div>
            <div class="payment-panel-body">
                <div class="summary-row">
                    <span>Subtotal ({{ $cartItems->count() }} item)</span>
                    <span>Rp {{ number_format($raw_subtotal, 0, ',', '.') }}</span>
                </div>

                @if ($total_discount > 0)
                    <div class="summary-row">
                        <span class="summary-discount">Diskon</span>
                        <span class="summary-discount">-Rp {{ number_format($total_discount, 0, ',', '.') }}</span>
                    </div>

                    <div class="subtotal-after-discount">
                        Subtotal setelah diskon: Rp{{ number_format($subtotal_after_discount, 0, ',', '.') }}
                    </div>
                @endif

                <div class="summary-row">
                    <span class="tax-highlight">Pajak (10%)</span>
                    <span class="tax-highlight">Rp {{ number_format($tax_total, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span>Ongkir ({{ $shippingMethodName }})</span>
                    <span>Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span>Berat Total</span>
                    <span>{{ number_format($total_weight, 1) }} kg</span>
                </div>

                <div class="summary-row summary-total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($final_total, 0, ',', '.') }}</span>
                </div>

                <!-- Payment Method Info -->
                <div class="payment-info">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        Metode Pembayaran
                    </h3>
                    <p><strong>{{ $paymentMethodName }}</strong></p>
                    <p>Silakan lanjutkan pembayaran dengan metode yang telah Anda pilih.</p>

                    <div class="shipping-info">
                        <strong>Metode Pengiriman:</strong> {{ $shippingMethodName }}<br>
                        @if ($checkoutData['shipping_method'] === 'KURIR_TOKO')
                            <div style="margin-top: 0.25rem; font-size: 0.85rem; color: var(--gray-600);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    style="display: inline-block; vertical-align: -2px; margin-right: 3px;">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                Pengiriman akan dilakukan oleh kurir dari toko Azka Garden
                            </div>
                        @elseif($checkoutData['shipping_method'] === 'AMBIL_SENDIRI')
                            <div style="margin-top: 0.25rem; font-size: 0.85rem; color: var(--success);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    style="display: inline-block; vertical-align: -2px; margin-right: 3px;">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                Silakan ambil pesanan Anda langsung di toko Azka Garden
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <form action="{{ route('user.payment.process') }}" method="POST" style="margin-top: 1.5rem;">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $checkoutData['payment_method'] ?? '' }}">
                    <input type="hidden" name="shipping_method" value="{{ $checkoutData['shipping_method'] ?? '' }}">
                    <input type="hidden" name="shipping_fee" value="{{ $shipping_cost }}">
                    <input type="hidden" name="tax_amount" value="{{ $tax_total }}">
                    <input type="hidden" name="discount_amount" value="{{ $total_discount }}">
                    <input type="hidden" name="final_amount" value="{{ $final_total }}">

                    <div class="action-buttons" style="display: flex; gap: 1rem; align-items: center;">
                        <a href="{{ route('checkout.index') }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Kembali ke Checkout
                        </a>

                        <button type="submit" class="pay-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>

                <div class="user-info">
                    <span>Login: {{ $currentUser }}</span>
                    <span>{{ date('d/m/Y H:i', strtotime($currentDateTime)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animated entrance effect
            const animateElements = () => {
                const elements = ['.payment-panel'];

                elements.forEach((selector, index) => {
                    document.querySelectorAll(selector).forEach((el, i) => {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(20px)';
                        el.style.transition = 'all 0.4s ease';

                        setTimeout(() => {
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }, 100 * (index + i));
                    });
                });
            };

            // Run animation after a short delay
            setTimeout(animateElements, 100);

            // Add highlight animation to total
            const totalElement = document.querySelector('.summary-row.summary-total span:last-child');
            if (totalElement) {
                totalElement.classList.add('updating');
                setTimeout(() => totalElement.classList.remove('updating'), 1000);
            }
        });
    </script>
@endsection
