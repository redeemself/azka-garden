@extends('layouts.app')

@section('title', 'Keranjang Belanja - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        /* Modern Cart Styles - Updated: 2025-07-31 19:16:50 by DenuJanuari */
        :root {
            --primary-green: #16a34a;
            --dark-green: #14532d;
            --light-green: #dcfce7;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --red-500: #ef4444;
            --red-600: #dc2626;
            --blue-500: #3b82f6;
            --yellow-500: #eab308;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--gray-900);
            line-height: 1.6;
            min-height: 100vh;
        }

        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
            position: relative;
        }

        /* Modern Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--light-green);
            border-radius: 50%;
            border-top: 4px solid var(--primary-green);
            animation: modernSpin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.15);
        }

        .loading-text {
            color: var(--primary-green);
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .loading-subtext {
            color: var(--gray-500);
            font-size: 0.875rem;
            text-align: center;
            max-width: 300px;
            line-height: 1.5;
        }

        @keyframes modernSpin {
            0% {
                transform: rotate(0deg);
            }

            50% {
                transform: rotate(180deg) scale(1.1);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .page-subtitle {
            color: var(--gray-500);
            font-size: 1.125rem;
            font-weight: 500;
        }

        /* Modern Toast Notifications */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 20px;
            min-width: 350px;
            max-width: 450px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transform: translateX(120%);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            opacity: 0;
            border: 1px solid var(--gray-100);
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-success {
            border-left: 4px solid var(--primary-green);
        }

        .toast-error {
            border-left: 4px solid var(--red-500);
        }

        .toast-warning {
            border-left: 4px solid var(--yellow-500);
        }

        .toast-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            background: var(--light-green);
            color: var(--primary-green);
        }

        .toast-error .toast-icon {
            background: #fee2e2;
            color: var(--red-500);
        }

        .toast-warning .toast-icon {
            background: #fef3c7;
            color: var(--yellow-500);
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .toast-message {
            color: var(--gray-500);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .toast-close {
            color: var(--gray-300);
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: var(--gray-500);
            background-color: var(--gray-100);
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-100);
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-cart h3 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .empty-cart p {
            color: var(--gray-500);
            font-size: 1.125rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Cart Grid Layout */
        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }

        /* Modern Card Design */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-100);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--gray-50), white);
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .card-header .item-count {
            background: var(--primary-green);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Modern Cart Item */
        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .cart-item:hover {
            background: white;
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: var(--primary-green);
        }

        .cart-item.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 1.5rem;
            border: 2px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .cart-item:hover .item-image {
            transform: scale(1.05);
        }

        .item-placeholder {
            width: 80px;
            height: 80px;
            background: var(--gray-200);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color: var(--gray-400);
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .item-price {
            color: var(--gray-500);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Modern Quantity Controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 1rem;
            background: white;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .qty-btn {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            min-width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: var(--dark-green);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
        }

        .qty-btn:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .qty-btn .btn-text {
            transition: opacity 0.2s ease;
        }

        .qty-btn.loading .btn-text {
            opacity: 0;
        }

        .qty-btn .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .qty-btn.loading .spinner {
            opacity: 1;
        }

        .qty-input {
            width: 60px;
            padding: 8px 12px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            background: var(--gray-50);
            transition: all 0.2s ease;
        }

        .qty-input:focus {
            outline: none;
            border-color: var(--primary-green);
            background: white;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .item-total {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--primary-green);
            margin-right: 1.5rem;
            min-width: 120px;
            text-align: right;
        }

        /* Modern Remove Button */
        .remove-btn {
            background: var(--red-500);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .remove-btn:hover {
            background: var(--red-600);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .remove-btn:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Summary Card */
        .summary-card {
            position: sticky;
            top: 2rem;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--gray-900), #1f2937);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .summary-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .summary-body {
            padding: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem 0;
            color: var(--gray-700);
            font-weight: 500;
        }

        .summary-row:last-of-type {
            margin-bottom: 0;
        }

        .summary-row.total {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            padding: 1rem 0;
            border-top: 2px solid var(--gray-200);
            margin-top: 1rem;
        }

        .summary-row .amount {
            font-weight: 700;
        }

        .summary-row.discount .amount {
            color: var(--primary-green);
        }

        /* Modern Action Buttons */
        .btn-modern {
            width: 100%;
            padding: 16px 24px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), #22c55e);
            color: white;
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(22, 163, 74, 0.4);
        }

        .btn-primary:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .cart-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .cart-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                padding: 1rem;
            }

            .item-image,
            .item-placeholder {
                align-self: center;
                margin: 0 0 1rem 0;
            }

            .quantity-controls {
                margin: 0;
                justify-content: center;
            }

            .item-total {
                text-align: center;
                margin: 0;
            }

            .remove-btn {
                align-self: center;
                margin-top: 1rem;
            }

            .toast {
                min-width: 300px;
                margin-right: 12px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.75rem;
            }

            .cart-item {
                padding: 1rem 0.75rem;
            }

            .card-body {
                padding: 1rem;
            }

            .summary-body {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Modern Loading Overlay --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memproses Keranjang...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, sedang menyimpan perubahan</div>
    </div>

    {{-- Toast Container --}}
    <div class="toast-container" id="toastContainer"></div>

    <div class="cart-container">
        {{-- Modern Page Header --}}
        <div class="page-header">
            <h1 class="page-title">Keranjang Belanja</h1>
            <p class="page-subtitle">Kelola produk pilihan Anda sebelum checkout</p>
        </div>

        {{-- Empty Cart State --}}
        @if ($items->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h3>Keranjang Anda Kosong</h3>
                <p>Belum ada produk yang ditambahkan ke keranjang. Yuk, mulai belanja tanaman hias favorit Anda!</p>
                <a href="{{ route('products.index') }}" class="btn-modern btn-primary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6h12z" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            {{-- Cart Grid Layout --}}
            <div class="cart-grid">
                {{-- Cart Items Section --}}
                <div class="cart-main">
                    <div class="modern-card">
                        <div class="card-header">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <h2>Produk dalam Keranjang</h2>
                            <span class="item-count">{{ $items->count() }} item</span>
                        </div>
                        <div class="card-body">
                            @foreach ($items as $item)
                                <div class="cart-item" data-item-id="{{ $item->id }}"
                                    id="cart-item-{{ $item->id }}">
                                    {{-- Product Image --}}
                                    @if ($item->product && $item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                            alt="{{ $item->product->name ?? $item->name }}" class="item-image"
                                            loading="lazy">
                                    @else
                                        <div class="item-placeholder">
                                            <svg width="32" height="32" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2"
                                                    ry="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21,15 16,10 5,21" />
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Product Details --}}
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->product->name ?? $item->name }}</div>
                                        <div class="item-price">Rp{{ number_format($item->price, 0, ',', '.') }} / item
                                        </div>
                                    </div>

                                    {{-- Quantity Controls --}}
                                    <div class="quantity-controls">
                                        <button type="button" class="qty-btn qty-decrease"
                                            data-item-id="{{ $item->id }}">
                                            <span class="btn-text">-</span>
                                            <div class="spinner"></div>
                                        </button>
                                        <input type="number" name="quantity" min="1" max="999"
                                            value="{{ $item->quantity }}" class="qty-input"
                                            data-item-id="{{ $item->id }}" readonly>
                                        <button type="button" class="qty-btn qty-increase"
                                            data-item-id="{{ $item->id }}">
                                            <span class="btn-text">+</span>
                                            <div class="spinner"></div>
                                        </button>
                                    </div>

                                    {{-- Item Total --}}
                                    <div class="item-total" id="item-total-{{ $item->id }}">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>

                                    {{-- Remove Button --}}
                                    <button type="button" class="remove-btn" data-item-id="{{ $item->id }}"
                                        title="Hapus item">
                                        <svg width="20" height="20" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <polyline points="3,6 5,6 21,6"></polyline>
                                            <path
                                                d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Order Summary Section --}}
                <div class="cart-sidebar">
                    <div class="modern-card summary-card">
                        <div class="summary-header">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c.552 0 1-.449 1-1V6a2 2 0 00-2-2H4a2 2 0 00-2 2v6c0 .551.448 1 1 1"></path>
                            </svg>
                            <h2>Ringkasan Pesanan</h2>
                        </div>
                        <div class="summary-body">
                            @php
                                $subtotal = $items->sum(fn($i) => $i->price * $i->quantity);
                                $discount =
                                    session('promo_type') === 'percent'
                                        ? $subtotal * (session('promo_discount', 0) / 100)
                                        : session('promo_discount', 0);
                                $subtotalAfterDiscount = max(0, $subtotal - $discount);
                                $shippingFee = 15000;
                                $tax = $subtotalAfterDiscount * 0.1;
                                $total = $subtotalAfterDiscount + $shippingFee + $tax;
                            @endphp

                            {{-- Summary Rows --}}
                            <div class="summary-row" id="subtotal-row">
                                <span>Subtotal ({{ $items->count() }} item)</span>
                                <span class="amount"
                                    id="subtotal-amount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            @if ($discount > 0)
                                <div class="summary-row discount" id="discount-row">
                                    <span>Diskon Promo</span>
                                    <span class="amount"
                                        id="discount-amount">-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="summary-row">
                                <span>Ongkos Kirim</span>
                                <span class="amount">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Pajak (10%)</span>
                                <span class="amount" id="tax-amount">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row total">
                                <span>Total Pembayaran</span>
                                <span class="amount" id="final-total">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            {{-- Action Buttons --}}
                            <button type="button" class="btn-modern btn-primary" id="checkout-btn">
                                <svg width="20" height="20" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4"></path>
                                    <path d="M21 12c.552 0 1-.449 1-1V6a2 2 0 00-2-2H4a2 2 0 00-2 2v6c0 .551.448 1 1 1">
                                    </path>
                                </svg>
                                <span class="btn-text">Lanjut ke Checkout</span>
                            </button>

                            <a href="{{ route('products.index') }}" class="btn-modern btn-secondary">
                                <svg width="20" height="20" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6h12z" />
                                </svg>
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            /**
             * Modern Cart Functionality - Enhanced Checkout Integration
             * Updated: 2025-07-31 19:30:57 by DenuJanuari
             */
            document.addEventListener('DOMContentLoaded', function() {
                // Configuration
                const CONFIG = {
                    LOADING_DELAY: 100,
                    TOAST_DURATION: 3500,
                    ANIMATION_SPEED: 300,
                    DEBOUNCE_DELAY: 300
                };

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Build cart base URL dynamically
                const cartBaseUrl = window.location.origin + '/user/cart';

                // Cache DOM elements
                const loadingOverlay = document.getElementById('loadingOverlay');
                const toastContainer = document.getElementById('toastContainer');

                // Loading functions
                function showLoading() {
                    if (loadingOverlay) {
                        setTimeout(() => {
                            loadingOverlay.classList.add('active');
                        }, CONFIG.LOADING_DELAY);
                    }
                }

                function hideLoading() {
                    if (loadingOverlay) {
                        loadingOverlay.classList.remove('active');
                    }
                }

                // Toast notifications
                function showToast(type, title, message, duration = CONFIG.TOAST_DURATION) {
                    if (!toastContainer) return;

                    const toast = document.createElement('div');
                    toast.className = `toast toast-${type}`;
                    toast.innerHTML = `
            <div class="toast-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        : type === 'error'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'}
                </svg>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" aria-label="Tutup">&times;</button>
        `;

                    toastContainer.appendChild(toast);

                    // Close button event
                    toast.querySelector('.toast-close').onclick = () => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), CONFIG.ANIMATION_SPEED);
                    };

                    // Show animation
                    requestAnimationFrame(() => {
                        toast.classList.add('show');
                    });

                    // Auto remove
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.classList.remove('show');
                            setTimeout(() => toast.remove(), CONFIG.ANIMATION_SPEED);
                        }
                    }, duration);
                }

                // Button loading states
                function setButtonLoading(button, isLoading) {
                    if (!button) return;

                    const btnText = button.querySelector('.btn-text');
                    const spinner = button.querySelector('.spinner');

                    if (isLoading) {
                        button.disabled = true;
                        button.classList.add('loading');
                        if (btnText) btnText.style.opacity = '0';
                        if (spinner) spinner.style.opacity = '1';
                    } else {
                        button.disabled = false;
                        button.classList.remove('loading');
                        if (btnText) btnText.style.opacity = '1';
                        if (spinner) spinner.style.opacity = '0';
                    }
                }

                // Update cart summary
                function updateCartSummary() {
                    const items = document.querySelectorAll('.cart-item');
                    let subtotal = 0;
                    let itemCount = 0;

                    items.forEach(item => {
                        const qtyInput = item.querySelector('.qty-input');
                        const priceText = item.querySelector('.item-price').textContent;
                        const price = parseInt(priceText.replace(/[^\d]/g, ''));
                        const quantity = parseInt(qtyInput.value) || 0;
                        const itemTotal = price * quantity;

                        // Update item total
                        const itemTotalElement = item.querySelector('.item-total');
                        if (itemTotalElement) {
                            itemTotalElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(
                                itemTotal);
                        }

                        subtotal += itemTotal;
                        itemCount += quantity;
                    });

                    // Update subtotal
                    const subtotalElement = document.getElementById('subtotal-amount');
                    const subtotalRow = document.getElementById('subtotal-row');

                    if (subtotalElement && subtotalRow) {
                        subtotalElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
                        subtotalRow.querySelector('span:first-child').textContent = `Subtotal (${itemCount} item)`;
                    }

                    // Recalculate totals
                    const discountRow = document.getElementById('discount-row');
                    let discount = 0;

                    if (discountRow) {
                        const discountText = discountRow.querySelector('.amount').textContent;
                        discount = parseInt(discountText.replace(/[^\d]/g, '')) || 0;
                    }

                    const subtotalAfterDiscount = Math.max(0, subtotal - discount);
                    const shippingFee = 15000;
                    const tax = Math.round(subtotalAfterDiscount * 0.1);
                    const total = subtotalAfterDiscount + shippingFee + tax;

                    // Update tax and total
                    const taxElement = document.getElementById('tax-amount');
                    const totalElement = document.getElementById('final-total');

                    if (taxElement) {
                        taxElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(tax);
                    }

                    if (totalElement) {
                        totalElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
                    }
                }

                // Update quantity
                function updateQuantity(itemId, newQuantity) {
                    const button = document.querySelector(`[data-item-id="${itemId}"].qty-btn`);
                    const cartItem = document.getElementById(`cart-item-${itemId}`);
                    const qtyInput = document.querySelector(`input[data-item-id="${itemId}"]`);

                    if (newQuantity < 1) {
                        showToast('error', 'Error', 'Jumlah produk minimal 1');
                        return;
                    }

                    setButtonLoading(button, true);
                    cartItem.classList.add('loading');
                    showLoading();

                    fetch(`${cartBaseUrl}/update/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity: newQuantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            setButtonLoading(button, false);
                            cartItem.classList.remove('loading');
                            hideLoading();

                            if (data.success) {
                                qtyInput.value = newQuantity;
                                updateCartSummary();
                                showToast('success', 'Berhasil!', 'Quantity berhasil diupdate');
                            } else {
                                showToast('error', 'Gagal', data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            setButtonLoading(button, false);
                            cartItem.classList.remove('loading');
                            hideLoading();
                            console.error('Update error:', error);
                            showToast('error', 'Error', 'Terjadi kesalahan jaringan');
                        });
                }

                // Remove item
                function removeItem(itemId) {
                    const cartItem = document.getElementById(`cart-item-${itemId}`);
                    const removeBtn = cartItem.querySelector('.remove-btn');

                    if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                        return;
                    }

                    removeBtn.disabled = true;
                    cartItem.style.opacity = '0.6';
                    showLoading();

                    fetch(`${cartBaseUrl}/remove/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();

                            if (data.success) {
                                // Smooth removal animation
                                cartItem.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                                cartItem.style.transform = 'translateX(-100%) scale(0.8)';
                                cartItem.style.opacity = '0';

                                setTimeout(() => {
                                    cartItem.remove();
                                    updateCartSummary();

                                    // Check if cart is empty
                                    const remainingItems = document.querySelectorAll('.cart-item');
                                    if (remainingItems.length === 0) {
                                        showToast('success', 'Berhasil!', 'Item berhasil dihapus');
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        showToast('success', 'Berhasil!', 'Item berhasil dihapus');
                                    }
                                }, 400);
                            } else {
                                removeBtn.disabled = false;
                                cartItem.style.opacity = '1';
                                showToast('error', 'Gagal', data.message || 'Gagal menghapus item');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            removeBtn.disabled = false;
                            cartItem.style.opacity = '1';
                            console.error('Remove error:', error);
                            showToast('error', 'Error', 'Terjadi kesalahan jaringan');
                        });
                }

                // Event listeners
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.qty-decrease')) {
                        e.preventDefault();
                        const itemId = e.target.closest('.qty-decrease').dataset.itemId;
                        const qtyInput = document.querySelector(`input[data-item-id="${itemId}"]`);
                        const currentQty = parseInt(qtyInput.value);
                        if (currentQty > 1) {
                            updateQuantity(itemId, currentQty - 1);
                        }
                    }

                    if (e.target.closest('.qty-increase')) {
                        e.preventDefault();
                        const itemId = e.target.closest('.qty-increase').dataset.itemId;
                        const qtyInput = document.querySelector(`input[data-item-id="${itemId}"]`);
                        const currentQty = parseInt(qtyInput.value);
                        if (currentQty < 999) {
                            updateQuantity(itemId, currentQty + 1);
                        }
                    }

                    if (e.target.closest('.remove-btn')) {
                        e.preventDefault();
                        const itemId = e.target.closest('.remove-btn').dataset.itemId;
                        removeItem(itemId);
                    }
                });

                // Enhanced checkout functionality - kirim data ke halaman checkout
                const checkoutBtn = document.getElementById('checkout-btn');
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        showLoading();

                        // Check if items exist before checkout
                        const items = document.querySelectorAll('.cart-item');
                        if (items.length === 0) {
                            hideLoading();
                            showToast('warning', 'Peringatan',
                                'Keranjang kosong, tidak dapat melanjutkan checkout');
                            return;
                        }

                        // Collect cart data
                        const cartData = {
                            items: [],
                            summary: {},
                            user: 'DenuJanuari',
                            timestamp: new Date().toISOString()
                        };

                        // Collect item data
                        items.forEach(item => {
                            const itemId = item.dataset.itemId;
                            const itemName = item.querySelector('.item-name').textContent.trim();
                            const itemPrice = item.querySelector('.item-price').textContent.replace(
                                /[^\d]/g, '');
                            const itemQuantity = item.querySelector('.qty-input').value;
                            const itemTotal = item.querySelector('.item-total').textContent.replace(
                                /[^\d]/g, '');

                            cartData.items.push({
                                id: itemId,
                                name: itemName,
                                price: parseInt(itemPrice),
                                quantity: parseInt(itemQuantity),
                                total: parseInt(itemTotal)
                            });
                        });

                        // Collect summary data with proper fallbacks
                        const subtotalElement = document.getElementById('subtotal-amount');
                        const taxElement = document.getElementById('tax-amount');
                        const finalTotalElement = document.getElementById('final-total');
                        const discountElement = document.getElementById('discount-amount');

                        cartData.summary = {
                            subtotal: subtotalElement ? parseInt(subtotalElement.textContent.replace(
                                /[^\d]/g, '')) : 0,
                            tax: taxElement ? parseInt(taxElement.textContent.replace(/[^\d]/g, '')) : 0,
                            total: finalTotalElement ? parseInt(finalTotalElement.textContent.replace(
                                /[^\d]/g, '')) : 0,
                            discount: discountElement ? parseInt(discountElement.textContent.replace(
                                /[^\d]/g, '')) : 0,
                            shipping: 15000,
                            itemCount: items.length
                        };

                        // Simplified approach: Store in localStorage and redirect
                        try {
                            // Store data in localStorage as backup
                            localStorage.setItem('checkout_data', JSON.stringify(cartData));

                            // Try AJAX first, but don't fail if it doesn't work
                            fetch('/user/cart/prepare-checkout', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(cartData)
                                })
                                .then(response => {
                                    // Don't check if response is ok, just proceed
                                    hideLoading();
                                    showToast('success', 'Berhasil!', 'Mengarahkan ke halaman checkout...');

                                    setTimeout(() => {
                                        window.location.href = '/checkout';
                                    }, 1000);
                                })
                                .catch(error => {
                                    // If AJAX fails, proceed anyway with URL parameters
                                    console.log('AJAX failed, using URL fallback:', error);
                                    proceedWithUrlFallback();
                                });

                        } catch (error) {
                            console.log('Error in checkout process:', error);
                            proceedWithUrlFallback();
                        }

                        // Fallback function using URL parameters
                        function proceedWithUrlFallback() {
                            hideLoading();

                            try {
                                const urlParams = new URLSearchParams({
                                    items: JSON.stringify(cartData.items),
                                    summary: JSON.stringify(cartData.summary),
                                    user: cartData.user,
                                    timestamp: cartData.timestamp,
                                    source: 'url_fallback'
                                });

                                showToast('success', 'Berhasil!', 'Mengarahkan ke halaman checkout...');
                                setTimeout(() => {
                                    window.location.href = `/checkout?${urlParams.toString()}`;
                                }, 1000);

                            } catch (urlError) {
                                console.error('URL fallback failed:', urlError);
                                // Last resort: redirect to checkout without data
                                showToast('warning', 'Peringatan', 'Mengarahkan ke checkout...');
                                setTimeout(() => {
                                    window.location.href = '/checkout';
                                }, 1000);
                            }
                        }
                    });
                }

                // Initialize
                hideLoading();
                console.log('Modern cart functionality loaded - 2025-07-31 19:30:57 by DenuJanuari');
            });
        </script>
    @endpush
@endsection
