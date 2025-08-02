@extends('layouts.app')

@section('title', 'Checkout - Konfirmasi Pesanan')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        :root {
            --primary: #16a34a;
            --primary-light: #22c55e;
            --primary-dark: #15803d;
            --primary-bg: #dcfce7;
            --accent: #10b981;
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
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --border-radius: 16px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 50%, #dcfce7 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .checkout-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: var(--border-radius);
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(22, 163, 74, 0.1);
            position: relative;
            overflow: hidden;
        }

        .checkout-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--accent));
        }

        .checkout-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .checkout-subtitle {
            color: var(--gray-600);
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .checkout-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .progress-step.completed {
            background: var(--primary-bg);
            color: var(--primary-dark);
        }

        .progress-step.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .progress-step.inactive {
            background: var(--gray-100);
            color: var(--gray-500);
        }

        .checkout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(22, 163, 74, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .checkout-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-bg) 0%, rgba(220, 252, 231, 0.8) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(22, 163, 74, 0.1);
        }

        .card-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-badge {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: auto;
        }

        .card-body {
            padding: 2rem;
        }

        /* Product Table Styling */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .product-table thead tr {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            border-bottom: 2px solid var(--primary-light);
        }

        .product-table th {
            padding: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .product-table th:last-child {
            text-align: right;
        }

        .product-table tbody tr {
            border-bottom: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }

        .product-table tbody tr:hover {
            background: rgba(22, 163, 74, 0.02);
        }

        .product-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
        }

        .product-details h4 {
            font-weight: 600;
            color: var(--gray-800);
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
        }

        .product-details p {
            color: var(--gray-600);
            margin: 0;
            font-size: 0.875rem;
        }

        .quantity-badge {
            background: linear-gradient(135deg, var(--primary-bg), var(--primary-light));
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            min-width: 50px;
            justify-content: center;
        }

        .price-cell {
            text-align: right;
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1rem;
        }

        /* Summary Section */
        .summary-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(16, 185, 129, 0.1);
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-top: 0.5rem;
            padding-top: 1rem;
            border-top: 2px solid rgba(16, 185, 129, 0.2);
        }

        .summary-label {
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .summary-value {
            color: var(--gray-800);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .summary-total {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .summary-total .summary-label {
            color: var(--primary-dark);
            font-weight: 700;
        }

        /* Method Info Cards */
        .method-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-radius: 12px;
            border: 2px solid var(--gray-200);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .method-info:hover {
            border-color: var(--primary-light);
            background: linear-gradient(135deg, var(--primary-bg), var(--white));
        }

        .method-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .method-details h3 {
            font-weight: 600;
            color: var(--gray-800);
            margin: 0 0 0.25rem 0;
            font-size: 1.1rem;
        }

        .method-details p {
            color: var(--gray-600);
            margin: 0;
            font-size: 0.9rem;
        }

        .method-cost {
            margin-left: auto;
            text-align: right;
        }

        .method-cost .cost-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }

        .method-cost .cost-value {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }

        /* Action Buttons */
        .action-section {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.4);
        }

        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-loading {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-loading::before {
            display: none;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--gray-300);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: loadingSpin 1s ease-in-out infinite;
        }

        @keyframes loadingSpin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Quick Action Buttons */
        .quick-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .quick-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--gray-300);
            background: var(--white);
            color: var(--gray-600);
            transition: all 0.2s ease;
        }

        .quick-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-bg);
            text-decoration: none;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: var(--success);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 350px;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.error {
            background: var(--error);
        }

        .toast.warning {
            background: var(--warning);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .checkout-container {
                padding: 1rem 0.5rem;
            }

            .checkout-title {
                font-size: 2rem;
            }

            .checkout-header {
                padding: 2rem 1.5rem;
                margin-bottom: 2rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .product-table th,
            .product-table td {
                padding: 0.75rem 0.5rem;
            }

            .product-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .product-image {
                width: 40px;
                height: 40px;
            }

            .action-section {
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .method-info {
                padding: 1rem;
            }

            .method-cost {
                margin-left: 0;
                text-align: left;
                margin-top: 0.5rem;
            }

            .checkout-progress {
                flex-direction: column;
                gap: 0.5rem;
            }

            .progress-step {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }

            .quick-actions {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .checkout-title {
                font-size: 1.75rem;
            }

            .card-title {
                font-size: 1.2rem;
            }

            .product-table {
                font-size: 0.875rem;
            }

            .method-info {
                flex-direction: column;
                text-align: center;
            }

            .method-icon {
                width: 40px;
                height: 40px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <!-- Header Section -->
        <div class="checkout-header">
            <h1 class="checkout-title">Konfirmasi Pesanan</h1>
            <p class="checkout-subtitle">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran</p>

            <div class="checkout-progress">
                <div class="progress-step completed">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Keranjang
                </div>
                <div class="progress-step active">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Konfirmasi
                </div>
                <div class="progress-step inactive">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Pembayaran
                </div>
            </div>

            <!-- FIXED: Quick Action Buttons with Proper Routes -->
            <div class="quick-actions">
                @php
                    // Route existence check function for safer routing
                    $safeRoute = function ($routeName, $fallback = '#') {
                        try {
                            return \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : $fallback;
                        } catch (\Exception $e) {
                            return $fallback;
                        }
                    };
                @endphp

                <!-- FIXED: Payment History Route - Option 1 (Preferred) -->
                <a href="{{ $safeRoute('user.payment.history', $safeRoute('user.orders.index', '#')) }}" class="quick-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Pembayaran
                </a>

                <!-- Orders History -->
                <a href="{{ $safeRoute('user.orders.index') }}" class="quick-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Riwayat Pesanan
                </a>

                <!-- Profile/Address -->
                <a href="{{ $safeRoute('user.addresses.index', $safeRoute('user.profile.index', '#')) }}" class="quick-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Alamat Pengiriman
                </a>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="checkout-card">
            <div class="card-header">
                <h2 class="card-title">
                    🛍️ Ringkasan Pesanan
                    <span class="card-badge">{{ $cartItems->count() }} Item</span>
                </h2>
            </div>
            <div class="card-body">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align: center;">Jumlah</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                            $totalDiscount = 0;
                        @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $itemSubtotal = $item->price * $item->quantity;
                                $itemDiscount = ($item->discount ?? 0) * $item->quantity;
                                $subtotal += $itemSubtotal;
                                $totalDiscount += $itemDiscount;
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-info">
                                        @if ($item->product && $item->product->image_url)
                                            <img src="{{ asset($item->product->image_url) }}"
                                                alt="{{ $item->product->name ?? $item->name }}" class="product-image"
                                                onerror="this.onerror=null; this.src='{{ asset('images/produk/placeholder.png') }}';">
                                        @else
                                            <div class="product-image"
                                                style="display: flex; align-items: center; justify-content: center; background: var(--gray-200); color: var(--gray-400);">
                                                <svg width="20" height="20" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="product-details">
                                            <h4>{{ $item->product->name ?? ($item->name ?? 'Produk Tidak Ditemukan') }}</h4>
                                            <p>
                                                @if (($item->discount ?? 0) > 0)
                                                    <span style="text-decoration: line-through; color: var(--gray-400);">
                                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                                    </span>
                                                    <span
                                                        style="color: var(--success); font-weight: 600; margin-left: 0.5rem;">
                                                        Rp{{ number_format($item->price - ($item->discount ?? 0), 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                                @endif
                                            </p>
                                            @if ($item->promo_code)
                                                <span
                                                    style="background: var(--success); color: white; padding: 0.125rem 0.375rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                                    Promo: {{ $item->promo_code }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="quantity-badge">
                                        {{ $item->quantity }}×
                                    </span>
                                </td>
                                <td class="price-cell">
                                    @if (($item->discount ?? 0) > 0)
                                        Rp{{ number_format($itemSubtotal - $itemDiscount, 0, ',', '.') }}
                                        <br>
                                        <small style="color: var(--success); font-weight: 500;">
                                            Hemat Rp{{ number_format($itemDiscount, 0, ',', '.') }}
                                        </small>
                                    @else
                                        Rp{{ number_format($itemSubtotal, 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Summary Calculation -->
                @php
                    $selectedShipping = collect($shippingOptions)->firstWhere('id', request('shipping_method'));
                    $shippingCost = $selectedShipping ? $selectedShipping['price'] : 0;
                    $taxRate = 0.11;
                    $taxableAmount = $subtotal - $totalDiscount;
                    $tax = $taxableAmount * $taxRate;
                    $grandTotal = $taxableAmount + $shippingCost + $tax;
                @endphp

                <div class="summary-section">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal ({{ $cartItems->count() }} item)</span>
                        <span class="summary-value">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    @if ($totalDiscount > 0)
                        <div class="summary-row">
                            <span class="summary-label">💰 Diskon Produk</span>
                            <span class="summary-value"
                                style="color: var(--success);">-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="summary-row">
                        <span class="summary-label">🚚 Biaya Pengiriman</span>
                        <span class="summary-value">Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">📋 Pajak (11%)</span>
                        <span class="summary-value">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row summary-total">
                        <span class="summary-label">💳 Total Pembayaran</span>
                        <span class="summary-value">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Method Section -->
        <div class="checkout-card">
            <div class="card-header">
                <h2 class="card-title">
                    🚛 Metode Pengiriman
                </h2>
            </div>
            <div class="card-body">
                @if ($selectedShipping)
                    <div class="method-info">
                        <div class="method-icon">
                            🚛
                        </div>
                        <div class="method-details">
                            <h3>{{ $selectedShipping['name'] }}</h3>
                            <p>{{ $selectedShipping['description'] ?? 'Pengiriman terpercaya dengan layanan terbaik' }}</p>
                        </div>
                        <div class="method-cost">
                            <div class="cost-label">Biaya Pengiriman</div>
                            <div class="cost-value">Rp{{ number_format($selectedShipping['price'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                        <p>Metode pengiriman tidak ditemukan</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                            ← Kembali ke Keranjang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Method Section -->
        <div class="checkout-card">
            <div class="card-header">
                <h2 class="card-title">
                    💳 Metode Pembayaran
                </h2>
            </div>
            <div class="card-body">
                @php
                    $selectedPayment = $paymentMethods->firstWhere('code', request('payment_method'));
                @endphp
                @if ($selectedPayment)
                    <div class="method-info">
                        <div class="method-icon">
                            💳
                        </div>
                        <div class="method-details">
                            <h3>{{ $selectedPayment->name }}</h3>
                            <p>{{ $selectedPayment->description ?? 'Pembayaran aman dan terpercaya' }}</p>
                        </div>
                        @if ($selectedPayment->fee > 0)
                            <div class="method-cost">
                                <div class="cost-label">Biaya Admin</div>
                                <div class="cost-value">Rp{{ number_format($selectedPayment->fee, 0, ',', '.') }}</div>
                            </div>
                        @endif
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                        <p>Metode pembayaran tidak ditemukan</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                            ← Kembali ke Keranjang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Keranjang
            </a>

            @if ($selectedShipping && $selectedPayment)
                <form action="{{ route('payment.index') }}" method="GET">
                    @csrf
                    <input type="hidden" name="shipping_method" value="{{ request('shipping_method') }}">
                    <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                    <input type="hidden" name="discount" value="{{ $totalDiscount }}">
                    <input type="hidden" name="shipping_cost" value="{{ $shippingCost }}">
                    <input type="hidden" name="tax" value="{{ $tax }}">
                    <input type="hidden" name="grand_total" value="{{ $grandTotal }}">

                    {{-- Tambahkan data produk dari keranjang --}}
                    @foreach ($cartItems as $item)
                        <input type="hidden" name="cart_items[{{ $loop->index }}][product_id]"
                            value="{{ $item->product->id ?? '' }}">
                        <input type="hidden" name="cart_items[{{ $loop->index }}][product_name]"
                            value="{{ $item->product->name ?? ($item->name ?? '') }}">
                        <input type="hidden" name="cart_items[{{ $loop->index }}][quantity]"
                            value="{{ $item->quantity }}">
                        <input type="hidden" name="cart_items[{{ $loop->index }}][price]"
                            value="{{ $item->price }}">
                        <input type="hidden" name="cart_items[{{ $loop->index }}][discount]"
                            value="{{ $item->discount ?? 0 }}">
                    @endforeach

                    <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
                </form>
            @else
                <div style="flex: 1; max-width: 300px;">
                    <button type="button" class="btn btn-primary" disabled
                        style="background: var(--gray-400); cursor: not-allowed;">
                        Data Tidak Lengkap
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        /**
         * Enhanced Checkout Page Functionality
         * Updated: 2025-08-01 13:04:41 UTC by DenuJanuari
         * Fixed payment routes and enhanced error handling
         */
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Enhanced checkout page initialized - 2025-08-01 13:04:41 UTC by DenuJanuari');

            const checkoutForm = document.getElementById('checkout-form');
            const paymentBtn = document.getElementById('payment-btn');

            // Enhanced toast notification function
            function showToast(message, type = 'success', duration = 3000) {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.textContent = message;
                    toast.className = `toast ${type}`;
                    toast.classList.add('show');

                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, duration);
                }
            }

            // Enhanced form submission with error handling
            if (checkoutForm && paymentBtn) {
                checkoutForm.addEventListener('submit', function(e) {
                    // Prevent double submission
                    if (paymentBtn.classList.contains('btn-loading')) {
                        e.preventDefault();
                        return false;
                    }

                    // Validate form data
                    const shippingMethod = document.querySelector('input[name="shipping_method"]');
                    const paymentMethod = document.querySelector('input[name="payment_method"]');

                    if (!shippingMethod || !shippingMethod.value) {
                        e.preventDefault();
                        showToast('Metode pengiriman tidak valid', 'error');
                        return false;
                    }

                    if (!paymentMethod || !paymentMethod.value) {
                        e.preventDefault();
                        showToast('Metode pembayaran tidak valid', 'error');
                        return false;
                    }

                    // Show loading state
                    paymentBtn.classList.add('btn-loading');
                    paymentBtn.disabled = true;
                    paymentBtn.innerHTML = `
                        <div class="loading"></div>
                        Memproses Pesanan...
                    `;

                    showToast('Memproses pesanan Anda...', 'success', 2000);

                    // Set timeout to re-enable button if form submission fails
                    setTimeout(() => {
                        if (paymentBtn.classList.contains('btn-loading')) {
                            paymentBtn.classList.remove('btn-loading');
                            paymentBtn.disabled = false;
                            paymentBtn.innerHTML = `
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Lanjut ke Pembayaran
                            `;
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    }, 30000); // 30 second timeout
                });
            }

            // Enhanced card hover effects
            const cards = document.querySelectorAll('.checkout-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Quick action button click tracking
            const quickBtns = document.querySelectorAll('.quick-btn');
            quickBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (!href || href === '#') {
                        e.preventDefault();
                        showToast('Fitur sedang dalam pengembangan', 'warning');
                    }
                });
            });

            // Smooth scroll to sections when needed
            function smoothScrollTo(element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            // Add loading animation to product images
            const productImages = document.querySelectorAll('.product-image');
            productImages.forEach(img => {
                if (img.tagName === 'IMG') {
                    img.addEventListener('load', function() {
                        this.style.opacity = '0';
                        this.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            this.style.transition = 'all 0.3s ease';
                            this.style.opacity = '1';
                            this.style.transform = 'scale(1)';
                        }, 100);
                    });
                }
            });

            // Animate elements on page load
            function animateOnLoad() {
                const elements = document.querySelectorAll('.checkout-card, .checkout-header');
                elements.forEach((element, index) => {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }, index * 150);
                });
            }

            // Initialize animations
            setTimeout(animateOnLoad, 100);

            // Enhanced button interactions
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.98)';
                });

                button.addEventListener('mouseup', function() {
                    this.style.transform = '';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });

            // Add ripple effect to buttons
            function createRipple(event) {
                const button = event.currentTarget;
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = event.clientX - rect.left - size / 2;
                const y = event.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    left: ${x}px;
                    top: ${y}px;
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

                button.style.position = 'relative';
                button.style.overflow = 'hidden';
                button.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }

            // Add ripple animation CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Apply ripple effect to buttons
            buttons.forEach(button => {
                button.addEventListener('click', createRipple);
            });

            // Performance optimization: Preload critical styles
            const criticalStyles = document.createElement('style');
            criticalStyles.textContent = `
                .checkout-card {
                    transform: translateZ(0);
                    will-change: transform, box-shadow;
                }
                .btn {
                    transform: translateZ(0);
                    will-change: transform;
                }
            `;
            document.head.appendChild(criticalStyles);

            // Enhanced error handling for route issues
            window.addEventListener('error', function(e) {
                console.error('Page error:', e.error);
                showToast('Terjadi kesalahan pada halaman', 'error');
            });

            console.log(
                'Enhanced checkout functionality loaded successfully - 2025-08-01 13:04:41 UTC by DenuJanuari');
        });
    </script>
@endsection
