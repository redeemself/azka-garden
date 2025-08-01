@extends('layouts.app')

@section('title', 'Keranjang Belanja')

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
            --error: #ef4444;
            --warning: #f59e0b;
            --border-radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 50%, #dcfce7 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(22, 163, 74, 0.1);
        }

        .cart-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .cart-subtitle {
            color: var(--gray-600);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .cart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(22, 163, 74, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-bg) 0%, rgba(220, 252, 231, 0.8) 100%);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(22, 163, 74, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Product Item Styling */
        .product-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item:hover {
            background: rgba(22, 163, 74, 0.02);
        }

        .product-image-container {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: var(--border-radius);
            overflow: hidden;
            border: 2px solid var(--gray-200);
            background: var(--gray-100);
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .product-image-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            color: var(--gray-400);
        }

        .product-info {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .product-price {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-price-original {
            text-decoration: line-through;
            color: var(--gray-400);
            margin-right: 0.5rem;
        }

        .product-price-discounted {
            color: var(--success);
            font-weight: 600;
        }

        .promo-badge {
            display: inline-block;
            background: var(--success);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin-top: 0.25rem;
        }

        /* Quantity Controls */
        .quantity-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 100px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-50);
            border-radius: 50px;
            padding: 0.25rem;
            border: 1px solid var(--gray-200);
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: white;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .quantity-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            transform: scale(1.05);
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--gray-200);
            color: var(--gray-400);
        }

        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            color: var(--gray-800);
            padding: 0.25rem;
        }

        /* Price Section */
        .price-section {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
            min-width: 100px;
        }

        .price-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .price-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .price-discount {
            color: var(--success);
            font-size: 0.875rem;
        }

        /* Remove Button */
        .remove-btn {
            background: transparent;
            border: none;
            color: var(--error);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            transform: scale(1.1);
        }

        /* Promo Section */
        .promo-section {
            background: linear-gradient(135deg, var(--success) 0%, var(--accent) 100%);
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }

        .promo-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .promo-description {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .promo-form {
            display: flex;
            gap: 0.5rem;
        }

        .promo-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: var(--gray-800);
        }

        .promo-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .promo-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .promo-remove-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Summary Section */
        .summary-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #a7f3d0;
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
            font-weight: 700;
            font-size: 1.1rem;
            background: var(--primary-bg);
            margin: 0 -1.5rem -1.5rem;
            padding: 1.5rem;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .summary-label {
            color: var(--gray-700);
            font-weight: 500;
        }

        .summary-value {
            color: var(--gray-800);
            font-weight: 600;
        }

        .summary-discount {
            color: var(--success);
        }

        .summary-promo-detail {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: normal;
            margin-left: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            color: var(--success);
            font-weight: 600;
        }

        /* Address Section */
        .address-section {
            margin-top: 1rem;
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .address-title {
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .add-address-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-address-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .address-card {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .address-card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow);
        }

        .address-card.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
            box-shadow: var(--shadow-lg);
        }

        .address-card.selected::before {
            content: '✓';
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: bold;
        }

        .address-label {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .address-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .address-details {
            color: var(--gray-600);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .distance-info {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }

        /* Modern Dropdown Styling */
        .custom-dropdown-container {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
        }

        .custom-dropdown {
            width: 100%;
            padding: 1rem 3rem 1rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            color: var(--gray-800);
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .custom-dropdown:hover {
            border-color: var(--primary-light);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
            transform: translateY(-1px);
        }

        .custom-dropdown:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
            transform: translateY(-1px);
        }

        .dropdown-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .custom-dropdown:focus+.dropdown-icon {
            transform: translateY(-50%) rotate(180deg);
        }

        /* Custom Option Styling */
        .custom-dropdown option {
            padding: 0.75rem;
            background: white;
            color: var(--gray-800);
            font-size: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-dropdown option:hover {
            background: var(--primary-bg);
            color: var(--primary-dark);
        }

        .custom-dropdown option:checked {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        /* Dropdown Label Styling */
        .dropdown-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Loading State */
        .custom-dropdown.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .custom-dropdown.loading+.dropdown-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: translateY(-50%) rotate(0deg);
            }

            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        /* Enhanced Visual Feedback */
        .custom-dropdown:invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .custom-dropdown:valid {
            border-color: var(--success);
        }

        /* Interactive Hover Effects */
        .custom-dropdown-container:hover .dropdown-icon {
            color: var(--primary-dark);
            transform: translateY(-50%) scale(1.1);
        }

        /* Smooth Animations */
        .custom-dropdown-container {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-dropdown-container:focus-within {
            transform: translateY(-2px);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
        }

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1.125rem;
            font-weight: 700;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-600);
        }

        .empty-cart-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            color: var(--gray-400);
        }

        .empty-cart h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-cart p {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Toast */
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
            max-width: 300px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-container {
                padding: 1rem 0.5rem;
            }

            .cart-title {
                font-size: 2rem;
            }

            .cart-header {
                padding: 1.5rem 1rem;
                margin-bottom: 1.5rem;
            }

            .card-header,
            .card-body {
                padding: 1rem;
            }

            .product-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
            }

            .product-image-container {
                align-self: center;
            }

            .product-info {
                text-align: center;
                width: 100%;
            }

            .quantity-section {
                align-self: center;
                flex-direction: row;
                align-items: center;
                width: auto;
            }

            .price-section {
                align-self: center;
                align-items: center;
                text-align: center;
            }

            .promo-form {
                flex-direction: column;
            }

            .promo-btn {
                width: 100%;
            }

            .address-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .toast {
                right: 1rem;
                left: 1rem;
                max-width: none;
            }

            .custom-dropdown {
                padding: 0.875rem 2.5rem 0.875rem 0.875rem;
                font-size: 16px;
                /* Prevent zoom on iOS */
            }

            .dropdown-icon {
                right: 0.75rem;
                width: 18px;
                height: 18px;
            }
        }

        @media (max-width: 480px) {
            .cart-title {
                font-size: 1.75rem;
            }

            .product-image-container {
                width: 60px;
                height: 60px;
            }

            .quantity-btn {
                width: 28px;
                height: 28px;
            }

            .card-header,
            .card-body {
                padding: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Keranjang Belanja</h1>
            <p class="cart-subtitle">Kelola produk yang akan Anda beli dengan mudah</p>
        </div>

        @if ($cartItems->isEmpty())
            <div class="cart-card">
                <div class="empty-cart">
                    <svg class="empty-cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3>Keranjang Anda Kosong</h3>
                    <p>Mulai berbelanja dan tambahkan produk ke keranjang Anda</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-large">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="cart-grid">
                <!-- Products Section -->
                <div class="cart-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            🛒 Daftar Produk
                        </h2>
                        <span class="px-3 py-1 text-sm bg-green-100 rounded-full text-green-800 ml-auto">
                            {{ $cartItems->count() }} item
                        </span>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @php
                            // Hitung subtotal dan discount dengan benar
                            $subtotalAwal = 0; // Harga sebelum diskon
                            $totalItemDiscount = 0; // Total diskon dari item

                            foreach ($cartItems as $item) {
                                $originalPrice = $item->product ? $item->product->price : $item->price;
                                $itemDiscount = $item->discount ?? 0;

                                $subtotalAwal += $originalPrice * $item->quantity;
                                $totalItemDiscount += $itemDiscount * $item->quantity;
                            }
                        @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $originalPrice = $item->product ? $item->product->price : $item->price;
                                $itemDiscount = $item->discount ?? 0;
                                $finalPrice = $originalPrice - $itemDiscount;

                                $itemOriginalTotal = $originalPrice * $item->quantity;
                                $itemDiscountTotal = $itemDiscount * $item->quantity;
                                $itemFinalTotal = $finalPrice * $item->quantity;
                            @endphp
                            <div class="product-item" id="cart-item-{{ $item->id }}">
                                <div class="product-image-container">
                                    @if ($item->product && $item->product->image_url)
                                        <img src="{{ asset($item->product->image_url) }}"
                                            alt="{{ $item->product->name ?? $item->name }}" class="product-image"
                                            loading="lazy"
                                            onerror="this.onerror=null; this.src='{{ asset('images/produk/placeholder.png') }}';">
                                    @else
                                        <div class="product-image-fallback">
                                            <svg width="32" height="32" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="product-info">
                                    <div class="product-name">{{ $item->product->name ?? $item->name }}</div>
                                    <div class="product-price">
                                        @if ($itemDiscount > 0)
                                            <span
                                                class="product-price-original">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                                            <span
                                                class="product-price-discounted">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                        @else
                                            Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                        @endif
                                    </div>
                                    @if ($item->promo_code)
                                        <div class="promo-badge">Promo: {{ $item->promo_code }}</div>
                                    @endif
                                </div>

                                <div class="quantity-section">
                                    <span class="price-label">Kuantitas</span>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn decrement-btn"
                                            data-item-id="{{ $item->id }}"
                                            data-url="{{ route('cart.update', $item->id) }}"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="quantity-display">{{ $item->quantity }}</span>
                                        <button type="button" class="quantity-btn increment-btn"
                                            data-item-id="{{ $item->id }}"
                                            data-url="{{ route('cart.update', $item->id) }}"
                                            {{ $item->product && $item->product->stock <= $item->quantity ? 'disabled' : '' }}>
                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="price-section">
                                    <span class="price-label">Harga</span>
                                    <div class="price-value">
                                        @if ($itemDiscount > 0)
                                            <div>Rp{{ number_format($finalPrice, 0, ',', '.') }}</div>
                                            <div class="price-discount">Hemat
                                                Rp{{ number_format($itemDiscount, 0, ',', '.') }}</div>
                                        @else
                                            Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="price-section">
                                    <span class="price-label">Subtotal</span>
                                    <div class="price-value">
                                        @if ($itemDiscount > 0)
                                            Rp{{ number_format($itemFinalTotal, 0, ',', '.') }}
                                        @else
                                            Rp{{ number_format($itemOriginalTotal, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <span class="price-label">Aksi</span>
                                    <button type="button" class="remove-btn" data-item-id="{{ $item->id }}"
                                        data-url="{{ route('cart.remove', $item->id) }}">
                                        <svg width="20" height="20" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Promo Section -->
                <div class="promo-section">
                    <h3 class="promo-title">🎯 Kode Promo</h3>
                    @if (Session::has('promo_code'))
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="font-medium">{{ Session::get('promo_code') }}</div>
                                <div class="promo-description">{{ Session::get('promo_description') }}</div>
                            </div>
                            <form action="{{ route('promo.deactivate') }}" method="POST">
                                @csrf
                                <button type="submit" class="promo-remove-btn">Hapus</button>
                            </form>
                        </div>
                    @else
                        <div class="promo-description">Promo khusus subscriber newsletter</div>
                        <form action="{{ route('promo.activate') }}" method="POST" class="promo-form">
                            @csrf
                            <input type="text" name="promo_code" placeholder="Masukkan kode promo"
                                class="promo-input">
                            <button type="submit" class="promo-btn">Terapkan</button>
                        </form>
                    @endif
                </div>

                <!-- Enhanced Summary Section dengan format yang benar -->
                <div class="summary-section">
                    <h2
                        style="color: var(--primary-dark); font-size: 1.25rem; font-weight: 700; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        🧾 Ringkasan Belanja
                    </h2>

                    @php
                        // Hitung session promo discount
                        $sessionPromoCode = Session::get('promo_code');
                        $sessionPromoType = Session::get('promo_type', 'fixed');
                        $sessionPromoDiscount = Session::get('promo_discount', 0);
                        $sessionPromoDescription = Session::get('promo_description', '');

                        // Hitung subtotal setelah diskon item
                        $subtotalSetelahDiskonItem = $subtotalAwal - $totalItemDiscount;

                        // Hitung session promo discount
                        $sessionDiscountAmount = 0;
                        if ($sessionPromoCode && $sessionPromoDiscount > 0) {
                            if ($sessionPromoType === 'percent') {
                                $sessionDiscountAmount = ($subtotalSetelahDiskonItem * $sessionPromoDiscount) / 100;
                            } else {
                                $sessionDiscountAmount = $sessionPromoDiscount;
                            }
                        }

                        // Total semua diskon
                        $totalAllDiscounts = $totalItemDiscount + $sessionDiscountAmount;

                        // Subtotal final setelah semua diskon
                        $subtotalFinal = $subtotalAwal - $totalAllDiscounts;

                        // Tax dari subtotal final
                        $taxRate = 0.11;
                        $tax = $subtotalFinal * $taxRate;

                        // Persentase hemat
                        $discountPercentage = $subtotalAwal > 0 ? ($totalAllDiscounts / $subtotalAwal) * 100 : 0;
                    @endphp

                    <!-- Subtotal Awal (tetap sama sesuai request) -->
                    <div class="summary-row">
                        <span class="summary-label">Subtotal Awal ({{ $cartItems->count() }} item)</span>
                        <span class="summary-value">Rp{{ number_format($subtotalAwal, 0, ',', '.') }}</span>
                    </div>

                    <!-- Diskon Promo Session jika ada -->
                    @if ($sessionPromoCode && $sessionDiscountAmount > 0)
                        <div class="summary-row summary-discount">
                            <span class="summary-label">
                                🎯 Diskon Promo ({{ $sessionPromoCode }})
                                @if ($sessionPromoType === 'percent')
                                    <span class="summary-promo-detail">{{ $sessionPromoDiscount }}%</span>
                                @endif
                            </span>
                            <span class="summary-value">-Rp{{ number_format($sessionDiscountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <!-- Total Hemat jika ada diskon -->
                    @if ($totalAllDiscounts > 0)
                        <div class="summary-row summary-discount">
                            <span class="summary-label">
                                💸 Total Hemat
                                <span class="summary-promo-detail">({{ number_format($discountPercentage, 1) }}% dari
                                    total)</span>
                            </span>
                            <span class="summary-value">-Rp{{ number_format($totalAllDiscounts, 0, ',', '.') }}</span>
                        </div>

                        <!-- Subtotal Setelah Diskon -->
                        <div class="summary-row">
                            <span class="summary-label">Subtotal Setelah Diskon</span>
                            <span class="summary-value">Rp{{ number_format($subtotalFinal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <!-- Biaya Pengiriman -->
                    <div class="summary-row">
                        <span class="summary-label">🚚 Biaya Pengiriman</span>
                        <span class="summary-value" id="shipping-cost-display">Rp0</span>
                    </div>

                    <!-- Pajak -->
                    <div class="summary-row">
                        <span class="summary-label">📋 Pajak (11%)</span>
                        <span class="summary-value">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                    </div>

                    <!-- Total Pembayaran -->
                    <div class="summary-row">
                        <span class="summary-label">💳 Total Pembayaran</span>
                        <span class="summary-value"
                            id="grand-total">Rp{{ number_format($subtotalFinal + $tax, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Address Selection -->
                @auth
                    @php
                        $userAddresses = auth()->user()->addresses ?? collect();
                    @endphp

                    <div class="cart-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                📍 Alamat Pengiriman
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="address-header">
                                <h3 class="address-title"></h3>
                                <a href="{{ route('user.address.create') }}" class="add-address-btn">
                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Alamat
                                </a>
                            </div>

                            @forelse($userAddresses as $address)
                                <div class="address-card {{ $address->is_primary ? 'selected' : '' }}"
                                    data-address-id="{{ $address->id }}" data-latitude="{{ $address->latitude }}"
                                    data-longitude="{{ $address->longitude }}" onclick="selectAddress(this)">
                                    <div class="address-label">
                                        {{ $address->label }}{{ $address->is_primary ? ' (Utama)' : '' }}
                                    </div>
                                    <div class="address-name">{{ $address->recipient }}</div>
                                    <div class="address-details">
                                        📞 {{ $address->phone_number }}<br>
                                        📍 {{ $address->full_address }}<br>
                                        🏙️ {{ $address->city }}, {{ $address->zip_code }}
                                    </div>
                                    @if ($address->is_primary && $address->latitude && $address->longitude)
                                        <div class="distance-info">
                                            <strong>📏 Jarak ke toko:</strong><br>
                                            3,1 km dari Azka Garden
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <svg width="48" height="48" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p>Belum ada alamat tersimpan</p>
                                    <a href="{{ route('user.address.create') }}" class="add-address-btn">📍 Tambah Alamat</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endauth

                <!-- Checkout Form -->
                <div class="cart-card">
                    <div class="card-body">
                        <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
                            <!-- Modern Shipping Method Dropdown -->
                            <div class="custom-dropdown-container">
                                <label class="dropdown-label">
                                    🚛 Metode Pengiriman
                                </label>
                                <select name="shipping_method" id="shipping_method" class="custom-dropdown" required>
                                    <option value="" disabled selected>Pilih metode pengiriman...</option>
                                    @foreach ($shippingOptions as $option)
                                        <option value="{{ $option['id'] }}" data-price="{{ $option['price'] }}"
                                            data-description="{{ $option['description'] ?? '' }}">
                                            {{ $option['name'] }} - Rp{{ number_format($option['price'], 0, ',', '.') }}
                                            @if (!empty($option['description']))
                                                | {{ $option['description'] }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="dropdown-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Modern Payment Method Dropdown -->
                            <div class="custom-dropdown-container">
                                <label class="dropdown-label">
                                    💳 Metode Pembayaran
                                </label>
                                <select name="payment_method" id="payment_method" class="custom-dropdown" required>
                                    <option value="" disabled selected>Pilih metode pembayaran...</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->code }}"
                                            data-description="{{ $method->description ?? '' }}">
                                            {{ $method->name }}
                                            @if ($method->description)
                                                | {{ $method->description }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="dropdown-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Hidden inputs dengan nilai yang sudah dihitung dengan benar -->
                            <input type="hidden" name="subtotal" id="subtotal-input" value="{{ $subtotalFinal }}">
                            <input type="hidden" name="discount" id="discount-input" value="{{ $totalAllDiscounts }}">
                            <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">
                            <input type="hidden" name="tax" id="tax-input" value="{{ $tax }}">
                            <input type="hidden" name="grand_total" id="grand-total-input"
                                value="{{ $subtotalFinal + $tax }}">
                            <input type="hidden" name="selected_address_id" id="selected-address-input" value="">

                            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="flex: 1;">
                                    ⬅️ Lanjut Belanja
                                </a>
                                <button type="submit" class="btn btn-primary btn-large" style="flex: 2;">
                                    ➡️ Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        /**
         * Enhanced Cart Management System with Fixed Discount Calculation
         * Updated: 2025-08-01 11:52:35 UTC by DenuJanuari
         */
        document.addEventListener('DOMContentLoaded', function() {
            console.log(
                'Enhanced cart page with fixed discount calculation initialized - 2025-08-01 11:52:35 UTC by DenuJanuari'
            );

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // Price calculation elements
            const shippingMethodSelect = document.getElementById('shipping_method');
            const paymentMethodSelect = document.getElementById('payment_method');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const grandTotalDisplay = document.getElementById('grand-total');
            const subtotalInput = document.getElementById('subtotal-input');
            const discountInput = document.getElementById('discount-input');
            const shippingCostInput = document.getElementById('shipping-cost-input');
            const taxInput = document.getElementById('tax-input');
            const grandTotalInput = document.getElementById('grand-total-input');
            const selectedAddressInput = document.getElementById('selected-address-input');

            // Initial values (sekarang menggunakan nilai yang sudah benar)
            const subtotal = parseFloat(subtotalInput?.value) || 0; // Subtotal setelah semua diskon
            const discount = parseFloat(discountInput?.value) || 0; // Total semua diskon
            const tax = parseFloat(taxInput?.value) || 0; // Tax dari subtotal final
            let shippingCost = 0;
            let selectedAddress = null;

            // Format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            // Calculate totals dengan logic yang sudah diperbaiki
            function calculateTotals() {
                if (shippingMethodSelect && shippingCostDisplay && grandTotalDisplay) {
                    const selectedOption = shippingMethodSelect.options[shippingMethodSelect.selectedIndex];
                    const baseShippingCost = parseFloat(selectedOption?.dataset?.price) || 0;

                    shippingCost = baseShippingCost;

                    // Update shipping cost display
                    shippingCostDisplay.textContent = 'Rp' + formatCurrency(shippingCost);
                    if (shippingCostInput) shippingCostInput.value = shippingCost;

                    // Calculate grand total: subtotal (setelah diskon) + shipping + tax
                    const grandTotal = subtotal + shippingCost + tax;
                    grandTotalDisplay.textContent = 'Rp' + formatCurrency(grandTotal);
                    if (grandTotalInput) grandTotalInput.value = grandTotal;

                    console.log('Fixed total calculation:', {
                        subtotalAfterAllDiscounts: subtotal,
                        totalDiscounts: discount,
                        shipping: shippingCost,
                        tax: tax,
                        grandTotal: grandTotal,
                        timestamp: '2025-08-01 11:52:35',
                        user: 'DenuJanuari'
                    });
                }
            }

            // Enhanced dropdown functionality
            function addLoadingState(dropdown) {
                if (!dropdown) return;
                dropdown.classList.add('loading');
                setTimeout(() => {
                    dropdown.classList.remove('loading');
                }, 500);
            }

            // Select address function
            window.selectAddress = function(addressElement) {
                // Remove selection from all addresses
                document.querySelectorAll('.address-card').forEach(card => {
                    card.classList.remove('selected');
                });

                // Select current address
                addressElement.classList.add('selected');

                const addressId = addressElement.dataset.addressId;

                if (selectedAddressInput) {
                    selectedAddressInput.value = addressId;
                }

                selectedAddress = {
                    id: addressId
                };

                // Show feedback
                showToast('Alamat pengiriman telah dipilih', 'success');
            };

            // Initialize
            calculateTotals();

            // Enhanced shipping method change handler
            if (shippingMethodSelect) {
                shippingMethodSelect.addEventListener('change', function() {
                    addLoadingState(this);

                    const selectedOption = this.options[this.selectedIndex];
                    const description = selectedOption.dataset.description;

                    // Show description if available
                    if (description) {
                        console.log('Shipping method selected:', description);
                        showToast('Metode pengiriman: ' + selectedOption.textContent.split(' - ')[0],
                            'success', 2000);
                    }

                    calculateTotals();
                });
            }

            // Enhanced payment method change handler
            if (paymentMethodSelect) {
                paymentMethodSelect.addEventListener('change', function() {
                    addLoadingState(this);

                    const selectedOption = this.options[this.selectedIndex];
                    const description = selectedOption.dataset.description;

                    // Show description if available
                    if (description) {
                        console.log('Payment method selected:', description);
                        showToast('Metode pembayaran: ' + selectedOption.textContent.split(' | ')[0],
                            'success', 2000);
                    }
                });
            }

            // Auto-select primary address if available
            const primaryAddress = document.querySelector('.address-card.selected');
            if (primaryAddress) {
                selectAddress(primaryAddress);
            }

            // Toast notification
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

            // Cart operations
            function createFetchOptions(method, action = null) {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                };

                if (action) {
                    options.body = JSON.stringify({
                        action: action
                    });
                }

                return options;
            }

            function safeFetch(url, options) {
                return fetch(url, options)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Network response error: ${response.status}`);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan pada sistem. Silakan coba lagi.', 'error');
                        throw error;
                    });
            }

            // Update cart quantity with proper discount recalculation
            function updateCartQuantity(button, action) {
                const url = button.dataset.url;
                const row = button.closest('.product-item');
                const quantityDisplay = row?.querySelector('.quantity-display');
                const decrementBtn = row?.querySelector('.decrement-btn');
                const incrementBtn = row?.querySelector('.increment-btn');

                // Show loading
                const originalContent = button.innerHTML;
                button.innerHTML = '<div class="loading"></div>';
                button.disabled = true;

                safeFetch(url, createFetchOptions('POST', action))
                    .then(data => {
                        if (data.success) {
                            if (quantityDisplay) {
                                quantityDisplay.textContent = data.quantity;
                            }

                            // Update button states
                            if (decrementBtn) {
                                decrementBtn.disabled = data.quantity <= 1;
                            }
                            if (incrementBtn) {
                                incrementBtn.disabled = data.stock_limit_reached || false;
                            }

                            showToast(
                                `Jumlah produk berhasil ${action === 'increment' ? 'ditambah' : 'dikurangi'}`
                            );

                            // Reload page to recalculate all totals including discounts
                            setTimeout(() => {
                                window.location.reload();
                            }, 800);
                        } else {
                            showToast(data.message ||
                                `Gagal ${action === 'increment' ? 'menambah' : 'mengurangi'} jumlah produk`,
                                'error');
                        }
                    })
                    .catch(() => {
                        // Reset button
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    });
            }

            // Increment quantity
            document.querySelectorAll('.increment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    updateCartQuantity(this, 'increment');
                });
            });

            // Decrement quantity
            document.querySelectorAll('.decrement-btn').forEach(button => {
                button.addEventListener('click', function() {
                    updateCartQuantity(this, 'decrement');
                });
            });

            // Remove item
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                        const url = this.dataset.url;
                        const row = this.closest('.product-item');

                        safeFetch(url, createFetchOptions('DELETE'))
                            .then(data => {
                                if (data.success) {
                                    if (row) {
                                        row.style.transition = 'all 0.3s';
                                        row.style.opacity = '0';

                                        setTimeout(() => {
                                            row.remove();
                                            showToast(
                                                'Produk berhasil dihapus dari keranjang'
                                            );

                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 1000);
                                        }, 300);
                                    }
                                } else {
                                    showToast(data.message ||
                                        'Gagal menghapus produk dari keranjang', 'error');
                                }
                            });
                    }
                });
            });

            // Enhanced form validation
            const checkoutForm = document.getElementById('checkout-form');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                        // Check shipping method
                        if (!shippingMethodSelect?.value) {
                            e.preventDefault();
                            showToast('Silakan pilih metode pengiriman terlebih dahulu', 'warning');
                            shippingMethodSelect?.focus();
                            return false;
                        }

                        // Check payment method
                        if (!paymentMethodSelect?.value) {
                            e.preventDefault();
                            showToast('Silakan pilih metode pembayaran terlebih dahulu', 'warning');
                            paymentMethodSelect?.focus();
                            return false;
                        }

                        @auth
                        if (!selectedAddressInput?.value) {
                            e.preventDefault();
                            showToast('Silakan pilih alamat pengiriman terlebih dahulu', 'warning');
                            return false;
                        }
                    @endauth

                    // Show processing state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<div class="loading"></div> Memproses...';
                    }

                    return true;
                });
        }

        // Enhanced keyboard navigation for dropdowns
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                const focusedElement = document.activeElement;
                if (focusedElement?.classList.contains('custom-dropdown')) {
                    const container = focusedElement.closest('.custom-dropdown-container');
                    if (container) {
                        container.style.transform = 'translateY(-2px)';
                    }
                }
            }
        });

        document.addEventListener('focusout', function(e) {
            if (e.target?.classList.contains('custom-dropdown')) {
                const container = e.target.closest('.custom-dropdown-container');
                if (container) {
                    container.style.transform = '';
                }
            }
        });

        // Enhanced accessibility
        const dropdowns = document.querySelectorAll('.custom-dropdown'); dropdowns.forEach(dropdown => {
            dropdown.addEventListener('focus', function() {
                const description = this.options[this.selectedIndex]?.dataset?.description;
                if (description) {
                    this.setAttribute('aria-description', description);
                }
            });

            dropdown.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const description = selectedOption?.dataset?.description;
                if (description) {
                    this.setAttribute('aria-description', description);
                }
            });
        });

        // Enhanced error handling for promo code
        const promoForm = document.querySelector('.promo-form');
        if (promoForm) {
            promoForm.addEventListener('submit', function(e) {
                const promoInput = this.querySelector('input[name="promo_code"]');
                const promoCode = promoInput?.value?.trim();

                if (!promoCode) {
                    e.preventDefault();
                    showToast('Silakan masukkan kode promo', 'warning');
                    promoInput?.focus();
                    return false;
                }

                if (promoCode.length < 3) {
                    e.preventDefault();
                    showToast('Kode promo minimal 3 karakter', 'warning');
                    promoInput?.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<div class="loading"></div> Memproses...';
                }
            });
        }

        // Monitor discount changes and update display
        function updateDiscountDisplay() {
            const discountElements = document.querySelectorAll('.price-discount, .summary-discount');
            discountElements.forEach(element => {
                if (element.textContent.includes('Hemat') || element.textContent.includes('Diskon')) {
                    element.style.animation = 'pulse 0.5s ease-in-out';
                    setTimeout(() => {
                        element.style.animation = '';
                    }, 500);
                }
            });
        }

        // Add pulse animation for discount badges
        const style = document.createElement('style'); style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }

                .promo-badge, .discount-badge {
                    animation: fadeIn 0.3s ease-in-out;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                .summary-discount .summary-value {
                    font-weight: 700;
                    color: var(--success) !important;
                }

                .summary-promo-detail {
                    background: rgba(16, 185, 129, 0.1);
                    padding: 0.15rem 0.4rem;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    color: var(--success);
                    font-weight: 600;
                }
            `; document.head.appendChild(style);

        // Real-time validation feedback
        function addValidationFeedback() {
            const requiredDropdowns = document.querySelectorAll('.custom-dropdown[required]');
            requiredDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.add('valid');
                        this.classList.remove('invalid');
                    } else {
                        this.classList.remove('valid');
                        this.classList.add('invalid');
                    }
                });

                // Initial validation check
                if (dropdown.value) {
                    dropdown.classList.add('valid');
                }
            });
        }

        // Initialize validation feedback
        addValidationFeedback();

        // Auto-save cart state to localStorage for recovery
        function saveCartState() {
            const cartState = {
                subtotal: subtotal,
                discount: discount,
                tax: tax,
                shippingCost: shippingCost,
                timestamp: new Date().toISOString(),
                itemCount: document.querySelectorAll('.product-item').length,
                updatedBy: 'redeemself',
                version: '2025-08-01 11:43:49'
            };
            localStorage.setItem('azka_cart_state', JSON.stringify(cartState));
        }

        // Save cart state on page load
        saveCartState();

        // Monitor for cart changes and auto-save
        const cartObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' &&
                    (mutation.target.classList.contains('product-item') ||
                        mutation.target.closest('.product-item'))) {
                    saveCartState();
                    updateDiscountDisplay();
                }
            });
        });

        const cartContainer = document.querySelector('.card-body');
        if (cartContainer) {
            cartObserver.observe(cartContainer, {
                childList: true,
                subtree: true
            });
        }

        // Enhanced discount calculation logging
        function logDiscountCalculation() {
            const totalElements = document.querySelectorAll('.summary-discount .summary-value');
            let totalDiscountAmount = 0;

            totalElements.forEach(element => {
                const text = element.textContent.replace(/[^\d]/g, '');
                if (text) {
                    totalDiscountAmount += parseInt(text);
                }
            });

            console.log('Discount calculation summary:', {
                totalDiscountDisplayed: totalDiscountAmount,
                calculatedDiscount: discount,
                subtotalAfterDiscount: subtotal,
                timestamp: '2025-08-01 11:43:49',
                user: 'redeemself'
            });
        }

        // Call discount calculation logging
        setTimeout(logDiscountCalculation, 1000);

        // Enhanced error logging
        window.addEventListener('error', function(e) {
            console.error('Cart page error:', {
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                timestamp: '2025-08-01 11:43:49',
                user: 'redeemself'
            });
        });

        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log('Cart page performance:', {
                        loadTime: perfData.loadEventEnd - perfData.loadEventStart,
                        domContentLoaded: perfData.domContentLoadedEventEnd - perfData
                            .domContentLoadedEventStart,
                        discountCalculationTime: performance.now(),
                        timestamp: '2025-08-01 11:43:49',
                        user: 'redeemself'
                    });
                }, 0);
            });
        }

        // Promo code auto-complete functionality
        const promoInput = document.querySelector('.promo-input');
        if (promoInput) {
            // Store common promo codes for auto-suggestion
            const commonPromoCodes = ['PROMOJULI10', 'NEWUSER', 'DISCOUNT20', 'SAVE15'];

            promoInput.addEventListener('input', function() {
                const value = this.value.toUpperCase();
                if (value.length >= 2) {
                    const suggestions = commonPromoCodes.filter(code =>
                        code.startsWith(value)
                    );

                    if (suggestions.length > 0) {
                        console.log('Promo suggestions:', suggestions);
                    }
                }
            });

            // Auto-format promo code to uppercase
            promoInput.addEventListener('blur', function() {
                this.value = this.value.toUpperCase().trim();
            });
        }

        // Cart summary animation on load
        const summaryRows = document.querySelectorAll('.summary-row'); summaryRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';

            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add visual feedback for successful operations
        function addSuccessAnimation(element) {
            if (element) {
                element.style.transform = 'scale(1.05)';
                element.style.transition = 'transform 0.2s ease';

                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 200);
            }
        }

        // Enhanced toast with action buttons
        function showToastWithAction(message, type = 'success', duration = 5000, actionText = null, actionCallback =
            null) {
            const toast = document.getElementById('toast');
            if (toast) {
                let toastContent = message;

                if (actionText && actionCallback) {
                    toastContent +=
                        ` <button onclick="(${actionCallback.toString()})()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; margin-left: 0.5rem; cursor: pointer;">${actionText}</button>`;
                }

                toast.innerHTML = toastContent;
                toast.className = `toast ${type}`;
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, duration);
            }
        }

        // Initialize discount percentage display update
        function updateDiscountPercentage() {
            const totalHematElement = document.querySelector('.summary-row:has(.summary-promo-detail)');
            if (totalHematElement) {
                addSuccessAnimation(totalHematElement);
            }
        }

        // Call update on page load
        setTimeout(updateDiscountPercentage, 500);

        console.log(
            'Enhanced cart functionality with detailed discount calculation loaded successfully - 2025-08-01 11:43:49 UTC by redeemself'
        );
        });
    </script>
@endsection
