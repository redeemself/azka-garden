@extends('layouts.app')

@section('title', $product->name . ' | Azka Garden')

@section('content')
    @php
        // Updated: 2025-08-02 10:31:09 by gerrymulyadi709
        // Fixed: Zoomed out images with proper height matching right content, mobile responsive

        // Enhanced product images handling for 2 image display
        $displayImages = collect([]);
        if (isset($product->product_images) && $product->product_images->count() > 0) {
            $displayImages = $product->product_images
                ->filter(
                    fn($img) => !empty($img->image_url) &&
                        preg_match('/\.(jpg|jpeg|png|webp)$/i', trim($img->image_url)),
                )
                ->unique('image_url')
                ->sortBy(fn($img) => $img->is_primary ? 0 : 1)
                ->values();
        }

        // Get primary product image - SAFER method
        $productImageUrl = asset('images/produk/placeholder.png'); // Default fallback
        if (isset($product->product_images) && $product->product_images->count() > 0) {
            $primaryImage = $product->product_images->where('is_primary', 1)->first();
            if ($primaryImage && !empty($primaryImage->image_url)) {
                $productImageUrl = asset($primaryImage->image_url);
            } else {
                $firstImage = $product->product_images->first();
                if ($firstImage && !empty($firstImage->image_url)) {
                    $productImageUrl = asset($firstImage->image_url);
                }
            }
        } elseif (!empty($product->image_url)) {
            $productImageUrl = asset($product->image_url);
        }

        // Enhanced: Cart integration with safe error handling
        $user = auth()->user();
        $cartProductIds = [];
        $cartItemCount = 0;

        if ($user) {
            try {
                $cartItems = \App\Models\Cart::where('user_id', $user->id)->get();
                $cartProductIds = $cartItems->pluck('product_id')->toArray();
                $cartItemCount = $cartItems->sum('quantity');
            } catch (\Exception $e) {
                $cartProductIds = [];
                $cartItemCount = 0;
                \Log::warning('Error fetching cart data', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        } else {
            $cartItems = session('cart_items', []);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    if (isset($item['product_id'])) {
                        $cartProductIds[] = $item['product_id'];
                    }
                    if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                        $cartItemCount += (int) $item['quantity'];
                    }
                }
            }
        }

        // Check if current product is in cart
        $isInCart = in_array($product->id, $cartProductIds);

        // Enhanced: Promo calculation with safe handling
        $promo_code = session('promo_code', '');
        $promo_type = session('promo_type', '');
        $promo_discount = (float) session('promo_discount', 0);
        $final_price = $product->price;
        $promo_active = false;
        $diskon = 0;
        $promo_label = '';

        if (auth()->check() && $promo_code && $promo_type && $promo_discount > 0) {
            if ($promo_type === 'percent') {
                $diskon = round($product->price * ($promo_discount / 100));
                $promo_label = $promo_discount . '%';
            } elseif ($promo_type === 'fixed') {
                $diskon = min($promo_discount, $product->price);
                $promo_label = 'Rp ' . number_format($diskon, 0, ',', '.');
            }
            $final_price = max(0, $product->price - $diskon);
            $promo_active = $final_price < $product->price;
        }

        // Store cart count in session
        session(['cart_count' => $cartItemCount]);

        // Get category name for display - SAFER method
        $categoryName = 'Uncategorized';
        if (isset($product->category) && $product->category) {
            $categoryName = $product->category->name;
        }
    @endphp

    <div class="flex flex-col min-h-screen">
        <section class="flex flex-col flex-grow bg-gradient-to-br from-green-50 via-white to-green-50">
            <div class="container p-6 mx-auto" style="padding-top:72px;">
                <!-- FIXED: Responsive layout - vertical stack on mobile, horizontal on desktop -->
                <div class="flex flex-col gap-8 lg:flex-row lg:items-stretch lg:h-auto">
                    <!-- FIXED: Product Images Container with matching height -->
                    <div class="w-full lg:w-1/2">
                        <div class="relative overflow-hidden bg-white shadow-2xl rounded-3xl h-full">
                            <!-- FIXED: Image container with proper height and zoom out -->
                            <div class="p-6 product-image-container flex items-center justify-center min-h-full">
                                @if ($displayImages->count() >= 2)
                                    <!-- Display 2 different images in grid with zoomed out view -->
                                    <div class="grid grid-cols-2 gap-4 w-full max-w-lg">
                                        @foreach ($displayImages->take(2) as $index => $img)
                                            <div class="overflow-hidden bg-white rounded-2xl shadow-md group aspect-square">
                                                <img src="{{ asset($img->image_url) }}"
                                                    alt="{{ $product->name }} {{ $index + 1 }}"
                                                    class="w-full h-full object-contain product-image transition-all duration-500 ease-in-out group-hover:scale-105 bg-gradient-to-br from-green-50 to-white p-2"
                                                    loading="lazy" decoding="async"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif ($displayImages->count() === 1)
                                    <!-- Display 1 image duplicated in 2 grid cells -->
                                    <div class="grid grid-cols-2 gap-4 w-full max-w-lg">
                                        <div class="overflow-hidden bg-white rounded-2xl shadow-md group aspect-square">
                                            <img src="{{ asset($displayImages->first()->image_url) }}"
                                                alt="{{ $product->name }} 1"
                                                class="w-full h-full object-contain product-image transition-all duration-500 ease-in-out group-hover:scale-105 bg-gradient-to-br from-green-50 to-white p-2"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                        </div>
                                        <div class="overflow-hidden bg-white rounded-2xl shadow-md group aspect-square">
                                            <img src="{{ $productImageUrl }}" alt="{{ $product->name }} 2"
                                                class="w-full h-full object-contain product-image transition-all duration-500 ease-in-out group-hover:scale-105 bg-gradient-to-br from-green-50 to-white p-2"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                        </div>
                                    </div>
                                @else
                                    <!-- Display placeholder or single product image in 2 grid cells -->
                                    <div class="grid grid-cols-2 gap-4 w-full max-w-lg">
                                        <div class="overflow-hidden bg-white rounded-2xl shadow-md group aspect-square">
                                            <img src="{{ $productImageUrl }}" alt="{{ $product->name }} 1"
                                                class="w-full h-full object-contain product-image transition-all duration-500 ease-in-out group-hover:scale-105 bg-gradient-to-br from-green-50 to-white p-2"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                        </div>
                                        <div class="overflow-hidden bg-white rounded-2xl shadow-md group aspect-square">
                                            <img src="{{ $productImageUrl }}" alt="{{ $product->name }} 2"
                                                class="w-full h-full object-contain product-image transition-all duration-500 ease-in-out group-hover:scale-105 bg-gradient-to-br from-green-50 to-white p-2"
                                                loading="lazy" decoding="async"
                                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';" />
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Enhanced Stock & Cart Badges -->
                            @if ($product->stock <= 0)
                                <div
                                    class="absolute px-4 py-2 text-sm font-bold text-white bg-red-500 shadow-lg rounded-xl top-4 right-4">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Stok Habis
                                </div>
                            @elseif($product->stock <= 5)
                                <div
                                    class="absolute px-4 py-2 text-sm font-bold text-white bg-yellow-500 shadow-lg rounded-xl top-4 right-4">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                    Sisa {{ $product->stock }}
                                </div>
                            @endif

                            @if ($isInCart)
                                <div class="absolute px-4 py-2 text-sm font-bold text-white bg-green-600 shadow-lg rounded-xl top-4 left-4"
                                    id="in-cart-badge">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Di Keranjang
                                </div>
                            @endif

                            @if ($promo_active)
                                <div class="discount-badge" aria-label="Diskon {{ $promo_label }}">DISKON
                                    {{ $promo_label }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- FIXED: Product Details with matching height -->
                    <div class="w-full lg:w-1/2 flex flex-col">
                        <!-- Product Title -->
                        <div class="mb-6">
                            <h1 class="mb-3 text-3xl font-bold leading-tight text-gray-800 lg:text-4xl">
                                {{ $product->name }}</h1>

                            <!-- Category Badge -->
                            @if (isset($product->category) && $product->category)
                                <div
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5l2 4h9l-4 7.5H8.5L7 7z"></path>
                                    </svg>
                                    {{ $categoryName }}
                                </div>
                            @endif
                        </div>

                        <!-- Enhanced Price Section -->
                        <div class="p-6 mb-6 bg-white border border-gray-200 shadow-lg rounded-2xl">
                            @if ($promo_active)
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <span class="text-xl text-gray-400 line-through">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span
                                        class="px-3 py-1 text-xs font-bold text-red-600 bg-red-100 border border-red-200 rounded-full">
                                        -{{ $promo_label }}
                                    </span>
                                </div>
                                <div class="text-3xl font-black text-green-600 lg:text-4xl">Rp
                                    {{ number_format($final_price, 0, ',', '.') }}</div>
                                <div class="mt-2 text-sm text-green-600">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Promo {{ $promo_code }} aktif
                                </div>
                            @else
                                <div class="text-3xl font-black text-gray-800 lg:text-4xl">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        <!-- Product Description -->
                        <div
                            class="p-6 mb-6 text-base leading-relaxed text-gray-700 bg-white border border-gray-200 shadow-lg rounded-2xl flex-grow">
                            <h3 class="mb-3 text-lg font-semibold text-gray-800">Deskripsi Produk</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- Enhanced Cart Section -->
                        @auth
                            @if ($product->stock > 0)
                                @if ($isInCart)
                                    <!-- Product already in cart -->
                                    <div class="p-6 border border-green-200 shadow-lg bg-gradient-to-r from-green-50 to-green-100 rounded-2xl"
                                        id="in-cart-section">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-full shadow-lg">
                                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-green-800">Produk di Keranjang</h3>
                                                <p class="text-sm text-green-600">Produk ini sudah ada dalam keranjang belanja
                                                    Anda</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <a href="{{ route('cart.index') }}"
                                                class="flex-1 px-6 py-4 font-bold text-center text-white transition-all duration-300 transform bg-green-600 shadow-lg rounded-xl hover:bg-green-700 hover:scale-105">
                                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                Lihat Keranjang
                                            </a>
                                            <a href="{{ route('products.index') }}"
                                                class="flex-1 px-6 py-4 font-bold text-center text-green-600 transition-all duration-300 transform bg-white border-2 border-green-600 shadow-lg rounded-xl hover:bg-green-50 hover:scale-105">
                                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Lanjut Belanja
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <!-- Add to cart form -->
                                    <div class="p-6 bg-white border border-gray-200 shadow-xl rounded-2xl">
                                        <form id="cart-form" action="{{ route('cart.add') }}" method="POST"
                                            class="flex flex-col gap-4 cart-form add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="price" value="{{ $final_price }}">
                                            <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">

                                            <!-- Quantity Section -->
                                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                                <label for="quantity"
                                                    class="text-lg font-semibold text-gray-700">Kuantitas</label>
                                                <div class="flex items-center gap-4">
                                                    <input type="number" name="quantity" id="quantity" value="1"
                                                        min="1" max="{{ min($product->stock, 100) }}"
                                                        class="w-20 px-3 py-2 text-center text-lg font-bold border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 bg-white"
                                                        placeholder="1">
                                                </div>
                                            </div>

                                            <!-- Stock Information -->
                                            <div class="flex items-center justify-between p-3 text-sm rounded-lg bg-blue-50">
                                                <span class="text-blue-700">Stok tersedia:</span>
                                                <span
                                                    class="font-bold text-blue-800 stock-display">{{ $product->stock }}</span>
                                            </div>

                                            <!-- Promo Code Input -->
                                            @if ($promo_code)
                                                <input type="hidden" name="promo_code" value="{{ $promo_code }}">
                                                <div
                                                    class="flex items-center px-4 py-3 border border-green-200 bg-green-50 rounded-xl">
                                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 7h.01M7 17h.01M17 7h.01M17 17h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                                    </svg>
                                                    <span class="font-medium text-green-700">Kode promo <span
                                                            class="font-mono font-bold">{{ $promo_code }}</span>
                                                        aktif</span>
                                                </div>
                                            @else
                                                <div class="relative">
                                                    <input type="text" name="promo_code" value="{{ old('promo_code') }}"
                                                        placeholder="Masukkan kode promo (opsional)"
                                                        class="block w-full px-4 py-3 transition border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
                                                    <div
                                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-medium text-gray-500">
                                                        Opsional
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Add to Cart Button -->
                                            <button type="submit"
                                                class="px-8 py-4 text-lg font-bold text-white transition-all duration-300 transform shadow-xl rounded-xl bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 disabled:opacity-50 disabled:cursor-not-allowed cart-btn add-to-cart-btn hover:scale-105 active:scale-95">
                                                <span class="flex items-center justify-center btn-text">
                                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    Tambah ke Keranjang
                                                </span>
                                                <div class="btn-spinner" style="display: none;">
                                                    <svg class="w-6 h-6 mr-3 text-white animate-spin"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Menambahkan...
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <!-- Out of stock -->
                                <div
                                    class="p-6 border border-red-200 shadow-lg bg-gradient-to-r from-red-50 to-red-100 rounded-2xl">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 bg-red-500 rounded-full shadow-lg">
                                            <svg class="text-white w-7 h-7" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-red-800">Stok Habis</h3>
                                            <p class="text-sm text-red-600">Produk ini sedang tidak tersedia</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Not logged in -->
                            <div
                                class="p-6 border border-gray-200 shadow-lg bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center w-12 h-12 bg-gray-500 rounded-full shadow-lg">
                                        <svg class="text-white w-7 h-7" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">Login untuk Membeli</h3>
                                        <p class="text-sm text-gray-600">Silakan login terlebih dahulu untuk menambahkan produk
                                            ke keranjang</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('login') }}"
                                        class="flex-1 px-6 py-4 font-bold text-center text-white transition-all duration-300 transform bg-green-600 shadow-lg rounded-xl hover:bg-green-700 hover:scale-105">
                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Login Sekarang
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="flex-1 px-6 py-4 font-bold text-center text-green-600 transition-all duration-300 transform bg-white border-2 border-green-600 shadow-lg rounded-xl hover:bg-green-50 hover:scale-105">
                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                            </path>
                                        </svg>
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        @endauth

                        <!-- Messages Container -->
                        <div id="alert-container" class="mt-6"></div>
                        @if (session('success'))
                            <div class="px-6 py-4 mt-6 font-semibold text-green-700 bg-green-100 border border-green-200 shadow-sm rounded-xl"
                                id="success-message">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="px-6 py-4 mt-6 font-semibold text-red-700 bg-red-100 border border-red-200 shadow-sm rounded-xl"
                                id="error-message">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Enhanced Cart Floating Button -->
    <a href="{{ route('cart.index') }}" class="cart-floating-button" aria-label="Lihat Keranjang">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span id="cart-counter" class="cart-counter" aria-live="polite" aria-atomic="true">{{ $cartItemCount }}</span>
    </a>

    <!-- Enhanced Confirmation Modal -->
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
                <button class="confirmation-btn confirmation-btn-secondary" id="confirmationCancel">Lanjut
                    Belanja</button>
                <button class="confirmation-btn confirmation-btn-primary" id="confirmationOk">Lihat Keranjang</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
