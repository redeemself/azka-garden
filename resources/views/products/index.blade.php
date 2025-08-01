@extends('layouts.app')

@section('title', 'Produk Kami')

@section('content')
    @php
        $user = auth()->user();

        // Cek apakah user punya alamat (relasi Eloquent harus method)
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();

        // Data promo dari session - with proper type casting to prevent errors
        $promo_code = session('promo_code') ?? '';
        $promo_type = session('promo_type') ?? '';
        $promo_discount = (float) (session('promo_discount') ?? 0);
        $promo_discount_percent = $promo_type === 'percent' ? 10.0 : null;

        // Improved cart count calculation with cache busting - FIXED QUERY
        if (auth()->check()) {
            // Fix: Changed whereColumn to where since we're comparing with a value, not another column
    $cartItemCount = \App\Models\Cart::where('user_id', auth()->id())
        ->where('quantity', '>', 0) // Correct usage of where clause
        ->sum('quantity');
} else {
    // Clear any stale cart data from session if empty
    $cartItems = session('cart_items') ?? (session('cartItems') ?? collect([]));
    if (!($cartItems instanceof \Illuminate\Support\Collection)) {
        $cartItems = collect($cartItems);
    }
    $cartItemCount = $cartItems->sum('quantity');

    // If cart is empty but counter isn't 0, reset session
            if ($cartItems->isEmpty() && session('cart_count', 0) > 0) {
                session()->forget(['cart_items', 'cartItems', 'cart_count']);
                $cartItemCount = 0;
            }
        }

        // Store the accurate count in session for reference
        session(['cart_count' => $cartItemCount]);

        // Preload hero images untuk mengurangi lag saat tampil
        $heroImageUrls = [
            asset('images/hero-1.jpg'),
            asset('images/hero-2.jpg'),
            asset('images/hero-3.jpg'),
            asset('images/hero-4.jpg'),
            asset('images/hero-5.jpg'),
            asset('images/hero-6.jpg'),
            asset('images/hero-7.jpg'),
        ];

        // Data tanggal dan user saat ini (updated dengan timestamp terbaru)
        $currentDateTime = '2025-08-01 05:11:26';
        $currentUser = 'DenuJanuari';
    @endphp
    {{-- DNS Prefetch dan Preconnect untuk optimasi --}}
    @push('head')
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        @foreach ($heroImageUrls as $imageUrl)
            <link rel="preload" href="{{ $imageUrl }}" as="image">
        @endforeach
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Critical CSS inlined untuk faster rendering --}}
        <style>
            /* Critical above-the-fold styles */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(8px);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.2s ease
            }

            .loading-overlay.active {
                opacity: 1;
                visibility: visible
            }

            .loading-spinner {
                width: 50px;
                height: 50px;
                border: 3px solid #e5f3e9;
                border-radius: 50%;
                border-top: 3px solid #16a34a;
                animation: fastSpin 0.6s linear infinite;
                margin-bottom: 16px
            }

            @keyframes fastSpin {
                0% {
                    transform: rotate(0deg)
                }

                100% {
                    transform: rotate(360deg)
                }
            }

            .hero-layer {
                will-change: opacity;
                transform: translateZ(0)
            }

            .product-card {
                will-change: transform;
                transform: translateZ(0)
            }
        </style>
    @endpush

    {{-- Optimized CSS dengan critical rendering path --}}
    <style>
        /* Base styles - Optimized for performance */
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            will-change: transform;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            position: relative;
            contain: layout style paint;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.1);
        }

        .product-image-container {
            overflow: hidden;
            position: relative;
            background-color: #f9fafb;
            contain: layout;
        }

        .product-image {
            transition: transform 0.3s ease;
            will-change: transform;
            object-fit: contain;
            transform: translateZ(0);
        }

        .product-card:hover .product-image {
            transform: scale(1.03) translateZ(0);
        }

        /* Already in cart state */
        .product-card.in-cart {
            border-color: #16a34a;
            background-color: #f0fdf4;
        }

        .product-card.in-cart .product-status-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #16a34a;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            z-index: 20;
        }

        /* Ultra-fast loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.15s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Optimized loading spinner */
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #e5f3e9;
            border-radius: 50%;
            border-top: 3px solid #16a34a;
            animation: fastSpin 0.6s linear infinite;
            margin-bottom: 16px;
            transform: translateZ(0);
        }

        .loading-text {
            color: #16a34a;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .loading-subtext {
            color: #6b7280;
            font-size: 0.8rem;
            text-align: center;
            max-width: 250px;
            line-height: 1.4;
        }

        /* Ultra-fast confirmation modal */
        .confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .confirmation-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .confirmation-content {
            background: white;
            border-radius: 16px;
            padding: 28px;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
            transform: scale(0.95) translateY(10px);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .confirmation-modal.active .confirmation-content {
            transform: scale(1) translateY(0);
        }

        .confirmation-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
        }

        .confirmation-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            position: relative;
        }

        .confirmation-icon svg {
            color: #16a34a;
            z-index: 1;
        }

        .confirmation-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .confirmation-message {
            color: #6b7280;
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .confirmation-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .confirmation-btn {
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .confirmation-btn-primary {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            box-shadow: 0 3px 12px rgba(22, 163, 74, 0.3);
        }

        .confirmation-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(22, 163, 74, 0.4);
        }

        .confirmation-btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .confirmation-btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }

        /* Optimized Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 20px -5px rgba(0, 0, 0, 0.1);
            padding: 16px;
            min-width: 320px;
            max-width: 400px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transform: translateX(120%);
            transition: all 0.3s ease;
            opacity: 0;
            border: 1px solid #f3f4f6;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-success {
            border-left: 3px solid #16a34a;
        }

        .toast-error {
            border-left: 3px solid #dc2626;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            color: #111827;
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .toast-message {
            color: #6b7280;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .toast-close {
            color: #9ca3af;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #4b5563;
            background-color: #f3f4f6;
        }

        .toast-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            background: #dcfce7;
            color: #16a34a;
        }

        .toast-error .toast-icon {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Ultra-fast animations */
        @keyframes fastSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes quickPulse {

            0%,
            100% {
                opacity: 0.1;
                transform: scale(1);
            }

            50% {
                opacity: 0.2;
                transform: scale(1.02);
            }
        }

        @keyframes fastFadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fastFadeIn 0.3s ease-out;
        }

        /* Hero carousel - optimized */
        .hero-layer {
            transition: opacity 0.8s ease-out;
            will-change: opacity;
            background-size: cover;
            background-position: center;
            transform: translateZ(0);
        }

        /* Hero indicators */
        .hero-indicators {
            position: absolute;
            bottom: 1.5rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            z-index: 30;
        }

        .hero-indicator {
            width: 3rem;
            height: 0.25rem;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 0.125rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .hero-indicator.active {
            background-color: white;
            width: 4rem;
        }

        /* Optimized discount badge */
        .discount-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.3);
            z-index: 20;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .discount-badge:before {
            content: '';
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
            display: block;
        }

        /* Banner section - optimized */
        .banner-section {
            width: 100%;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
            contain: layout;
        }

        .banner-image-container {
            width: 100%;
            position: relative;
            overflow: hidden;
            height: auto;
        }

        .banner-image {
            width: 100%;
            object-fit: cover;
            display: block;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.15);
        }

        .banner-content {
            text-align: center;
            width: 100%;
            padding: 0 1rem;
            z-index: 10;
        }

        .banner-title {
            color: white;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .promo-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #15803d;
            font-size: 1.25rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Responsive optimizations */
        @media (min-width: 769px) {
            .banner-image-container {
                height: 240px;
            }

            .banner-image {
                height: 100%;
                transform: scale(0.85);
                transform-origin: center;
            }

            .banner-title {
                font-size: 2.5rem;
            }

            .promo-badge {
                width: 80px;
                height: 80px;
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .banner-section {
                margin-bottom: 1rem;
            }

            .banner-image-container {
                height: 180px;
            }

            .banner-image {
                height: 100%;
                object-position: center;
            }

            .banner-title {
                font-size: 1.5rem;
                max-width: 90%;
            }

            .promo-badge {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                top: 10px;
                right: 10px;
            }

            .confirmation-content {
                padding: 20px;
                margin: 16px;
            }

            .toast {
                min-width: 280px;
                margin-right: 10px;
            }
        }

        /* Search and promo section */
        .search-filter-container {
            width: 100%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 16px;
            margin-bottom: 2rem;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .search-filter-container {
                padding: 1rem;
                border-radius: 12px;
            }
        }

        @media (max-width: 480px) {

            .search-input,
            .category-select,
            .search-button,
            .promo-input,
            .promo-button {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Product count */
        .product-count-badge {
            background: #166534;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Promo active notification */
        .promo-active-notification {
            background: linear-gradient(135deg, #dcfce7, #f0fdf4);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 640px) {
            .promo-active-notification {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .promo-active-notification form {
                width: 100%;
            }

            .promo-active-notification button {
                width: 100%;
            }
        }

        /* Optimized product grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            width: 100%;
            contain: layout;
        }

        @media (max-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
            }
        }

        /* Optimized cart floating button */
        .cart-floating-button {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 50;
            background-color: #15803d;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            transform: translateZ(0);
        }

        .cart-floating-button:hover {
            transform: scale(1.05) translateZ(0);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .cart-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            font-size: 12px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 2px solid white;
        }

        /* Optimized button states */
        .add-to-cart-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.15s ease;
        }

        .add-to-cart-btn.loading .btn-text {
            visibility: hidden;
        }

        .add-to-cart-btn.loading .btn-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: inline-block;
            width: 18px;
            height: 18px;
        }

        .add-to-cart-btn.success {
            background-color: #16a34a !important;
        }

        .add-to-cart-btn:disabled {
            background-color: #9ca3af !important;
            cursor: not-allowed;
        }

        .in-cart-btn {
            background-color: #6b7280 !important;
            cursor: not-allowed;
        }

        /* Performance optimizations */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    </style>

    {{-- Ultra-fast Loading overlay --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memuat Data...</div>
        <div class="loading-subtext">Sebentar ya, sedang diproses</div>
    </div>

    {{-- Optimized Confirmation Modal --}}
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-icon">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="confirmation-title" id="confirmationTitle">Produk berhasil ditambahkan!</h3>
            <p class="confirmation-message" id="confirmationMessage">Mau lihat keranjang sekarang?</p>
            <div class="confirmation-buttons">
                <button class="confirmation-btn confirmation-btn-secondary" id="confirmationCancel">Lanjut Belanja</button>
                <button class="confirmation-btn confirmation-btn-primary" id="confirmationOk">Lihat Keranjang</button>
            </div>
        </div>
    </div>

    {{-- Toast container --}}
    <div class="toast-container" id="toastContainer"></div>

    {{-- Optimized cart floating button --}}
    <a href="{{ url('/cart') }}" class="cart-floating-button" aria-label="Lihat Keranjang">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span id="cart-counter" class="cart-counter" aria-live="polite" aria-atomic="true">{{ $cartItemCount }}</span>
    </a>

    {{-- Peringatan jika user login belum punya alamat --}}
    @auth
        @if (!$hasAddress)
            <div x-data="{ show: true }" x-show="show" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" style="backdrop-filter: blur(3px);">
                <div class="relative flex flex-col items-center w-full max-w-xs p-6 bg-white shadow-2xl rounded-xl fade-in"
                    role="alert" aria-live="assertive" aria-atomic="true">
                    <button @click="show = false"
                        class="absolute p-1 text-gray-400 rounded-full hover:bg-gray-100 top-3 right-3 hover:text-gray-600"
                        aria-label="Tutup Peringatan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center w-16 h-16 mb-4 bg-green-100 rounded-full"
                            aria-hidden="true">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-gray-800">Alamat Belum Lengkap</h3>
                        <p class="mb-5 text-sm text-center text-gray-600">Anda belum mengisi alamat rumah! Silakan lengkapi
                            alamat Anda di halaman profil agar bisa melakukan pembelian.</p>
                        <a href="{{ route('user.profile.index') }}"
                            class="w-full px-4 py-2 mb-2 font-medium text-center text-white transition bg-green-600 rounded-md shadow-sm hover:bg-green-700">
                            Isi Alamat Sekarang
                        </a>
                        <button @click="show = false"
                            class="w-full px-4 py-2 font-medium text-center text-gray-800 transition bg-gray-100 border border-gray-200 rounded-md hover:bg-gray-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    {{-- Optimized Hero carousel dengan Alpine.js --}}
    <section x-data="{
        heroImages: {{ json_encode($heroImageUrls) }},
        currentBg: 0,
        nextBg: 1,
        frontLayer: true,
        interval: null,
        transitionDuration: 800,
        transitioning: false,
    
        init() {
            // Fast preload with smaller images first
            this.preloadImages();
            this.startInterval();
        },
        preloadImages() {
            // Preload critical images only
            const img1 = new Image();
            img1.src = this.heroImages[0];
            const img2 = new Image();
            img2.src = this.heroImages[1];
        },
        startInterval() {
            this.stopInterval();
            this.interval = setInterval(() => this.nextBackground(), 5000);
        },
        stopInterval() {
            if (this.interval) clearInterval(this.interval);
        },
        nextBackground() {
            let next = (this.currentBg + 1) % this.heroImages.length;
            this.performTransition(next);
            // Lazy preload next image
            const nextImg = new Image();
            nextImg.src = this.heroImages[(next + 1) % this.heroImages.length];
        },
        performTransition(nextIndex) {
            if (this.transitioning || nextIndex === this.currentBg) return;
            this.transitioning = true;
            this.nextBg = nextIndex;
            this.frontLayer = !this.frontLayer;
            setTimeout(() => {
                this.currentBg = nextIndex;
                this.transitioning = false;
            }, this.transitionDuration);
        },
        changeBackground(idx) {
            if (this.transitioning) return;
            this.stopInterval();
            this.performTransition(idx);
            this.startInterval();
        }
    }" x-init="init" class="relative min-h-screen overflow-hidden">
        {{-- Background Layers --}}
        <div class="absolute inset-0 bg-center bg-cover transition-opacity duration-[800ms] ease-in-out z-10 hero-layer"
            :class="frontLayer ? 'opacity-100' : 'opacity-0'" :style="`background-image: url('${heroImages[currentBg]}');`">
        </div>
        <div class="absolute inset-0 bg-center bg-cover transition-opacity duration-[800ms] ease-in-out z-0 hero-layer"
            :class="!frontLayer ? 'opacity-100' : 'opacity-0'" :style="`background-image: url('${heroImages[nextBg]}');`">
        </div>
        <div class="absolute inset-0 pointer-events-none bg-gradient-to-br from-black/60 via-black/50 to-green-900/40">
        </div>

        {{-- Hero Indicators --}}
        <div class="hero-indicators" role="tablist" aria-label="Navigasi Gambar Hero">
            <template x-for="(img, idx) in heroImages" :key="idx">
                <button type="button" role="tab" class="hero-indicator" :class="idx === currentBg ? 'active' : ''"
                    @click="changeBackground(idx)" :aria-selected="idx === currentBg" :tabindex="idx === currentBg ? 0 : -1"
                    :aria-label="`Gambar ${idx + 1}`"></button>
            </template>
        </div>

        <div class="relative z-30 px-4 mx-auto max-w-7xl">
            {{-- Banner --}}
            @if (isset($banners) && count($banners))
                @php $banner = $banners[0]; @endphp
                <div class="banner-section" role="region" aria-label="Banner Promo">
                    <div class="banner-image-container">
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title ?? 'Promo Tanaman Hias Juli' }}"
                            class="banner-image" loading="eager" decoding="async" />
                        <div class="banner-overlay">
                            <div class="banner-content">
                                <h2 class="banner-title">{{ $banner->title ?? 'Promo Tanaman Hias Juli' }}</h2>
                            </div>
                            <div class="promo-badge" aria-label="Diskon 10%">10%</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form pencarian dan promo --}}
            <div class="search-filter-container" role="search">
                <div class="grid gap-4 md:grid-cols-2">
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-3"
                        aria-label="Form Pencarian Produk">
                        {{-- Form contents... --}}
                    </form>

                    <form method="POST" action="{{ route('promo.activate') }}" id="promoForm" class="flex flex-col"
                        aria-label="Form Aktivasi Kode Promo">
                        @csrf
                        <div class="flex-1 p-4 border border-green-200 rounded-lg bg-green-50">
                            <p class="mb-2 font-semibold text-green-800">Punya kode promo? Aktifkan disini:</p>
                            <div class="flex flex-wrap gap-2">
                                <input type="text" name="promo_code" value="{{ old('promo_code', $promo_code) }}"
                                    placeholder="Masukkan kode promo"
                                    class="flex-1 px-4 py-2 min-w-[150px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 promo-input"
                                    aria-label="Input kode promo" />
                                <button type="submit"
                                    class="px-5 py-2 text-white transition-all bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 promo-button"
                                    aria-label="Aktifkan kode promo">
                                    Aktifkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Notifikasi promo aktif --}}
            @if ($promo_code && $promo_type !== null && $promo_discount > 0)
                <div class="promo-active-notification" role="region" aria-live="polite" aria-atomic="true"
                    aria-label="Promo aktif">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full"
                            aria-hidden="true">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-green-800">Promo aktif: <span
                                    class="font-mono">{{ $promo_code }}</span></p>
                            <p class="text-sm text-green-700">
                                Diskon
                                {{ $promo_type === 'percent' ? $promo_discount_percent . '%' : 'Rp ' . number_format($promo_discount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('promo.deactivate') }}" aria-label="Nonaktifkan promo">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-white transition-all bg-gray-800 rounded-lg hover:bg-gray-900">Nonaktifkan</button>
                    </form>
                </div>
            @endif

            {{-- Produk dan jumlah --}}
            <div class="flex items-center justify-between my-6">
                <h1 class="text-3xl font-bold text-white drop-shadow-lg">Produk Kami</h1>
                <div class="product-count-badge" aria-live="polite" aria-atomic="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    {{ $products->count() }} Produk
                </div>
            </div>

            {{-- Grid produk --}}
            <div class="mb-12 product-grid">
                @forelse($products as $product)
                    @php
                        $final_price = $product->price;
                        $promo_label = '';
                        $promo_active = false;
                        $diskon = 0;

                        if (auth()->check() && $promo_code && $promo_type !== null && $promo_discount !== null) {
                            if ($promo_type === 'percent') {
                                $diskon = round($product->price * (10 / 100));
                                $promo_label = '10%';
                            } elseif ($promo_type === 'fixed') {
                                $diskon = min($promo_discount, $product->price);
                                $promo_label = 'Rp ' . number_format($diskon, 0, ',', '.');
                            }
                            $final_price = max(0, $product->price - $diskon);
                            $promo_active = $final_price < $product->price;
                        }

                        // Check if product is already in cart
                        $isInCart = false;
                        if (auth()->check()) {
                            $isInCart = \App\Models\Cart::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->exists();
                        } else {
                            $cartItems = session('cart_items', []);
                            foreach ($cartItems as $item) {
                                if ($item['product_id'] == $product->id) {
                                    $isInCart = true;
                                    break;
                                }
                            }
                        }

                        $displayImages = collect($product->images ?? [])
                            ->filter(fn($img) => preg_match('/\.(jpg|jpeg|png)$/i', trim($img->image_url ?? '')))
                            ->unique('image_url')
                            ->sortBy(
                                fn($img) => preg_match('/\.jpg$/i', $img->image_url)
                                    ? 0
                                    : (preg_match('/\.png$/i', $img->image_url)
                                        ? 1
                                        : 2),
                            )
                            ->values();
                    @endphp

                    <article class="product-card overflow-hidden rounded-xl {{ $isInCart ? 'in-cart' : '' }}"
                        role="group" aria-labelledby="product-{{ $product->id }}-title">
                        @if ($promo_active)
                            <div class="discount-badge" aria-label="Diskon {{ $promo_label }}">DISKON
                                {{ $promo_label }}</div>
                        @endif

                        @if ($isInCart)
                            <div class="product-status-badge" aria-label="Sudah di keranjang">✓ Di Keranjang</div>
                        @endif

                        <div class="p-2 product-image-container">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($displayImages->take(2) as $index => $img)
                                    <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                        <img src="{{ asset($img->image_url) }}"
                                            alt="{{ $product->name }} {{ $index + 1 }}"
                                            class="w-full h-full product-image" loading="lazy" decoding="async"
                                            onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                    </div>
                                @endforeach
                                @if ($displayImages->count() < 2)
                                    @if ($displayImages->count() === 1)
                                        <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                            <img src="{{ asset($displayImages->first()->image_url) }}"
                                                alt="{{ $product->name }} 1" class="w-full h-full product-image"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                        </div>
                                        <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                            <img src="{{ asset($product->image_url ?? 'images/produk/placeholder.png') }}"
                                                alt="{{ $product->name }} 2" class="w-full h-full product-image"
                                                loading="lazy" decoding="async" />
                                        </div>
                                    @else
                                        <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                            <img src="{{ asset($product->image_url ?? 'images/produk/placeholder.png') }}"
                                                alt="{{ $product->name }} 1" class="w-full h-full product-image"
                                                loading="lazy" decoding="async" />
                                        </div>
                                        <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                            <img src="{{ asset('images/produk/placeholder.png') }}"
                                                alt="{{ $product->name }} 2" class="w-full h-full product-image"
                                                loading="lazy" decoding="async" />
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="p-5">
                            <h2 id="product-{{ $product->id }}-title"
                                class="mb-2 text-xl font-semibold text-gray-800 line-clamp-2">{{ $product->name }}</h2>
                            <p class="mb-4 text-sm text-gray-600 line-clamp-2">{{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="mb-4">
                                @if ($promo_active)
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 line-through">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span
                                            class="px-2 py-0.5 ml-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">-{{ $promo_label }}</span>
                                    </div>
                                    <div class="mt-1 text-xl font-bold text-green-700">Rp
                                        {{ number_format($final_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="text-xl font-bold text-gray-800">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('products.show', $product->id) }}"
                                    class="flex items-center justify-center px-4 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all"
                                    aria-label="Lihat detail {{ $product->name }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Detail
                                </a>

                                @auth
                                    @if ($isInCart)
                                        <button
                                            class="flex items-center justify-center w-full px-4 py-2.5 text-white bg-gray-500 rounded-lg cursor-not-allowed in-cart-btn"
                                            disabled title="Produk sudah ada di keranjang" aria-disabled="true">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Di Keranjang
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('products.add-to-cart', $product->id) }}"
                                            class="add-to-cart-form" aria-label="Tambah {{ $product->name }} ke keranjang">
                                            @csrf
                                            <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                            <input type="hidden" name="price" value="{{ $final_price }}">
                                            <button type="submit"
                                                class="flex items-center justify-center w-full px-4 py-2.5 text-white bg-green-700 rounded-lg hover:bg-green-800 add-to-cart-btn transition-all">
                                                <span class="btn-text" aria-live="polite" aria-atomic="true">
                                                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                    Beli
                                                </span>
                                                <div class="btn-spinner" style="display: none;" aria-hidden="true">
                                                    <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button
                                        class="flex items-center justify-center w-full px-4 py-2.5 text-gray-700 bg-gray-200 rounded-lg cursor-not-allowed"
                                        title="Login untuk membeli" aria-disabled="true">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Login
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="p-8 bg-white col-span-full rounded-xl">
                        <div class="flex flex-col items-center text-center">
                            <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <h3 class="mb-2 text-xl font-semibold text-gray-800">Belum ada produk tersedia</h3>
                            <p class="text-gray-600">Coba periksa kembali nanti atau ubah filter pencarian.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if (method_exists($products, 'links') && $products->hasPages())
                <div class="p-4 mb-8 bg-white shadow rounded-xl" role="navigation" aria-label="Pagination Produk">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- Ultra-fast optimized JavaScript --}}
    <script>
        /**
         * Ultra-fast product listing with zero-lag optimization
         * Updated: {{ $currentDateTime }} by {{ $currentUser }}
         */

        // Performance optimizations
        const PERFORMANCE_CONFIG = {
            LOADING_DELAY: 100, // Minimal loading overlay delay
            TOAST_DURATION: 2500, // Shorter toast duration
            MODAL_DELAY: 500, // Faster modal appearance
            TRANSITION_SPEED: 150, // Ultra-fast transitions
            DEBOUNCE_DELAY: 50 // Fast debouncing
        };

        // Cache DOM elements for better performance
        const DOM_CACHE = {};

        function getCachedElement(id) {
            if (!DOM_CACHE[id]) {
                DOM_CACHE[id] = document.getElementById(id);
            }
            return DOM_CACHE[id];
        }

        // Ultra-fast DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeApp);
        } else {
            initializeApp();
        }

        function initializeApp() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // Preload critical functions
            const loadingOverlay = getCachedElement('loadingOverlay');
            const toastContainer = getCachedElement('toastContainer');
            const confirmationModal = getCachedElement('confirmationModal');

            function showLoadingOverlay() {
                if (loadingOverlay) {
                    loadingOverlay.classList.add('active');
                }
            }

            function hideLoadingOverlay() {
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('active');
                }
            }

            function showToast(type, title, message, duration = PERFORMANCE_CONFIG.TOAST_DURATION) {
                if (!toastContainer) return;

                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <div class="toast-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
                        </svg>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${title}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close" aria-label="Tutup">&times;</button>
                `;

                toastContainer.appendChild(toast);

                // Fast event binding
                toast.querySelector('.toast-close').onclick = () => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), PERFORMANCE_CONFIG.TRANSITION_SPEED);
                };

                // Ultra-fast animation
                requestAnimationFrame(() => {
                    toast.classList.add('show');
                });

                // Auto-remove
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), PERFORMANCE_CONFIG.TRANSITION_SPEED);
                    }
                }, duration);
            }

            function showConfirmationModal(title, message, onConfirm) {
                if (!confirmationModal) return;

                const titleEl = getCachedElement('confirmationTitle');
                const messageEl = getCachedElement('confirmationMessage');
                const okBtn = getCachedElement('confirmationOk');
                const cancelBtn = getCachedElement('confirmationCancel');

                if (!titleEl || !messageEl || !okBtn || !cancelBtn) return;

                titleEl.textContent = title;
                messageEl.textContent = message;

                confirmationModal.classList.add('active');

                // Ultra-fast event handlers
                const handleOk = () => {
                    confirmationModal.classList.remove('active');
                    cleanup();
                    if (onConfirm) onConfirm();
                };

                const handleCancel = () => {
                    confirmationModal.classList.remove('active');
                    cleanup();
                    // Ultra-fast navigation to products.index
                    window.location.href = "{{ route('products.index') }}";
                };

                const cleanup = () => {
                    okBtn.removeEventListener('click', handleOk);
                    cancelBtn.removeEventListener('click', handleCancel);
                    confirmationModal.removeEventListener('click', handleBackdrop);
                };

                const handleBackdrop = (e) => {
                    if (e.target === confirmationModal) {
                        handleCancel();
                    }
                };

                okBtn.addEventListener('click', handleOk);
                cancelBtn.addEventListener('click', handleCancel);
                confirmationModal.addEventListener('click', handleBackdrop);
            }

            // Add this to the initializeApp function in your index.blade.php file
            function updateCartCounter(newCount = null) {
                const counter = getCachedElement('cart-counter');
                if (!counter) return;

                if (newCount !== null) {
                    counter.textContent = newCount;

                    // Also update localStorage and session
                    try {
                        localStorage.setItem('cartItemCount', newCount);

                        // Use a fetch request to update the server-side session too
                        fetch('/update-cart-count', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                    'content') || '',
                            },
                            body: JSON.stringify({
                                count: newCount
                            })
                        }).catch(e => console.warn('Failed to update server cart count'));
                    } catch (e) {
                        console.warn('localStorage not available');
                    }
                } else {
                    // Force accurate count from server rather than relying on stale data
                    fetch('/get-cart-count', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            counter.textContent = data.count;
                            localStorage.setItem('cartItemCount', data.count);
                        })
                        .catch(() => {
                            // Fallback to session value
                            const count = {{ $cartItemCount }};
                            counter.textContent = count;
                        });
                }
            }

            // Add this to your document ready handler
            document.addEventListener('DOMContentLoaded', function() {
                // Force refresh cart count on page load
                updateCartCounter();
            });

            // Ultra-fast add to cart handler with batch processing
            function setupCartHandlers() {
                const forms = document.querySelectorAll('.add-to-cart-form');
                const formHandler = createFastFormHandler();

                forms.forEach(form => {
                    form.addEventListener('submit', formHandler);
                });
            }

            function createFastFormHandler() {
                let processing = false;

                return function(e) {
                    e.preventDefault();

                    if (processing) return;
                    processing = true;

                    const form = this;
                    const btn = form.querySelector('.add-to-cart-btn');
                    const btnText = btn?.querySelector('.btn-text');
                    const btnSpinner = btn?.querySelector('.btn-spinner');

                    if (!btn || !btnText || !btnSpinner) {
                        processing = false;
                        return;
                    }

                    // Ultra-fast UI updates
                    btn.classList.add('loading');
                    btnText.style.display = 'none';
                    btnSpinner.style.display = 'block';

                    // Minimal loading overlay
                    setTimeout(showLoadingOverlay, PERFORMANCE_CONFIG.LOADING_DELAY);

                    const formData = new FormData(form);
                    if (!formData.get('promo_code')) {
                        formData.set('promo_code', '{{ $promo_code ?? '' }}');
                    }

                    // Ultra-fast fetch with optimized headers
                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData,
                            keepalive: true
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || `Error ${response.status}`);
                                }).catch(() => {
                                    throw new Error(`HTTP ${response.status}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            hideLoadingOverlay();
                            processing = false;

                            if (data.success) {
                                // Ultra-fast DOM updates
                                const productCard = btn.closest('.product-card');
                                productCard.classList.add('in-cart');

                                // Create status badge efficiently
                                if (!productCard.querySelector('.product-status-badge')) {
                                    const statusBadge = document.createElement('div');
                                    statusBadge.className = 'product-status-badge';
                                    statusBadge.innerHTML = '✓ Di Keranjang';
                                    statusBadge.setAttribute('aria-label', 'Sudah di keranjang');
                                    productCard.appendChild(statusBadge);
                                }

                                // Replace button efficiently
                                btn.outerHTML = `
                                <button class="flex items-center justify-center w-full px-4 py-2.5 text-white bg-gray-500 rounded-lg cursor-not-allowed in-cart-btn"
                                        disabled title="Produk sudah ada di keranjang" aria-disabled="true">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Di Keranjang
                                </button>
                            `;

                                updateCartCounter(data.data?.cart_count);
                                showToast('success', 'Berhasil!', data.message ||
                                    'Produk ditambahkan ke keranjang');

                                // Ultra-fast modal with minimal delay
                                setTimeout(() => {
                                    showConfirmationModal(
                                        'Produk berhasil ditambahkan!',
                                        'Mau lihat keranjang sekarang?',
                                        () => {
                                            // Ultra-fast navigation to cart
                                            window.location.href = "{{ url('/cart') }}";
                                        }
                                    );
                                }, PERFORMANCE_CONFIG.MODAL_DELAY);
                            } else {
                                resetButton();
                                showToast('error', 'Gagal', data.message || 'Gagal menambah ke keranjang');
                            }
                        })
                        .catch(error => {
                            console.warn('Cart error:', error.message);
                            hideLoadingOverlay();
                            processing = false;
                            resetButton();
                            showToast('error', 'Gagal', 'Terjadi kesalahan, coba lagi');
                        });

                    function resetButton() {
                        btn.classList.remove('loading', 'success');
                        btnText.style.display = 'inline-flex';
                        btnText.innerHTML = `
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg> Beli`;
                        btnSpinner.style.display = 'none';
                    }
                };
            }

            // Ultra-fast promo form handler
            function setupPromoHandler() {
                const promoForm = getCachedElement('promoForm');
                if (!promoForm) return;

                let promoProcessing = false;

                promoForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (promoProcessing) return;
                    promoProcessing = true;

                    const formData = new FormData(this);
                    const promoCode = formData.get('promo_code');

                    if (!promoCode?.trim()) {
                        showToast('error', 'Error', 'Masukkan kode promo');
                        promoProcessing = false;
                        return;
                    }

                    setTimeout(showLoadingOverlay, PERFORMANCE_CONFIG.LOADING_DELAY);

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData,
                            keepalive: true
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            hideLoadingOverlay();
                            promoProcessing = false;

                            if (data.success) {
                                showToast('success', 'Berhasil!', data.message || 'Kode promo aktif');
                                // Ultra-fast reload with cache optimization
                                setTimeout(() => {
                                    window.location.reload(true);
                                }, 300);
                            } else {
                                showToast('error', 'Gagal', data.message || 'Kode promo tidak valid');
                            }
                        })
                        .catch(error => {
                            console.warn('Promo error:', error.message);
                            hideLoadingOverlay();
                            promoProcessing = false;
                            showToast('error', 'Gagal', 'Terjadi kesalahan sistem');
                        });
                });
            }

            // Performance optimizations
            function optimizePerformance() {
                // Enable hardware acceleration for animations
                const styleSheet = document.createElement('style');
                styleSheet.textContent = `
                    .product-card, .hero-layer, .loading-overlay, .confirmation-modal, .toast {
                        transform: translateZ(0);
                        backface-visibility: hidden;
                        perspective: 1000px;
                    }
                `;
                document.head.appendChild(styleSheet);

                // Optimize images loading
                const images = document.querySelectorAll('img[loading="lazy"]');
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.loading = 'eager';
                                imageObserver.unobserve(img);
                            }
                        });
                    }, {
                        rootMargin: '50px'
                    });

                    images.forEach(img => imageObserver.observe(img));
                }

                // Preload critical resources
                const preloadLink = document.createElement('link');
                preloadLink.rel = 'prefetch';
                preloadLink.href = "{{ route('products.index') }}";
                document.head.appendChild(preloadLink);

                const cartPreload = document.createElement('link');
                cartPreload.rel = 'prefetch';
                cartPreload.href = "{{ url('/cart') }}";
                document.head.appendChild(cartPreload);
            }

            // Initialize all features
            function init() {
                setupCartHandlers();
                setupPromoHandler();
                updateCartCounter();
                optimizePerformance();

                // Hide initial loading
                hideLoadingOverlay();

                console.log('Ultra-fast product listing initialized - {{ $currentDateTime }} by {{ $currentUser }}');
            }

            // Fast initialization
            if (document.readyState === 'complete') {
                init();
            } else {
                window.addEventListener('load', init);
            }
        }

        // Performance monitoring (optional)
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log(
                        `Page load time: ${Math.round(perfData.loadEventEnd - perfData.loadEventStart)}ms`
                    );
                }, 0);
            });
        }

        // Service Worker for caching (optional)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {
                    // Silent fail for service worker
                });
            });
        }
    </script>
@endsection
