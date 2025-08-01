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

        // Get search query from request
        $searchQuery = request('search', '');

        // Get category filter from request (single category selection)
        $categoryFilter = request('category', 0);

        // Load all categories for filter menu
        $categories = \App\Models\Category::where('status', 1)->get();

        // Get products based on category filter
        $query = \App\Models\Product::where('status', 1);

        // Apply category filter if selected
        if ($categoryFilter > 0) {
            $query->where('category_id', $categoryFilter);
        }

        // Apply search query if provided
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        // Get products with pagination
        $products = $query->with(['category', 'images'])->paginate(15);

        // Get the count of filtered products
        $filteredProductCount = $products->total();

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
        $currentDateTime = '2025-08-01 06:31:24';
        $currentUser = 'DenuJanuari';

        // Detect if viewing on mobile
        $userAgent = request()->header('User-Agent');
        $isMobile =
            preg_match(
                '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
                $userAgent,
            ) ||
            preg_match(
                '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
                substr($userAgent, 0, 4),
            );
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

            /* Category pills styling */
            .category-pill {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                border-radius: 9999px;
                font-weight: 600;
                transition: all 0.2s;
                cursor: pointer;
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
                background-color: rgba(255, 255, 255, 0.8);
                border: 2px solid transparent;
            }

            .category-pill:hover {
                background-color: rgba(255, 255, 255, 0.95);
                transform: translateY(-1px);
            }

            .category-pill.active {
                border-color: #16a34a;
                background-color: rgba(22, 163, 74, 0.1);
                color: #15803d;
            }

            /* Mobile quantity selector styles */
            .quantity-selector {
                display: flex;
                align-items: center;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #e5e7eb;
                width: 100%;
                margin-bottom: 0.75rem;
            }

            .quantity-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background-color: #f9fafb;
                border: none;
                font-size: 1.25rem;
                font-weight: bold;
                color: #4b5563;
                touch-action: manipulation;
            }

            .quantity-input {
                flex: 1;
                border: none;
                text-align: center;
                font-size: 1rem;
                padding: 0.5rem;
                -moz-appearance: textfield;
                background-color: white;
            }

            .quantity-input::-webkit-outer-spin-button,
            .quantity-input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Mobile add to cart button */
            .mobile-add-to-cart {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                padding: 0.75rem;
                background-color: #16a34a;
                color: white;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.2s;
                border: none;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .mobile-add-to-cart:active {
                transform: scale(0.98);
                background-color: #15803d;
            }

            /* Mobile product card styles */
            .mobile-product-card {
                background-color: white;
                border-radius: 1rem;
                overflow: hidden;
                margin-bottom: 1rem;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            .mobile-product-image {
                width: 100%;
                height: 180px;
                object-fit: cover;
                background-color: #f9fafb;
            }

            .mobile-product-details {
                padding: 1rem;
            }

            .mobile-product-title {
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
                line-height: 1.3;
            }

            .mobile-product-category {
                font-size: 0.75rem;
                color: #16a34a;
                margin-bottom: 0.5rem;
                display: inline-block;
                padding: 0.125rem 0.5rem;
                background-color: #dcfce7;
                border-radius: 0.25rem;
                font-weight: 500;
            }

            .mobile-product-price {
                font-size: 1.25rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 0.75rem;
            }

            .mobile-product-actions {
                margin-top: 1rem;
            }

            /* Mobile-optimized search and filter styles */
            .mobile-search-container {
                margin: -30px 0 20px 0;
                padding: 15px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                position: relative;
                z-index: 35;
            }

            .mobile-search-input-wrapper {
                position: relative;
                margin-bottom: 16px;
            }

            .mobile-search-input {
                width: 100%;
                padding: 14px 14px 14px 48px;
                font-size: 16px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background-color: #f9fafb;
                transition: all 0.2s;
                -webkit-appearance: none;
                appearance: none;
            }

            .mobile-search-input:focus {
                outline: none;
                background-color: white;
                border-color: #16a34a;
                box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
            }

            .mobile-search-icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #16a34a;
                pointer-events: none;
            }

            .mobile-filter-label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 12px;
                display: block;
                font-size: 16px;
            }

            .mobile-category-filters {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 8px;
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* IE/Edge */
                margin: 0 -15px;
                padding-left: 15px;
                padding-right: 15px;
                scroll-snap-type: x mandatory;
            }

            .mobile-category-filters::-webkit-scrollbar {
                display: none;
                /* Chrome/Safari */
            }

            .mobile-category-pill {
                flex: 0 0 auto;
                display: flex;
                align-items: center;
                padding: 10px 18px;
                background-color: #f3f4f6;
                color: #4b5563;
                font-weight: 600;
                border-radius: 30px;
                margin-right: 10px;
                white-space: nowrap;
                transition: all 0.2s;
                border: 2px solid transparent;
                scroll-snap-align: start;
            }

            .mobile-category-pill.active {
                background-color: #dcfce7;
                color: #15803d;
                border-color: #16a34a;
            }

            .mobile-promo-container {
                margin-top: 16px;
                padding-top: 16px;
                border-top: 1px solid #e5e7eb;
            }

            .mobile-promo-label {
                font-weight: 600;
                color: #16a34a;
                margin-bottom: 10px;
                display: block;
                font-size: 15px;
            }

            .mobile-promo-form {
                display: flex;
                gap: 8px;
            }

            .mobile-promo-input {
                flex: 1;
                padding: 12px;
                font-size: 16px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background-color: #f9fafb;
            }

            .mobile-promo-input:focus {
                outline: none;
                background-color: white;
                border-color: #16a34a;
                box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
            }

            .mobile-promo-button {
                padding: 0 20px;
                background-color: #16a34a;
                color: white;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                font-size: 15px;
                white-space: nowrap;
            }

            .mobile-promo-button:active {
                background-color: #15803d;
                transform: translateY(1px);
            }

            /* Mobile-optimized floating filters button */
            .mobile-filter-button {
                position: fixed;
                right: 20px;
                bottom: 90px;
                width: 50px;
                height: 50px;
                background-color: #16a34a;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                z-index: 40;
                color: white;
            }

            /* Hide desktop elements on mobile */
            @media (max-width: 768px) {
                .desktop-search-filter-container {
                    display: none;
                }
            }

            /* Hide mobile elements on desktop */
            @media (min-width: 769px) {
                .mobile-search-container {
                    display: none;
                }

                .mobile-filter-button {
                    display: none;
                }
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

        /* Enhanced search input styles */
        .search-input-group {
            display: flex;
            position: relative;
            width: 100%;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
            outline: none;
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .search-button {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .search-button:hover {
            background-color: #15803d;
        }

        /* Bottom Sheet for Mobile */
        .mobile-bottom-sheet {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            z-index: 9998;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 1rem;
            max-height: 80vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .mobile-bottom-sheet.active {
            transform: translateY(0);
        }

        .sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .sheet-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: #111827;
        }

        .sheet-close {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: #f3f4f6;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            cursor: pointer;
        }

        .sheet-product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .sheet-product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            background-color: #f9fafb;
        }

        .sheet-product-details h3 {
            font-weight: 600;
            font-size: 1rem;
            margin: 0 0 0.25rem 0;
            color: #111827;
        }

        .sheet-product-details p {
            font-weight: 700;
            font-size: 1.125rem;
            margin: 0;
            color: #16a34a;
        }

        .sheet-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 9997;
        }

        .sheet-overlay.active {
            opacity: 1;
            visibility: visible;
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

    {{-- Mobile Bottom Sheet for Quantity Selection --}}
    <div class="sheet-overlay" id="sheetOverlay"></div>
    <div class="mobile-bottom-sheet" id="quantitySheet">
        <div class="sheet-header">
            <h3 class="sheet-title">Pilih Jumlah</h3>
            <button class="sheet-close" id="closeSheet">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="sheet-product-info" id="sheetProductInfo">
            <!-- Will be populated by JS -->
        </div>

        <div class="sheet-content">
            <p class="mb-2 font-medium text-gray-700">Pilih jumlah produk:</p>

            <div class="mb-6 quantity-selector">
                <button type="button" class="quantity-btn decrease-btn" id="decreaseQuantity">−</button>
                <input type="number" class="quantity-input" id="quantityInput" value="1" min="1"
                    max="100">
                <button type="button" class="quantity-btn increase-btn" id="increaseQuantity">+</button>
            </div>

            <form id="sheetAddToCartForm">
                <input type="hidden" name="product_id" id="sheetProductId">
                <input type="hidden" name="promo_code" id="sheetPromoCode" value="{{ $promo_code ?? '' }}">
                <input type="hidden" name="price" id="sheetProductPrice">
                <input type="hidden" name="quantity" id="sheetQuantity" value="1">

                <button type="submit" class="mobile-add-to-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                        <path d="M20 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>

    {{-- Mobile Filter Button --}}
    <button type="button" id="mobileFilterBtn" class="mobile-filter-button">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm0 8a1 1 0 011-1h10a1 1 0 010 2H4a1 1 0 01-1-1zm0 8a1 1 0 011-1h4a1 1 0 010 2H4a1 1 0 01-1-1z" />
        </svg>
    </button>

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
                    @click="changeBackground(idx)" :aria-selected="idx === currentBg"
                    :tabindex="idx === currentBg ? 0 : -1" :aria-label="`Gambar ${idx + 1}`"></button>
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

            {{-- Mobile Search and Filter UI --}}
            <div class="mobile-search-container">
                <form method="GET" action="{{ route('products.index') }}" id="mobileSearchForm">
                    <div class="mobile-search-input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mobile-search-icon" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Cari produk..."
                            class="mobile-search-input" aria-label="Pencarian produk">
                    </div>

                    <label class="mobile-filter-label">Filter Kategori:</label>
                    <div class="mobile-category-filters">
                        <a href="{{ route('products.index', ['search' => $searchQuery]) }}"
                            class="mobile-category-pill {{ $categoryFilter == 0 ? 'active' : '' }}">
                            Semua
                        </a>

                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->id, 'search' => $searchQuery]) }}"
                                class="mobile-category-pill {{ $categoryFilter == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <div class="mobile-promo-container">
                        <label class="mobile-promo-label">Punya kode promo?</label>
                        <form method="POST" action="{{ route('promo.activate') }}" class="mobile-promo-form">
                            @csrf
                            <input type="text" name="promo_code" value="{{ old('promo_code', $promo_code) }}"
                                placeholder="Masukkan kode promo" class="mobile-promo-input">
                            <button type="submit" class="mobile-promo-button">Aktifkan</button>
                        </form>
                    </div>
                </form>
            </div>

            {{-- Desktop Search and Filter Container --}}
            <div class="search-filter-container desktop-search-filter-container" role="search">
                <form method="GET" action="{{ route('products.index') }}" id="productSearchForm" class="mb-4">
                    <div class="search-input-group">
                        <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Cari produk..."
                            class="search-input" aria-label="Pencarian produk">
                        <button type="submit" class="search-button" aria-label="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mb-2 text-white">
                    <span class="font-medium">Filter Kategori:</span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index', ['search' => $searchQuery]) }}"
                        class="category-pill {{ $categoryFilter == 0 ? 'active' : '' }}">
                        Semua
                    </a>

                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id, 'search' => $searchQuery]) }}"
                            class="category-pill {{ $categoryFilter == $category->id ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Promo Code Form --}}
                <div class="pt-4 mt-6 border-t border-gray-200">
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
                    {{ $filteredProductCount }} Produk
                </div>
            </div>

            {{-- Mobile-optimized product grid --}}
            <div class="mb-12 {{ $isMobile ? '' : 'product-grid' }}">
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

                        // Get product image URL
                        $productImageUrl = '';
                        if ($displayImages->count() > 0) {
                            $productImageUrl = asset($displayImages->first()->image_url);
                        } elseif (!empty($product->image_url)) {
                            $productImageUrl = asset($product->image_url);
                        } else {
                            $productImageUrl = asset('images/produk/placeholder.png');
                        }

                        // Get category name for display
                        $categoryName = $product->category->name ?? 'Uncategorized';
                    @endphp

                    @if ($isMobile)
                        {{-- Mobile-optimized card --}}
                        <div class="mobile-product-card {{ $isInCart ? 'border-2 border-green-500' : '' }}"
                            data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                            data-product-price="{{ $final_price }}" data-product-image="{{ $productImageUrl }}"
                            data-product-in-cart="{{ $isInCart ? 'true' : 'false' }}">

                            @if ($promo_active)
                                <div
                                    class="absolute px-2 py-1 text-xs font-bold text-white bg-green-600 rounded-full top-2 left-2">
                                    DISKON {{ $promo_label }}
                                </div>
                            @endif

                            @if ($isInCart)
                                <div
                                    class="absolute px-2 py-1 text-xs font-bold text-white bg-green-600 rounded-full top-2 right-2">
                                    ✓ Di Keranjang
                                </div>
                            @endif

                            <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="mobile-product-image"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('images/produk/placeholder.png') }}';">

                            <div class="mobile-product-details">
                                <span class="mobile-product-category">{{ $categoryName }}</span>
                                <h3 class="mobile-product-title">{{ $product->name }}</h3>

                                @if ($promo_active)
                                    <div class="flex items-center mb-1">
                                        <span class="mr-2 text-sm text-gray-500 line-through">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span
                                            class="bg-green-100 text-green-800 text-xs px-1.5 py-0.5 rounded-full">-{{ $promo_label }}</span>
                                    </div>
                                    <div class="text-green-600 mobile-product-price">Rp
                                        {{ number_format($final_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="mobile-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                @endif

                                <div class="mobile-product-actions">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="flex-1 px-3 py-2 font-medium text-center text-gray-800 bg-gray-100 rounded-lg">
                                            Detail
                                        </a>

                                        @auth
                                            @if ($isInCart)
                                                <button disabled
                                                    class="flex-1 px-3 py-2 font-medium text-white bg-gray-400 rounded-lg opacity-70">
                                                    Di Keranjang
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="flex-1 px-3 py-2 font-medium text-white bg-green-600 rounded-lg buy-button"
                                                    data-product-id="{{ $product->id }}">
                                                    Beli
                                                </button>
                                            @endif
                                        @else
                                            <button disabled
                                                class="flex-1 px-3 py-2 font-medium text-gray-600 bg-gray-300 rounded-lg">
                                                Login
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Desktop card --}}
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
                                <div class="mb-1">
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        {{ $categoryName }}
                                    </span>
                                </div>
                                <h2 id="product-{{ $product->id }}-title"
                                    class="mb-2 text-xl font-semibold text-gray-800 line-clamp-2">{{ $product->name }}
                                </h2>
                                <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit($product->description, 80) }}
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
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" aria-hidden="true">
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
                                                class="add-to-cart-form"
                                                aria-label="Tambah {{ $product->name }} ke keranjang">
                                                @csrf
                                                <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                                <input type="hidden" name="price" value="{{ $final_price }}">
                                                <button type="submit"
                                                    class="flex items-center justify-center w-full px-4 py-2.5 text-white bg-green-700 rounded-lg hover:bg-green-800 add-to-cart-btn transition-all">
                                                    <span class="btn-text" aria-live="polite" aria-atomic="true">
                                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
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
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" aria-hidden="true">
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
                    @endif
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
                    {{ $products->appends(request()->except('page'))->links() }}
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
            const isMobile = {{ $isMobile ? 'true' : 'false' }};

            // Preload critical functions
            const loadingOverlay = getCachedElement('loadingOverlay');
            const toastContainer = getCachedElement('toastContainer');
            const confirmationModal = getCachedElement('confirmationModal');
            const quantitySheet = getCachedElement('quantitySheet');
            const sheetOverlay = getCachedElement('sheetOverlay');
            const quantityInput = getCachedElement('quantityInput');
            const decreaseBtn = getCachedElement('decreaseQuantity');
            const increaseBtn = getCachedElement('increaseQuantity');
            const closeSheetBtn = getCachedElement('closeSheet');
            const sheetAddToCartForm = getCachedElement('sheetAddToCartForm');
            const sheetProductInfo = getCachedElement('sheetProductInfo');
            const mobileFilterBtn = getCachedElement('mobileFilterBtn');

            // Global variables for mobile sheet
            let currentProductId = null;
            let currentProductPrice = 0;

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
                                'X-CSRF-TOKEN': csrfToken,
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

            // Mobile bottom sheet functions
            function showBottomSheet(productId, productName, productPrice, productImage) {
                if (!quantitySheet || !sheetOverlay) return;

                // Reset quantity to 1
                if (quantityInput) quantityInput.value = 1;

                // Update sheet content
                if (sheetProductInfo) {
                    sheetProductInfo.innerHTML = `
                        <img src="${productImage}" alt="${productName}" class="sheet-product-image">
                        <div class="sheet-product-details">
                            <h3>${productName}</h3>
                            <p>Rp ${new Intl.NumberFormat('id-ID').format(productPrice)}</p>
                        </div>
                    `;
                }

                // Set form values
                const productIdInput = getCachedElement('sheetProductId');
                const productPriceInput = getCachedElement('sheetProductPrice');
                const quantityValueInput = getCachedElement('sheetQuantity');

                if (productIdInput) productIdInput.value = productId;
                if (productPriceInput) productPriceInput.value = productPrice;
                if (quantityValueInput) quantityValueInput.value = 1;

                // Store current product info
                currentProductId = productId;
                currentProductPrice = productPrice;

                // Show the sheet and overlay
                quantitySheet.classList.add('active');
                sheetOverlay.classList.add('active');

                // Add body scroll lock
                document.body.style.overflow = 'hidden';
            }

            function hideBottomSheet() {
                if (!quantitySheet || !sheetOverlay) return;

                quantitySheet.classList.remove('active');
                sheetOverlay.classList.remove('active');

                // Remove body scroll lock
                document.body.style.overflow = '';

                // Reset current product
                currentProductId = null;
                currentProductPrice = 0;
            }

            // Setup quantity controls
            function setupQuantityControls() {
                if (!quantityInput || !decreaseBtn || !increaseBtn) return;

                decreaseBtn.addEventListener('click', () => {
                    const currentValue = parseInt(quantityInput.value) || 1;
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                        updateSheetQuantity();
                    }
                });

                increaseBtn.addEventListener('click', () => {
                    const currentValue = parseInt(quantityInput.value) || 1;
                    if (currentValue < 100) {
                        quantityInput.value = currentValue + 1;
                        updateSheetQuantity();
                    }
                });

                quantityInput.addEventListener('change', () => {
                    let value = parseInt(quantityInput.value) || 1;
                    value = Math.max(1, Math.min(100, value));
                    quantityInput.value = value;
                    updateSheetQuantity();
                });

                closeSheetBtn.addEventListener('click', hideBottomSheet);
                sheetOverlay.addEventListener('click', hideBottomSheet);
            }

            function updateSheetQuantity() {
                const quantityValueInput = getCachedElement('sheetQuantity');
                if (quantityValueInput && quantityInput) {
                    quantityValueInput.value = quantityInput.value;
                }
            }

            // Setup mobile buy buttons
            function setupMobileBuyButtons() {
                if (!isMobile) return;

                const buyButtons = document.querySelectorAll('.buy-button');
                buyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const productCard = this.closest('.mobile-product-card');
                        if (!productCard) return;

                        const productId = productCard.dataset.productId;
                        const productName = productCard.dataset.productName;
                        const productPrice = parseFloat(productCard.dataset.productPrice);
                        const productImage = productCard.dataset.productImage;
                        const inCart = productCard.dataset.productInCart === 'true';

                        if (inCart) return;

                        showBottomSheet(productId, productName, productPrice, productImage);
                    });
                });
            }

            // Setup bottom sheet form submission
            function setupSheetFormSubmission() {
                if (!sheetAddToCartForm) return;

                sheetAddToCartForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    showLoadingOverlay();

                    const formData = new FormData(this);
                    const productId = formData.get('product_id');
                    const quantity = formData.get('quantity');

                    // Send request to add to cart
                    fetch(`/products/${productId}/add-to-cart`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            hideLoadingOverlay();
                            hideBottomSheet();

                            if (data.success) {
                                // Update cart counter
                                updateCartCounter(data.data?.cart_count);

                                // Show success message
                                showToast('success', 'Berhasil!',
                                `Ditambahkan ${quantity} produk ke keranjang`);

                                // Update product card status
                                const productCard = document.querySelector(
                                    `.mobile-product-card[data-product-id="${productId}"]`);
                                if (productCard) {
                                    productCard.classList.add('border-2', 'border-green-500');
                                    productCard.dataset.productInCart = 'true';

                                    // Add the "in cart" badge if it doesn't exist
                                    if (!productCard.querySelector('.absolute.top-2.right-2')) {
                                        const badge = document.createElement('div');
                                        badge.className =
                                            'absolute top-2 right-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full';
                                        badge.textContent = '✓ Di Keranjang';
                                        productCard.appendChild(badge);
                                    }

                                    // Replace buy button
                                    const buyButtonContainer = productCard.querySelector(
                                        '.mobile-product-actions .flex');
                                    if (buyButtonContainer) {
                                        const buyButton = buyButtonContainer.querySelector('.buy-button');
                                        if (buyButton) {
                                            buyButton.outerHTML = `
                                            <button disabled 
                                                    class="flex-1 px-3 py-2 font-medium text-white bg-gray-400 rounded-lg opacity-70">
                                                Di Keranjang
                                            </button>
                                        `;
                                        }
                                    }
                                }

                                // Show confirmation modal after a short delay
                                setTimeout(() => {
                                    showConfirmationModal(
                                        'Produk berhasil ditambahkan!',
                                        'Mau lihat keranjang sekarang?',
                                        () => {
                                            window.location.href = "{{ url('/cart') }}";
                                        }
                                    );
                                }, PERFORMANCE_CONFIG.MODAL_DELAY);
                            } else {
                                showToast('error', 'Gagal', data.message ||
                                    'Gagal menambahkan produk ke keranjang');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            hideLoadingOverlay();
                            hideBottomSheet();
                            showToast('error', 'Gagal', 'Terjadi kesalahan, silakan coba lagi');
                        });
                });
            }

            // Setup mobile filter button
            function setupMobileFilterButton() {
                if (mobileFilterBtn) {
                    mobileFilterBtn.addEventListener('click', function() {
                        // Smooth scroll to the search container
                        const mobileSearchContainer = document.querySelector('.mobile-search-container');
                        if (mobileSearchContainer) {
                            mobileSearchContainer.scrollIntoView({
                                behavior: 'smooth'
                            });

                            // Focus the search input after scrolling
                            setTimeout(() => {
                                const searchInput = mobileSearchContainer.querySelector(
                                    '.mobile-search-input');
                                if (searchInput) searchInput.focus();
                            }, 400);
                        }
                    });
                }
            }

            // Setup horizontal scroll indicators for category filters
            function setupCategoryScrollHints() {
                const categoryFilters = document.querySelector('.mobile-category-filters');
                if (categoryFilters) {
                    // Check if scrollable
                    const isScrollable = categoryFilters.scrollWidth > categoryFilters.clientWidth;

                    if (isScrollable) {
                        // Add subtle animation to hint at scrollability
                        setTimeout(() => {
                            categoryFilters.scrollLeft = 20;
                            setTimeout(() => {
                                categoryFilters.scrollLeft = 0;
                            }, 300);
                        }, 1000);
                    }

                    // Add active class to the selected filter
                    const activePill = categoryFilters.querySelector('.mobile-category-pill.active');
                    if (activePill) {
                        // Scroll active pill into view (with offset)
                        setTimeout(() => {
                            activePill.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'center'
                            });
                        }, 100);
                    }
                }
            }

            // Initialize all mobile features
            function initializeMobileFeatures() {
                setupQuantityControls();
                setupMobileBuyButtons();
                setupSheetFormSubmission();
                setupMobileFilterButton();
                setupCategoryScrollHints();
            }

            function init() {
                // Force refresh cart count on page load
                updateCartCounter();

                // Mobile-specific initialization
                if (isMobile) {
                    initializeMobileFeatures();
                } else {
                    // Desktop-specific initializations can go here
                }

                // Hide initial loading
                hideLoadingOverlay();

                console.log('Product listing initialized - {{ $currentDateTime }} by {{ $currentUser }}');
            }

            // Fast initialization
            if (document.readyState === 'complete') {
                init();
            } else {
                window.addEventListener('load', init);
            }
        }
    </script>
@endsection