@endsection

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* FIXED: Enhanced responsive layout with proper image zoom out and height matching */
        .product-image-container {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f9fafb, #ffffff);
        }

        .product-image {
            transition: transform 0.3s ease;
            will-change: transform;
            object-fit: contain;
            /* FIXED: Zoom out effect with contain */
            transform: translateZ(0);
            padding: 8px;
            /* FIXED: Add padding for zoom out effect */
        }

        .product-card:hover .product-image {
            transform: scale(1.05) translateZ(0);
        }

        /* FIXED: Responsive grid layout */
        @media (min-width: 1024px) {
            .product-image-container {
                min-height: 600px;
                /* FIXED: Match right content height on desktop */
            }

            .product-image-container .grid {
                max-width: 500px;
                /* FIXED: Limit max width for better proportions */
            }
        }

        /* FIXED: Mobile responsiveness - vertical stack */
        @media (max-width: 1023px) {
            .product-image-container {
                min-height: 300px;
                padding: 1rem;
            }

            .product-image-container .grid {
                max-width: 350px;
            }

            .product-image-container .grid>div {
                aspect-ratio: 1;
            }
        }

        /* FIXED: Simplified quantity input styles */
        #quantity {
            -moz-appearance: textfield;
            width: 80px;
            padding: 8px 12px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #10b981;
            border-radius: 8px;
            background: white;
            color: #1f2937;
        }

        #quantity::-webkit-outer-spin-button,
        #quantity::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #quantity:focus {
            outline: none;
            ring: 2px;
            ring-color: #10b981;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        #quantity:invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Discount badge */
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

        .cart-btn:disabled {
            transform: none;
        }

        .cart-btn:not(:disabled):hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Enhanced Cart floating button */
        .cart-floating-button {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 50;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 3px solid rgba(255, 255, 255, 0.9);
        }

        .cart-floating-button:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 15px 35px rgba(22, 163, 74, 0.4);
            color: white;
            text-decoration: none;
        }

        .cart-counter {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
            font-size: 12px;
            font-weight: 800;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
            border: 2px solid white;
        }

        /* Enhanced Confirmation Modal Styles */
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
            flex-wrap: wrap;
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
            flex: 1;
            min-width: 120px;
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

        /* Enhanced Toast notifications */
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

        /* Enhanced animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-form,
        #in-cart-section {
            animation: slideInUp 0.6s ease-out;
        }

        /* Mobile responsive enhancements */
        @media (max-width: 640px) {
            .cart-floating-button {
                bottom: 20px;
                left: 20px;
                width: 50px;
                height: 50px;
            }

            .cart-counter {
                width: 20px;
                height: 20px;
                font-size: 10px;
                top: -5px;
                right: -5px;
            }

            .confirmation-content {
                padding: 20px;
                margin: 16px;
            }

            .confirmation-buttons {
                flex-direction: column;
            }

            .confirmation-btn {
                min-width: auto;
                width: 100%;
            }

            .toast {
                min-width: 280px;
                margin-right: 10px;
            }

            #quantity {
                width: 70px;
                font-size: 16px;
            }

            .product-image-container {
                min-height: 250px;
                padding: 0.75rem;
            }

            .product-image-container .grid {
                max-width: 280px;
                gap: 0.75rem;
            }

            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            /* Stack vertically on mobile */
            .lg\\:flex-row {
                flex-direction: column;
            }

            .lg\\:w-1\\/2 {
                width: 100%;
            }

            /* Better button layout on mobile */
            .flex-col.sm\\:flex-row {
                flex-direction: column;
            }

            .flex-col.sm\\:flex-row>* {
                width: 100%;
            }
        }

        /* Tablet responsive */
        @media (min-width: 641px) and (max-width: 1023px) {
            .product-image-container {
                min-height: 350px;
            }

            .product-image-container .grid {
                max-width: 400px;
            }
        }
    </style>
