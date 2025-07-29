@extends('layouts.app')

@section('title', 'Produk Kami')

@section('content')
@php
    $user = auth()->user();
    $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
    $promo_code = session('promo_code');
    $promo_type = session('promo_type');
    $promo_discount = session('promo_discount');
    $promo_discount_percent = ($promo_type === 'percent') ? 10.0 : null;

    // Hitung jumlah item di keranjang dari database jika login, dari session jika guest
    if(auth()->check()) {
        $cartItemCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
    } else {
        $cartItems = session('cart_items') ?? session('cartItems') ?? collect();
        if (!($cartItems instanceof \Illuminate\Support\Collection)) {
            $cartItems = collect($cartItems);
        }
        $cartItemCount = $cartItems->sum('quantity');
    }

    // Prepare images for preloading to reduce lag
    $heroImageUrls = [
        asset('images/hero-1.jpg'),
        asset('images/hero-2.jpg'),
        asset('images/hero-3.jpg'),
        asset('images/hero-4.jpg'),
        asset('images/hero-5.jpg'),
        asset('images/hero-6.jpg'),
        asset('images/hero-7.jpg'),
    ];

    $currentDateTime = '2025-07-28 09:48:35';
    $currentUser = 'redeemself';
@endphp

{{-- Preload hero images in head to reduce lag --}}
@push('head')
@foreach($heroImageUrls as $imageUrl)
<link rel="preload" href="{{ $imageUrl }}" as="image">
@endforeach
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<style>
    /* Base styles */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        will-change: transform, box-shadow;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }
    .product-image-container {
        overflow: hidden;
        position: relative;
        background-color: #f9fafb;
    }
    .product-image {
        transition: transform 0.5s ease;
        will-change: transform;
        object-fit: contain;
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
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
        width: 50px;
        height: 50px;
        border: 3px solid rgba(22, 101, 52, 0.1);
        border-radius: 50%;
        border-top: 3px solid #166534;
        animation: spin 0.8s linear infinite;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Toast notifications */
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
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 16px;
        min-width: 300px;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 14px;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
    }
    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }
    .toast-success {
        border-left: 4px solid #15803d;
    }
    .toast-error {
        border-left: 4px solid #b91c1c;
    }
    .toast-content {
        flex: 1;
    }
    .toast-title {
        font-weight: 600;
        color: #111827;
        font-size: 1rem;
    }
    .toast-message {
        color: #4b5563;
        font-size: 0.875rem;
        margin-top: 4px;
    }
    .toast-close {
        color: #9ca3af;
        font-size: 1.25rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .toast-close:hover {
        color: #4b5563;
        background-color: #f3f4f6;
    }
    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }
    .toast-success .toast-icon {
        color: #15803d;
    }
    .toast-error .toast-icon {
        color: #b91c1c;
    }

    /* Animations */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    /* Hero carousel */
    .hero-layer {
        transition: opacity 1.2s ease-out;
        will-change: opacity;
        background-size: cover;
        background-position: center;
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
        transition: all 0.3s ease;
    }
    .hero-indicator.active {
        background-color: white;
        width: 4rem;
    }

    /* Modern discount badge */
    .discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #15803d;
        color: white;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 20;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .discount-badge:before {
        content: '';
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        display: block;
    }

    /* Banner section fixes for both mobile and desktop */
    .banner-section {
        width: 100%;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
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

    /* Desktop specific banner styles */
    @media (min-width: 769px) {
        .banner-image-container {
            height: 240px;
        }

        .banner-image {
            height: 100%;
            transform: scale(0.85); /* Zoom out effect */
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

    /* Mobile specific banner styles */
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
    }

    /* Search and promo section */
    .search-filter-container {
        width: 100%;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 16px;
        margin-bottom: 2rem;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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

    /* Product grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
    }

    /* Cart floating button - di pojok kiri bawah dengan ikon daun */
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
        transition: all 0.3s ease;
    }

    .cart-floating-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        animation: pulse 0.5s;
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

    /* Add to cart button success/loading states */
    .add-to-cart-btn {
        position: relative;
        overflow: hidden;
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
        width: 20px;
        height: 20px;
    }

    .add-to-cart-btn.success {
        background-color: #16a34a !important;
    }
</style>

<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="toast-container" id="toastContainer"></div>

{{-- Cart Button dengan ikon daun di pojok kiri bawah --}}
<a href="{{ route('user.cart.index') }}" class="cart-floating-button">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
    </svg>
    <span id="cart-counter" class="cart-counter">{{ $cartItemCount }}</span>
</a>

{{-- Peringatan jika user login belum punya alamat --}}
@auth
    @if(!$hasAddress)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
            style="backdrop-filter: blur(3px);"
        >
            <div class="relative flex flex-col items-center w-full max-w-xs p-6 bg-white shadow-2xl rounded-xl fade-in">
                <button
                    @click="show = false"
                    class="absolute p-1 text-gray-400 rounded-full hover:bg-gray-100 top-3 right-3 hover:text-gray-600"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800">Alamat Belum Lengkap</h3>
                    <div class="mb-5 text-sm text-center text-gray-600">Anda belum mengisi alamat rumah! Silakan lengkapi alamat Anda di halaman profil agar bisa melakukan pembelian.</div>
                    <a href="{{ route('user.profile.index') }}"
                       class="w-full px-4 py-2 mb-2 font-medium text-center text-white transition bg-green-600 rounded-md shadow-sm hover:bg-green-700">
                        Isi Alamat Sekarang
                    </a>
                    <button @click="show = false" class="w-full px-4 py-2 font-medium text-center text-gray-800 transition bg-gray-100 border border-gray-200 rounded-md hover:bg-gray-200">Tutup</button>
                </div>
            </div>
        </div>
    @endif
@endauth

<section
    x-data="{
        heroImages: {{ json_encode($heroImageUrls) }},
        currentBg: 0,
        nextBg: 1,
        frontLayer: true,
        interval: null,
        transitionDuration: 1200,
        transitioning: false,

        init() {
            // Preload first two images
            const img1 = new Image();
            const img2 = new Image();
            img1.src = this.heroImages[0];
            img2.src = this.heroImages[1];

            this.startInterval();
        },
        startInterval() {
            this.stopInterval();
            this.interval = setInterval(() => { this.nextBackground() }, 6000);
        },
        stopInterval() {
            if (this.interval) clearInterval(this.interval);
        },
        nextBackground() {
            let next = (this.currentBg + 1) % this.heroImages.length;
            this.performTransition(next);

            // Preload next image
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
        },
    }"
    x-init="init"
    class="relative min-h-screen overflow-hidden"
>
    {{-- Dynamic Background Layers --}}
    <div
        class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1200ms] ease-in-out z-10 hero-layer"
        :class="frontLayer ? 'opacity-100' : 'opacity-0'"
        :style="`background-image: url('${heroImages[currentBg]}');`"
    ></div>
    <div
        class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1200ms] ease-in-out z-0 hero-layer"
        :class="!frontLayer ? 'opacity-100' : 'opacity-0'"
        :style="`background-image: url('${heroImages[nextBg]}');`"
    ></div>
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-br from-black/60 via-black/50 to-green-900/40"></div>

    {{-- Hero indicators --}}
    <div class="hero-indicators">
        <template x-for="(img, idx) in heroImages" :key="idx">
            <button
                type="button"
                class="hero-indicator"
                :class="idx === currentBg ? 'active' : ''"
                @click="changeBackground(idx)"
                aria-label="Change image"
            ></button>
        </template>
    </div>

    <div class="relative z-30 px-4 mx-auto max-w-7xl">
        {{-- Banner Section with Fixed Mobile & Desktop Display --}}
        @if(isset($banners) && count($banners))
            @php $banner = $banners[0]; @endphp
            <div class="banner-section">
                <div class="banner-image-container">
                    <img src="{{ asset($banner->image) }}"
                         alt="{{ $banner->title ?? 'Promo Tanaman Hias Juli' }}"
                         class="banner-image"
                         loading="lazy" />
                    <div class="banner-overlay">
                        <div class="banner-content">
                            <h2 class="banner-title">{{ $banner->title ?? 'Promo Tanaman Hias Juli' }}</h2>
                        </div>
                        <div class="promo-badge">10%</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Search, Category, Promo Bar --}}
        <div class="search-filter-container">
            <div class="grid gap-4 md:grid-cols-2">
                <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari produk atau tanaman hias..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 search-input" />
                        <select name="category" onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 category-select">
                            <option value="">Semua Kategori</option>
                            @isset($categories)
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full px-6 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all search-button">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Produk
                        </div>
                    </button>
                </form>

                <form method="POST" action="{{ route('promo.activate') }}" id="promoForm" class="flex flex-col">
                    @csrf
                    <div class="flex-1 p-4 border border-green-200 rounded-lg bg-green-50">
                        <p class="mb-2 font-semibold text-green-800">Punya kode promo? Aktifkan disini:</p>
                        <div class="flex flex-wrap gap-2">
                            <input type="text" name="promo_code" value="{{ old('promo_code', session('promo_code')) }}"
                                   placeholder="Masukkan kode promo"
                                   class="flex-1 px-4 py-2 min-w-[150px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 promo-input" />
                            <button type="submit"
                                    class="px-5 py-2 text-white transition-all bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 promo-button">
                                Aktifkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Promo info jika aktif --}}
        @if($promo_code && $promo_type !== null && $promo_discount !== null)
            <div class="promo-active-notification">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-green-800">Promo aktif: <span class="font-mono">{{ $promo_code }}</span></p>
                        <p class="text-sm text-green-700">
                            Diskon {{ $promo_type === 'percent' ? $promo_discount_percent . '%' : 'Rp ' . number_format($promo_discount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('promo.deactivate') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-white transition-all bg-gray-800 rounded-lg hover:bg-gray-900">Nonaktifkan</button>
                </form>
            </div>
        @endif

        {{-- Produk Grid & Jumlah Produk --}}
        <div class="flex items-center justify-between my-6">
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Produk Kami</h1>
            <div class="product-count-badge">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                {{ $products->count() }} Produk
            </div>
        </div>

        <div class="mb-12 product-grid">
            @forelse($products as $product)
                @php
                    $final_price = $product->price;
                    $promo_label = '';
                    $promo_active = false;
                    $diskon = 0;

                    if(auth()->check() && $promo_code && $promo_type !== null && $promo_discount !== null) {
                        if($promo_type === 'percent') {
                            $diskon = round($product->price * (10 / 100));
                            $promo_label = '10%';
                        } elseif($promo_type === 'fixed') {
                            $diskon = min($promo_discount, $product->price);
                            $promo_label = 'Rp ' . number_format($diskon, 0, ',', '.');
                        }
                        $final_price = max(0, $product->price - $diskon);
                        $promo_active = ($final_price < $product->price);
                    }

                    $displayImages = collect($product->images ?? [])
                        ->filter(function($img) {
                            return preg_match('/\.(jpg|jpeg|png)$/i', trim($img->image_url ?? ''));
                        })
                        ->unique('image_url')
                        ->sortBy(function($img) {
                            if (preg_match('/\.jpg$/i', $img->image_url)) return 0;
                            if (preg_match('/\.png$/i', $img->image_url)) return 1;
                            return 2;
                        })
                        ->values();
                @endphp

                <div class="overflow-hidden product-card rounded-xl">
                    @if($promo_active)
                        <div class="discount-badge">DISKON {{ $promo_label }}</div>
                    @endif

                    <div class="p-2 product-image-container">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($displayImages->take(2) as $index => $img)
                                <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                    <img
                                        src="{{ asset($img->image_url) }}"
                                        alt="{{ $product->name }} {{ $index + 1 }}"
                                        class="w-full h-full product-image"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';"
                                    >
                                </div>
                            @endforeach
                            @if($displayImages->count() < 2)
                                @if($displayImages->count() == 1)
                                    <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                        <img
                                            src="{{ asset($displayImages->first()->image_url) }}"
                                            alt="{{ $product->name }} 1"
                                            class="w-full h-full product-image"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';"
                                        >
                                    </div>
                                    <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                        <img
                                            src="{{ asset($product->image_url ?? 'images/produk/placeholder.png') }}"
                                            alt="{{ $product->name }} 2"
                                            class="w-full h-full product-image"
                                            loading="lazy"
                                        >
                                    </div>
                                @else
                                    <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                        <img
                                            src="{{ asset($product->image_url ?? 'images/produk/placeholder.png') }}"
                                            alt="{{ $product->name }} 1"
                                            class="w-full h-full product-image"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="overflow-hidden bg-white rounded-lg aspect-square">
                                        <img
                                            src="{{ asset('images/produk/placeholder.png') }}"
                                            alt="{{ $product->name }} 2"
                                            class="w-full h-full product-image"
                                            loading="lazy"
                                        >
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="p-5">
                        <h2 class="mb-2 text-xl font-semibold text-gray-800 line-clamp-2">{{ $product->name }}</h2>
                        <p class="mb-4 text-sm text-gray-600 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>

                        <div class="mb-4">
                            @if($promo_active)
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 ml-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        -{{ $promo_label }}
                                    </span>
                                </div>
                                <div class="mt-1 text-xl font-bold text-green-700">
                                    Rp {{ number_format($final_price, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="text-xl font-bold text-gray-800">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('user.products.show', $product->id) }}"
                               class="flex items-center justify-center px-4 py-2.5 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                            @auth
                                <form method="POST" action="{{ route('user.cart.add') }}" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="promo_code" value="{{ session('promo_code') ?? '' }}">
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-2.5 text-white bg-green-700 rounded-lg hover:bg-green-800 add-to-cart-btn transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Beli
                                    </button>
                                </form>
                            @else
                                <button
                                    class="flex items-center justify-center w-full px-4 py-2.5 text-gray-700 bg-gray-200 rounded-lg cursor-not-allowed"
                                    title="Login untuk membeli">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Login
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 bg-white col-span-full rounded-xl">
                    <div class="flex flex-col items-center text-center">
                        <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mb-2 text-xl font-semibold text-gray-800">Belum ada produk tersedia</h3>
                        <p class="text-gray-600">Coba periksa kembali nanti atau ubah filter pencarian.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($products, 'links') && $products->hasPages())
            <div class="p-4 mb-8 bg-white shadow rounded-xl">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Show loading overlay
    function showLoadingOverlay() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.classList.add('active');
        }
    }

    // Hide loading overlay
    function hideLoadingOverlay() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.classList.remove('active');
        }
    }

    // Show toast notification
    function showToast(type, title, message, duration = 3000) {
        const toastContainer = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        toast.innerHTML = `
            <div class="toast-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'}
                </svg>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">&times;</button>
        `;

        toastContainer.appendChild(toast);

        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });

        // Ensure proper animation timing
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
        });

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    function updateCartCounter() {
        const counter = document.getElementById('cart-counter');
        if (counter) {
            const count = {{ $cartItemCount }};
            counter.textContent = count;
            localStorage.setItem('cartItemCount', count);
        }
    }

    // Add loading indicator for all cart forms
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update cart counter badge
                    const counter = document.getElementById('cart-counter');
                    if (counter) counter.textContent = data.data.cart_count;

                    // Redirect ke halaman keranjang
                    window.location.href = "{{ route('user.cart.index') }}";
                } else {
                    alert(data.message || "Gagal menambah produk ke keranjang");
                }
            })
            .catch(() => {
                // Fallback: submit form biasa jika AJAX gagal
                form.submit();
            });
        });
    });

    // Handle promo form with AJAX
    const promoForm = document.getElementById('promoForm');
    if (promoForm) {
        promoForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const promoCode = formData.get('promo_code');

            if (!promoCode) {
                showToast('error', 'Error', 'Masukkan kode promo terlebih dahulu');
                return;
            }

            showLoadingOverlay();

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json().catch(() => {
                if (response.ok) {
                    return { success: true };
                }
                throw new Error('Network response was not ok');
            }))
            .then(data => {
                if (data.success) {
                    showToast('success', 'Berhasil', 'Kode promo berhasil diterapkan');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Gagal menerapkan kode promo');
                }
            })
            .catch(error => {
                console.error('Error applying promo code:', error);
                hideLoadingOverlay();
                showToast('error', 'Gagal', 'Kode promo tidak valid atau tidak dapat diterapkan');
            });
        });
    }

    // Initialize cart counter with actual value
    updateCartCounter();

    // Automatically hide loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        hideLoadingOverlay();
    });
});
</script>
@endsection
