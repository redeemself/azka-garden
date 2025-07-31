@extends('layouts.app')

@section('title', 'Checkout - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        /**
             * Azka Garden Checkout Page Styles
             * Created: 2025-07-31 15:29:34
             * Updated by: DenuJanuari
             * Enhanced checkout system with payment integration
             */

        :root {
            /* Main colors */
            --primary: #166534;
            --primary-light: #16a34a;
            --primary-dark: #14532d;
            --primary-bg: #f0fdf4;
            --primary-bg-hover: #dcfce7;
            --accent: #4ade80;

            /* Neutral colors */
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

            /* Status colors */
            --error: #b91c1c;
            --error-bg: #fee2e2;
            --warning: #ea580c;
            --warning-bg: #ffedd5;
            --success: #16a34a;
            --success-bg: #dcfce7;
            --info: #0284c7;
            --info-bg: #e0f2fe;

            /* Shadow styles */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

            /* Typography */
            --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;

            /* Transitions */
            --transition-all: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Typography & Base Styles */
        body {
            font-family: var(--font-sans);
            color: var(--gray-800);
            background-color: #f8f9fa;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700;
            line-height: 1.25;
            color: var(--gray-900);
        }

        .page-heading {
            position: relative;
            margin-bottom: 2rem;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -0.025em;
            color: var(--gray-900);
            text-align: center;
        }

        .page-heading::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        /* Container & Layout */
        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: flex-start;
        }

        /* Progress Bar */
        .progress-bar {
            background-color: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-100);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--gray-200);
            z-index: 1;
        }

        .progress-steps::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            background-color: var(--primary);
            z-index: 2;
            width: 66.67%;
            /* Progress to step 2 (checkout) */
            transition: var(--transition-all);
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
            background-color: #f8f9fa;
            padding: 0.5rem;
        }

        .progress-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 0.5rem;
            transition: var(--transition-all);
        }

        .progress-step.completed .progress-step-circle {
            background-color: var(--primary);
        }

        .progress-step.active .progress-step-circle {
            background-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(22, 101, 52, 0.2);
        }

        .progress-step.inactive .progress-step-circle {
            background-color: var(--gray-300);
            color: var(--gray-600);
        }

        .progress-step-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            text-align: center;
        }

        .progress-step.active .progress-step-label {
            color: var(--primary);
            font-weight: 600;
        }

        /* Card Styles */
        .checkout-card {
            background-color: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-100);
            transition: var(--transition-all);
        }

        .checkout-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .checkout-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            background-color: var(--gray-50);
        }

        .checkout-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkout-card-title svg {
            color: var(--primary);
        }

        .checkout-card-body {
            padding: 1.5rem;
        }

        /* Delivery Information */
        .delivery-info {
            background-color: var(--info-bg);
            border: 1px solid var(--info);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .delivery-address {
            background-color: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .address-label {
            display: inline-block;
            background-color: var(--primary-bg);
            color: var(--primary);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .address-recipient {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .address-details {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 0.25rem;
        }

        .address-phone {
            color: var(--gray-700);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Order Items */
        .order-items {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition-fast);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background-color: var(--gray-50);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .item-price {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .item-quantity {
            font-weight: 500;
            color: var(--gray-700);
            margin: 0 1rem;
            min-width: 60px;
            text-align: center;
        }

        .item-subtotal {
            font-weight: 600;
            color: var(--primary);
            min-width: 100px;
            text-align: right;
        }

        /* Shipping Information */
        .shipping-info {
            background-color: var(--success-bg);
            border: 1px solid var(--success);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .shipping-method {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .shipping-name {
            font-weight: 600;
            color: var(--success);
        }

        .shipping-cost {
            font-weight: 600;
            color: var(--success);
        }

        .shipping-description {
            color: var(--gray-700);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .shipping-distance {
            color: var(--gray-600);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Payment Methods */
        .payment-methods {
            display: grid;
            gap: 1rem;
        }

        .payment-method {
            border: 2px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: var(--transition-all);
            position: relative;
            background-color: var(--white);
        }

        .payment-method:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
            transform: translateY(-1px);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background-color: var(--primary-bg);
            box-shadow: var(--shadow-md);
        }

        .payment-method-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .payment-method input[type="radio"] {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary);
        }

        .payment-method-name {
            font-weight: 600;
            color: var(--gray-800);
            flex: 1;
        }

        .payment-method-fee {
            font-weight: 600;
            color: var(--primary);
        }

        .payment-method-description {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-left: 2rem;
        }

        /* Order Summary */
        .summary-card {
            position: sticky;
            top: 2rem;
            background-color: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .summary-header {
            background-color: var(--gray-800);
            color: var(--white);
            padding: 1.25rem 1.5rem;
        }

        .summary-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-body {
            padding: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: var(--gray-700);
        }

        .summary-row.discount {
            color: var(--success);
            font-weight: 500;
        }

        .summary-row.shipping {
            color: var(--info);
            font-weight: 500;
        }

        .summary-row.tax {
            color: var(--primary);
            font-weight: 500;
        }

        .summary-row.payment-fee {
            color: var(--warning);
            font-weight: 500;
        }

        .summary-divider {
            height: 1px;
            background-color: var(--gray-200);
            margin: 1rem 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .back-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            background-color: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition-fast);
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: var(--gray-100);
            color: var(--gray-800);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .proceed-btn {
            flex: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition-fast);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .proceed-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: btn-shine 2s infinite;
        }

        @keyframes btn-shine {
            100% {
                left: 100%;
            }
        }

        .proceed-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .proceed-btn:disabled {
            background-color: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            border: 2px solid rgba(0, 0, 0, 0.1);
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border-left-color: currentColor;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: text-bottom;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Notes Section */
        .order-notes {
            margin-top: 1rem;
        }

        .order-notes label {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            display: block;
        }

        .order-notes textarea {
            width: 100%;
            min-height: 80px;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-size: 0.95rem;
            line-height: 1.4;
            resize: vertical;
            transition: var(--transition-fast);
        }

        .order-notes textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        /* User Info */
        .user-info {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--gray-200);
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .checkout-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .summary-card {
                position: static;
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .checkout-container {
                padding: 0 0.75rem;
                margin: 1rem auto;
            }

            .page-heading {
                font-size: 1.5rem;
            }

            .checkout-card-header,
            .checkout-card-body {
                padding: 1rem;
            }

            .progress-steps {
                flex-direction: column;
                gap: 1rem;
            }

            .progress-steps::before,
            .progress-steps::after {
                display: none;
            }

            .progress-step {
                flex-direction: row;
                width: 100%;
                justify-content: flex-start;
                background-color: transparent;
                padding: 0;
            }

            .progress-step-circle {
                margin-bottom: 0;
                margin-right: 1rem;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .item-quantity,
            .item-subtotal {
                margin: 0;
                text-align: left;
                min-width: auto;
            }

            .payment-method-description {
                margin-left: 0;
                margin-top: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    @php
        // Checkout data initialization
        $user = auth()->user();
        $currentDateTime = '2025-07-31 15:29:34';
        $currentUser = 'DenuJanuari';

        // Calculate order totals (received from cart checkout)
        $cartItems = session('checkout_cart_items', collect());
        $subtotal = session('checkout_subtotal', 0);
        $discount = session('checkout_discount', 0);
        $shippingFee = session('checkout_shipping_fee', 0);
        $shippingMethod = session('checkout_shipping_method', 'KURIR_TOKO_SEDANG');
        $shippingDistance = session('checkout_distance_km', 7.2);
        $tax = session('checkout_tax', $subtotal * 0.1);

        // Customer address
        $deliveryAddress = $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first();

        // Payment methods available
        $paymentMethods = [
            [
                'id' => 'bank_transfer',
                'name' => 'Transfer Bank',
                'description' => 'Transfer melalui BCA, BNI, BRI, atau Mandiri',
                'fee' => 0,
                'icon' => 'credit-card',
            ],
            [
                'id' => 'ewallet',
                'name' => 'E-Wallet',
                'description' => 'Bayar melalui GoPay, OVO, DANA, atau ShopeePay',
                'fee' => 2500,
                'icon' => 'smartphone',
            ],
            [
                'id' => 'cash_on_delivery',
                'name' => 'Bayar di Tempat (COD)',
                'description' => 'Bayar tunai saat barang diterima',
                'fee' => 5000,
                'icon' => 'dollar-sign',
            ],
            [
                'id' => 'credit_card',
                'name' => 'Kartu Kredit',
                'description' => 'Visa, MasterCard, atau American Express',
                'fee' => 3000,
                'icon' => 'credit-card',
            ],
        ];

        // Default selected payment method
        $selectedPaymentMethod = $paymentMethods[0];
        $paymentFee = $selectedPaymentMethod['fee'];

        // Calculate grand total
        $grandTotal = $subtotal - $discount + $shippingFee + $tax + $paymentFee;

        // Shipping method details
        $shippingMethodDetails = [
            'KURIR_TOKO_DEKAT' => ['name' => 'Kurir Toko (< 5km)', 'estimated' => '1-2 jam'],
            'KURIR_TOKO_SEDANG' => ['name' => 'Kurir Toko (5-10km)', 'estimated' => '2-3 jam'],
            'KURIR_TOKO_JAUH' => ['name' => 'Kurir Toko (> 10km)', 'estimated' => '3-4 jam'],
            'JNE' => ['name' => 'JNE Regular', 'estimated' => '2-3 hari'],
            'JNT' => ['name' => 'J&T Express', 'estimated' => '1-2 hari'],
            'AMBIL_SENDIRI' => ['name' => 'Ambil Sendiri di Toko', 'estimated' => 'Langsung'],
        ];

        $currentShippingMethod = $shippingMethodDetails[$shippingMethod] ?? $shippingMethodDetails['KURIR_TOKO_SEDANG'];
    @endphp

    <div class="checkout-container">
        <h1 class="page-heading">Checkout Pesanan</h1>

        {{-- Progress Bar --}}
        <div class="progress-bar">
            <div class="progress-steps">
                <div class="progress-step completed">
                    <div class="progress-step-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="progress-step-label">Keranjang</div>
                </div>
                <div class="progress-step active">
                    <div class="progress-step-circle">2</div>
                    <div class="progress-step-label">Checkout</div>
                </div>
                <div class="progress-step inactive">
                    <div class="progress-step-circle">3</div>
                    <div class="progress-step-label">Pembayaran</div>
                </div>
                <div class="progress-step inactive">
                    <div class="progress-step-circle">4</div>
                    <div class="progress-step-label">Selesai</div>
                </div>
            </div>
        </div>

        <div class="checkout-grid">
            <div class="checkout-main">
                {{-- Delivery Information --}}
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Informasi Pengiriman
                        </h2>
                        <a href="{{ route('user.address.index') }}"
                            style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">
                            Ubah Alamat
                        </a>
                    </div>
                    <div class="checkout-card-body">
                        @if ($deliveryAddress)
                            <div class="delivery-address">
                                <div class="address-label">{{ $deliveryAddress->label }}</div>
                                <div class="address-recipient">{{ $deliveryAddress->recipient }}</div>
                                <div class="address-details">{{ $deliveryAddress->full_address }}</div>
                                <div class="address-details">{{ $deliveryAddress->city }}, {{ $deliveryAddress->zip_code }}
                                </div>
                                <div class="address-phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                    {{ $deliveryAddress->phone_number }}
                                </div>
                            </div>

                            <div class="shipping-info">
                                <div class="shipping-method">
                                    <div class="shipping-name">{{ $currentShippingMethod['name'] }}</div>
                                    <div class="shipping-cost">
                                        {{ $shippingFee > 0 ? 'Rp ' . number_format($shippingFee, 0, ',', '.') : 'Gratis' }}
                                    </div>
                                </div>
                                <div class="shipping-description">
                                    Estimasi pengiriman: {{ $currentShippingMethod['estimated'] }}
                                </div>
                                <div class="shipping-distance">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    Jarak dari toko: {{ number_format($shippingDistance, 1) }} km
                                </div>
                            </div>
                        @else
                            <div class="delivery-info">
                                <p style="margin: 0; color: var(--error);">
                                    <strong>Alamat pengiriman belum tersedia.</strong>
                                    <a href="{{ route('user.address.create') }}" style="color: var(--primary);">Tambah
                                        alamat pengiriman</a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Item Pesanan ({{ $cartItems->count() }} produk)
                        </h2>
                        <a href="{{ route('cart.index') }}"
                            style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">
                            Edit Keranjang
                        </a>
                    </div>
                    <div class="checkout-card-body">
                        <div class="order-items">
                            @forelse($cartItems as $item)
                                <div class="order-item">
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->product->name ?? $item->name }}</div>
                                        <div class="item-price">Rp{{ number_format($item->price, 0, ',', '.') }} per item
                                        </div>
                                        @if (isset($item->product->weight) && $item->product->weight > 0)
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                Berat: {{ $item->product->weight }}kg
                                            </div>
                                        @endif
                                    </div>
                                    <div class="item-quantity">{{ $item->quantity }}x</div>
                                    <div class="item-subtotal">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="order-item">
                                    <div class="item-details">
                                        <div class="item-name" style="color: var(--error);">Tidak ada item dalam keranjang
                                        </div>
                                        <div class="item-price">
                                            <a href="{{ route('cart.index') }}" style="color: var(--primary);">Kembali ke
                                                keranjang</a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            Metode Pembayaran
                        </h2>
                    </div>
                    <div class="checkout-card-body">
                        <div class="payment-methods">
                            @foreach ($paymentMethods as $index => $method)
                                <div class="payment-method {{ $index === 0 ? 'selected' : '' }}"
                                    data-method="{{ $method['id'] }}" data-fee="{{ $method['fee'] }}">
                                    <div class="payment-method-header">
                                        <input type="radio" name="payment_method" id="payment_{{ $method['id'] }}"
                                            value="{{ $method['id'] }}" {{ $index === 0 ? 'checked' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            @if ($method['icon'] === 'credit-card')
                                                <rect x="1" y="4" width="22" height="16" rx="2"
                                                    ry="2"></rect>
                                                <line x1="1" y1="10" x2="23" y2="10">
                                                </line>
                                            @elseif($method['icon'] === 'smartphone')
                                                <rect x="5" y="2" width="14" height="20" rx="2"
                                                    ry="2"></rect>
                                                <line x1="12" y1="18" x2="12.01" y2="18">
                                                </line>
                                            @elseif($method['icon'] === 'dollar-sign')
                                                <line x1="12" y1="1" x2="12" y2="23">
                                                </line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            @endif
                                        </svg>
                                        <label for="payment_{{ $method['id'] }}"
                                            class="payment-method-name">{{ $method['name'] }}</label>
                                        <div class="payment-method-fee">
                                            {{ $method['fee'] > 0 ? '+Rp ' . number_format($method['fee'], 0, ',', '.') : 'Gratis' }}
                                        </div>
                                    </div>
                                    <div class="payment-method-description">{{ $method['description'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Order Notes --}}
                        <div class="order-notes">
                            <label for="order_notes">Catatan Pesanan (Opsional)</label>
                            <textarea id="order_notes" name="order_notes" placeholder="Tambahkan catatan khusus untuk pesanan Anda..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="checkout-sidebar">
                <div class="summary-card">
                    <div class="summary-header">
                        <h2 class="summary-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            Ringkasan Pesanan
                        </h2>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span>Subtotal ({{ $cartItems->count() }} item)</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="summary-row discount">
                                <span>Diskon</span>
                                <span>−Rp{{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="summary-row shipping">
                            <span>Ongkos Kirim</span>
                            <span id="summary-shipping-cost">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                        </div>

                        <div class="summary-row tax">
                            <span>Pajak (10%)</span>
                            <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                        </div>

                        <div class="summary-row payment-fee">
                            <span>Biaya Pembayaran</span>
                            <span id="summary-payment-fee">Rp{{ number_format($paymentFee, 0, ',', '.') }}</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <span>Total Bayar</span>
                            <span id="summary-total">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('cart.index') }}" class="back-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Kembali
                            </a>

                            @if ($deliveryAddress && $cartItems->count() > 0)
                                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                                    @csrf
                                    <input type="hidden" name="payment_method" id="selected_payment_method"
                                        value="{{ $selectedPaymentMethod['id'] }}">
                                    <input type="hidden" name="payment_fee" id="selected_payment_fee"
                                        value="{{ $paymentFee }}">
                                    <input type="hidden" name="shipping_method" value="{{ $shippingMethod }}">
                                    <input type="hidden" name="shipping_fee" value="{{ $shippingFee }}">
                                    <input type="hidden" name="distance_km" value="{{ $shippingDistance }}">
                                    <input type="hidden" name="delivery_address_id" value="{{ $deliveryAddress->id }}">
                                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                                    <input type="hidden" name="discount" value="{{ $discount }}">
                                    <input type="hidden" name="tax" value="{{ $tax }}">
                                    <input type="hidden" name="total" id="final_total" value="{{ $grandTotal }}">

                                    <button type="submit" class="proceed-btn" id="proceed-payment-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="1" y="4" width="22" height="16" rx="2"
                                                ry="2"></rect>
                                            <line x1="1" y1="10" x2="23" y2="10"></line>
                                        </svg>
                                        Lanjut ke Pembayaran
                                    </button>
                                </form>
                            @else
                                <button class="proceed-btn" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    Lengkapi Data Dulu
                                </button>
                            @endif
                        </div>

                        <div class="user-info">
                            <span>Login: {{ $currentUser }}</span>
                            <span>{{ date('d/m/Y H:i', strtotime($currentDateTime)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * Enhanced Checkout Page JavaScript
         * Created: 2025-07-31 15:29:34
         * Updated by: DenuJanuari
         * Integrated checkout system with payment handling
         */

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize checkout state
            let checkoutState = {
                subtotal: {{ $subtotal }},
                discount: {{ $discount }},
                shippingFee: {{ $shippingFee }},
                tax: {{ $tax }},
                paymentFee: {{ $paymentFee }},
                total: {{ $grandTotal }},
                selectedPaymentMethod: '{{ $selectedPaymentMethod['id'] }}',
                timestamp: '{{ $currentDateTime }}',
                user: '{{ $currentUser }}'
            };

            // DOM elements
            const paymentMethods = document.querySelectorAll('.payment-method');
            const paymentFeeElement = document.getElementById('summary-payment-fee');
            const totalElement = document.getElementById('summary-total');
            const selectedPaymentMethodInput = document.getElementById('selected_payment_method');
            const selectedPaymentFeeInput = document.getElementById('selected_payment_fee');
            const finalTotalInput = document.getElementById('final_total');
            const checkoutForm = document.getElementById('checkout-form');
            const proceedBtn = document.getElementById('proceed-payment-btn');
            const orderNotesTextarea = document.getElementById('order_notes');

            // Payment method selection handler
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    const methodId = this.dataset.method;
                    const methodFee = parseInt(this.dataset.fee) || 0;
                    const radio = this.querySelector('input[type="radio"]');

                    if (radio) {
                        // Update radio selection
                        radio.checked = true;

                        // Update visual selection
                        paymentMethods.forEach(m => m.classList.remove('selected'));
                        this.classList.add('selected');

                        // Update checkout state
                        checkoutState.selectedPaymentMethod = methodId;
                        checkoutState.paymentFee = methodFee;
                        checkoutState.total = checkoutState.subtotal - checkoutState.discount +
                            checkoutState.shippingFee + checkoutState.tax + methodFee;

                        // Update hidden form inputs
                        if (selectedPaymentMethodInput) {
                            selectedPaymentMethodInput.value = methodId;
                        }
                        if (selectedPaymentFeeInput) {
                            selectedPaymentFeeInput.value = methodFee;
                        }
                        if (finalTotalInput) {
                            finalTotalInput.value = checkoutState.total;
                        }

                        // Update summary display with animation
                        updatePaymentSummary(methodFee, checkoutState.total);

                        console.log('Payment method changed:', {
                            method_id: methodId,
                            method_fee: methodFee,
                            new_total: checkoutState.total,
                            timestamp: '{{ $currentDateTime }}',
                            user: '{{ $currentUser }}'
                        });
                    }
                });
            });

            // Update payment summary with animation
            function updatePaymentSummary(paymentFee, total) {
                if (paymentFeeElement) {
                    // Add animation
                    paymentFeeElement.style.transition = 'all 0.3s ease';
                    paymentFeeElement.style.color = 'var(--warning)';
                    paymentFeeElement.style.transform = 'scale(1.05)';

                    // Update value
                    paymentFeeElement.textContent = paymentFee > 0 ?
                        `Rp${new Intl.NumberFormat('id-ID').format(paymentFee)}` : 'Gratis';

                    // Reset animation
                    setTimeout(() => {
                        paymentFeeElement.style.transform = '';
                        paymentFeeElement.style.color = '';
                    }, 1000);
                }

                if (totalElement) {
                    // Add animation
                    totalElement.style.transition = 'all 0.3s ease';
                    totalElement.style.transform = 'scale(1.1)';
                    totalElement.style.color = 'var(--primary)';

                    // Update value
                    totalElement.textContent = `Rp${new Intl.NumberFormat('id-ID').format(total)}`;

                    // Reset animation
                    setTimeout(() => {
                        totalElement.style.transform = '';
                        totalElement.style.color = '';
                    }, 1000);
                }
            }

            // Enhanced form submission handler
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate required fields
                    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                    if (!selectedPayment) {
                        alert('Silakan pilih metode pembayaran terlebih dahulu.');
                        return;
                    }

                    // Add order notes to form data
                    if (orderNotesTextarea && orderNotesTextarea.value.trim()) {
                        const notesInput = document.createElement('input');
                        notesInput.type = 'hidden';
                        notesInput.name = 'order_notes';
                        notesInput.value = orderNotesTextarea.value.trim();
                        this.appendChild(notesInput);
                    }

                    // Show loading state
                    if (proceedBtn) {
                        proceedBtn.disabled = true;
                        proceedBtn.innerHTML = `
                            <div style="width: 20px; height: 20px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 0.5rem; display: inline-block;"></div>
                            Memproses Checkout...
                        `;
                    }

                    // Add loading overlay
                    showLoadingOverlay('Memproses checkout Anda...',
                        'Mohon tunggu, kami sedang menyiapkan pesanan Anda.');

                    console.log('Checkout form submitted:', {
                        payment_method: selectedPayment.value,
                        payment_fee: checkoutState.paymentFee,
                        total: checkoutState.total,
                        order_notes: orderNotesTextarea?.value.trim() || null,
                        timestamp: '{{ $currentDateTime }}',
                        user: '{{ $currentUser }}'
                    });

                    // Submit form after brief delay for UX
                    setTimeout(() => {
                        this.submit();
                    }, 800);
                });
            }

            // Auto-save order notes
            if (orderNotesTextarea) {
                let notesTimeout;
                orderNotesTextarea.addEventListener('input', function() {
                    clearTimeout(notesTimeout);
                    notesTimeout = setTimeout(() => {
                        console.log('Order notes updated:', {
                            notes: this.value.trim(),
                            timestamp: '{{ $currentDateTime }}',
                            user: '{{ $currentUser }}'
                        });
                    }, 1000);
                });
            }

            // Show loading overlay function
            function showLoadingOverlay(title, message) {
                const overlay = document.createElement('div');
                overlay.className = 'loading-overlay active';
                overlay.innerHTML = `
                    <div style="background-color: white; padding: 2rem; border-radius: 0.75rem; text-align: center; box-shadow: var(--shadow-xl); max-width: 400px;">
                        <div style="width: 50px; height: 50px; border: 4px solid var(--gray-200); border-top: 4px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1.5rem;"></div>
                        <h3 style="margin: 0 0 1rem; font-weight: 600; color: var(--gray-800);">${title}</h3>
                        <p style="margin: 0; color: var(--gray-600);">${message}</p>
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                            Jangan tutup halaman ini
                        </div>
                    </div>
                `;
                document.body.appendChild(overlay);
            }

            // Initialize animations
            function initializeAnimations() {
                const cards = document.querySelectorAll('.checkout-card, .summary-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.4s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100 * (index + 1));
                });
            }

            // Initialize page
            setTimeout(initializeAnimations, 200);

            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            console.log('Enhanced checkout system initialized:', {
                checkout_state: checkoutState,
                payment_methods_count: {{ count($paymentMethods) }},
                cart_items_count: {{ $cartItems->count() }},
                delivery_address: {{ $deliveryAddress ? 'true' : 'false' }},
                timestamp: '{{ $currentDateTime }}',
                user: '{{ $currentUser }}',
                version: '2.0.0'
            });
        });
    </script>

    {{-- Additional CSS for loading overlay --}}
    <style>
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-overlay.active {
            display: flex !important;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Enhanced hover effects */
        .payment-method:hover {
            transform: translateY(-2px);
        }

        .payment-method.selected {
            transform: translateY(-2px);
        }

        /* Better responsive design */
        @media (max-width: 640px) {
            .order-item {
                padding: 0.75rem;
            }

            .payment-method {
                padding: 0.75rem;
            }

            .payment-method-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .payment-method-fee {
                margin-left: 2rem;
            }
        }
    </style>
@endsection