@endpush

@section('scripts')
    <script>
        /**
         * FIXED Product Show Page - Responsive layout with zoomed out images
         * Updated: 2025-08-02 10:31:09 by gerrymulyadi709
         * Fixed: Image zoom out, height matching, mobile vertical stack
         */

        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const maxStock = {{ $product->stock }};

            // Quantity input validation
            const quantityInput = document.getElementById('quantity');

            function validateQuantityInput() {
                if (!quantityInput) return;

                const value = parseInt(quantityInput.value) || 1;
                const validValue = Math.max(1, Math.min(maxStock, Math.min(100, value)));

                if (value !== validValue) {
                    quantityInput.value = validValue;
                }

                // Visual feedback for invalid values
                if (value > maxStock) {
                    quantityInput.style.borderColor = '#ef4444';
                    showToast('error', 'Peringatan', `Maksimal quantity adalah ${maxStock}`);
                } else if (value < 1) {
                    quantityInput.style.borderColor = '#ef4444';
                    showToast('error', 'Peringatan', 'Minimal quantity adalah 1');
                } else {
                    quantityInput.style.borderColor = '#10b981';
                }
            }

            // Input validation events
            if (quantityInput) {
                quantityInput.addEventListener('input', validateQuantityInput);
                quantityInput.addEventListener('change', validateQuantityInput);
                quantityInput.addEventListener('blur', validateQuantityInput);

                // Prevent invalid input
                quantityInput.addEventListener('keydown', function(e) {
                    // Allow: backspace, delete, tab, escape, enter
                    if ([46, 8, 9, 27, 13].includes(e.keyCode) ||
                        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                        (e.keyCode === 65 && e.ctrlKey === true) ||
                        (e.keyCode === 67 && e.ctrlKey === true) ||
                        (e.keyCode === 86 && e.ctrlKey === true) ||
                        (e.keyCode === 88 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode >
                            105)) {
                        e.preventDefault();
                    }
                });
            }

            // Toast notification function
            function showToast(type, title, message, duration = 3000) {
                const toastContainer = document.getElementById('toastContainer');
                if (!toastContainer) return;

                const toast = document.createElement('div');
                toast.className = `toast toast-${type} show`;
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

                toast.querySelector('.toast-close').onclick = () => {
                    toast.remove();
                };

                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, duration);
            }

            // Confirmation modal function
            function showConfirmationModal(title, message, onConfirm) {
                const confirmationModal = document.getElementById('confirmationModal');
                if (!confirmationModal) return;

                const titleEl = document.getElementById('confirmationTitle');
                const messageEl = document.getElementById('confirmationMessage');
                const okBtn = document.getElementById('confirmationOk');
                const cancelBtn = document.getElementById('confirmationCancel');

                if (!titleEl || !messageEl || !okBtn || !cancelBtn) return;

                titleEl.textContent = title;
                messageEl.textContent = message;

                // Show modal
                confirmationModal.style.opacity = '1';
                confirmationModal.style.visibility = 'visible';
                confirmationModal.classList.add('active');

                const handleOk = () => {
                    confirmationModal.style.opacity = '0';
                    confirmationModal.style.visibility = 'hidden';
                    confirmationModal.classList.remove('active');
                    cleanup();
                    if (onConfirm) onConfirm();
                };

                const handleCancel = () => {
                    confirmationModal.style.opacity = '0';
                    confirmationModal.style.visibility = 'hidden';
                    confirmationModal.classList.remove('active');
                    cleanup();
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

            // Update cart counter
            function updateCartCounter(newCount = null) {
                const counter = document.getElementById('cart-counter');
                if (!counter) return;

                if (newCount !== null) {
                    counter.textContent = newCount;
                    counter.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        counter.style.transform = 'scale(1)';
                    }, 200);
                }
            }

            // Setup add to cart forms
            function setupAddToCartForms() {
                const forms = document.querySelectorAll('.add-to-cart-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const button = this.querySelector('.add-to-cart-btn');
                        const btnText = button.querySelector('.btn-text');
                        const btnSpinner = button.querySelector('.btn-spinner');
                        const quantityInput = this.querySelector('#quantity');

                        // Validate quantity
                        const quantity = parseInt(quantityInput.value) || 1;
                        if (quantity < 1) {
                            showToast('error', 'Gagal', 'Jumlah produk minimal 1');
                            quantityInput.focus();
                            return;
                        }

                        if (quantity > maxStock) {
                            showToast('error', 'Gagal', `Stok hanya tersedia ${maxStock} item`);
                            quantityInput.focus();
                            return;
                        }

                        // Show loading state
                        button.disabled = true;
                        button.classList.add('loading');
                        if (btnText) btnText.style.visibility = 'hidden';
                        if (btnSpinner) btnSpinner.style.display = 'inline-block';

                        const formData = new FormData(this);

                        // AJAX request
                        fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    updateCartCounter(data.cart_count);
                                    showToast('success', 'Berhasil!', data.message ||
                                        'Produk berhasil ditambahkan ke keranjang!');

                                    // Update button state
                                    button.classList.remove('loading');
                                    button.classList.add('in-cart-btn');
                                    button.disabled = true;
                                    if (btnText) {
                                        btnText.innerHTML =
                                            `<svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Di Keranjang`;
                                        btnText.style.visibility = 'visible';
                                    }
                                    if (btnSpinner) btnSpinner.style.display = 'none';

                                    // Update visual state
                                    const productContainer = this.closest(
                                        '.w-full.lg\\:w-1\\/2').previousElementSibling;
                                    let inCartBadge = document.getElementById('in-cart-badge');
                                    if (productContainer && !inCartBadge) {
                                        const badge = document.createElement('div');
                                        badge.id = 'in-cart-badge';
                                        badge.className =
                                            'absolute px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-xl shadow-lg top-4 left-4';
                                        badge.innerHTML = `
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Di Keranjang
                                        `;
                                        productContainer.querySelector('.relative').appendChild(
                                            badge);
                                    }

                                    // Show confirmation modal
                                    setTimeout(() => {
                                        showConfirmationModal(
                                            'Produk berhasil ditambahkan!',
                                            'Mau lanjut belanja atau lihat keranjang?',
                                            () => {
                                                window.location.href =
                                                    "{{ route('cart.index') }}";
                                            }
                                        );
                                    }, 100);
                                } else {
                                    throw new Error(data.message ||
                                        'Gagal menambahkan produk ke keranjang');
                                }
                            })
                            .catch(error => {
                                // Reset button state
                                button.disabled = false;
                                button.classList.remove('loading');
                                if (btnText) btnText.style.visibility = 'visible';
                                if (btnSpinner) btnSpinner.style.display = 'none';

                                showToast('error', 'Gagal', error.message ||
                                    'Terjadi kesalahan, silakan coba lagi');
                            });
                    });
                });
            }

            // Initialize add to cart forms
            setupAddToCartForms();

            // Handle responsive image container height matching
            function adjustImageContainerHeight() {
                const imageContainer = document.querySelector('.product-image-container');
                const contentContainer = document.querySelector('.lg\\:w-1\\/2 .flex.flex-col');

                if (imageContainer && contentContainer && window.innerWidth >= 1024) {
                    // On desktop, match the height of the content container
                    const contentHeight = contentContainer.offsetHeight;
                    imageContainer.style.minHeight = `${Math.max(400, contentHeight)}px`;
                } else if (imageContainer) {
                    // On mobile, use responsive heights
                    imageContainer.style.minHeight = window.innerWidth <= 640 ? '250px' : '300px';
                }
            }

            // Initialize height adjustment
            adjustImageContainerHeight();

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(adjustImageContainerHeight, 150);
            });

            // Handle orientation change on mobile
            window.addEventListener('orientationchange', function() {
                setTimeout(adjustImageContainerHeight, 300);
            });

            // Improve image loading and error handling
            const productImages = document.querySelectorAll('.product-image');
            productImages.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });

                img.addEventListener('error', function() {
                    this.style.opacity = '0.7';
                    this.style.filter = 'grayscale(50%)';
                });

                // Set initial opacity for smooth loading
                img.style.opacity = '0.8';
                img.style.transition = 'opacity 0.3s ease';
            });

            // Enhanced mobile touch interactions
            if ('ontouchstart' in window) {
                const imageGrids = document.querySelectorAll('.product-image-container .grid > div');
                imageGrids.forEach(gridItem => {
                    gridItem.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });

                    gridItem.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                    });

                    gridItem.addEventListener('touchcancel', function() {
                        this.style.transform = 'scale(1)';
                    });
                });
            }

            // Optimize scroll performance
            let ticking = false;

            function updateScrollElements() {
                // Update any scroll-dependent elements here if needed
                ticking = false;
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(updateScrollElements);
                    ticking = true;
                }
            });

            // Progressive enhancement for modern browsers
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                                observer.unobserve(img);
                            }
                        }
                    });
                });

                // Observe images for lazy loading if needed
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }

            // Accessibility improvements
            const focusableElements = document.querySelectorAll(
                'button, a, input, [tabindex]:not([tabindex="-1"])');
            focusableElements.forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.outline = '2px solid #10b981';
                    this.style.outlineOffset = '2px';
                });

                element.addEventListener('blur', function() {
                    this.style.outline = 'none';
                });
            });

            // Add keyboard navigation for modal
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('confirmationModal');
                if (modal && modal.classList.contains('active')) {
                    if (e.key === 'Escape') {
                        document.getElementById('confirmationCancel').click();
                    } else if (e.key === 'Enter') {
                        document.getElementById('confirmationOk').click();
                    }
                }
            });

            // Performance optimization: Debounce input validation
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Apply debounced validation to quantity input
            if (quantityInput) {
                const debouncedValidation = debounce(validateQuantityInput, 300);
                quantityInput.addEventListener('input', debouncedValidation);
            }

            // Initialize page with smooth animations
            document.body.style.opacity = '0';
            window.addEventListener('load', function() {
                document.body.style.transition = 'opacity 0.3s ease';
                document.body.style.opacity = '1';
            });

            // Log successful initialization
            console.log('🎉 FIXED Product Show Page - Responsive with zoomed out images');
            console.log('✅ Features: Image zoom out, height matching, mobile vertical stack, enhanced UX');
            console.log('🚀 Updated by: gerrymulyadi709 at 2025-08-02 10:35:14 UTC');
            console.log('📱 Mobile: Vertical stack layout, optimized touch interactions');
            console.log('🖥️  Desktop: Height matching between image and content containers');
        });
    </script>
@endsection
