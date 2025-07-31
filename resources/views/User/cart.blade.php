@extends('layouts.app')

@section('title', 'Keranjang Belanja - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        /**
             * Azka Garden Cart Page Styles
             * Updated: 2025-07-31 14:27:34 by DenuJanuari
             * Enhanced cart functionality with location services and shipping calculation
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
            margin-bottom: 1.5rem;
            font-weight: 800;
            font-size: 1.875rem;
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
            width: 80px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        /* Container & Layout */
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .two-column-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            align-items: flex-start;
        }

        /* Card Styles */
        .cart-card {
            background-color: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: var(--transition-all);
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-100);
        }

        .cart-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .cart-card-header {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            background-color: var(--gray-50);
        }

        .cart-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-card-title svg {
            color: var(--primary);
        }

        .cart-card-body {
            padding: 1.5rem;
        }

        /* Empty Cart Message */
        .empty-cart {
            padding: 3rem 1rem;
            text-align: center;
            background-color: var(--warning-bg);
            border-radius: 0.75rem;
            border: 1px dashed var(--warning);
            max-width: 500px;
            margin: 2rem auto;
            animation: fadeIn 0.5s ease;
        }

        .empty-cart-icon {
            font-size: 3rem;
            color: var(--warning);
            margin-bottom: 1rem;
        }

        .empty-cart-message {
            font-size: 1.25rem;
            color: var(--gray-800);
            margin-bottom: 1.5rem;
        }

        /* Cart Table Styles */
        .cart-table-wrapper {
            overflow-x: auto;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-sm);
        }

        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: var(--white);
        }

        .cart-table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-600);
            background-color: var(--gray-50);
            padding: 0.875rem 1rem;
            letter-spacing: 0.05em;
            text-align: left;
        }

        .cart-table th:first-child {
            border-top-left-radius: 0.5rem;
        }

        .cart-table th:last-child {
            border-top-right-radius: 0.5rem;
            text-align: center;
        }

        .cart-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .cart-table tr:last-child td {
            border-bottom: none;
        }

        .cart-table tr {
            transition: var(--transition-fast);
        }

        .cart-table tr:hover {
            background-color: var(--gray-50);
        }

        .product-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        .product-price {
            font-weight: 500;
            color: var(--gray-700);
            white-space: nowrap;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            max-width: 160px;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            font-weight: 600;
            padding: 0.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            color: var(--gray-800);
            transition: var(--transition-fast);
        }

        .quantity-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        .update-btn {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .update-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .subtotal {
            font-weight: 600;
            color: var(--primary);
            white-space: nowrap;
        }

        .remove-btn {
            background: none;
            border: none;
            color: var(--error);
            font-size: 1.25rem;
            cursor: pointer;
            transition: var(--transition-fast);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border-radius: 9999px;
        }

        .remove-btn:hover {
            background-color: var(--error-bg);
            transform: scale(1.15);
        }

        /* Address Styles */
        .address-card {
            background-color: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
            transition: var(--transition-fast);
        }

        .address-card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
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
        }

        .address-phone {
            margin-top: 0.5rem;
            color: var(--gray-700);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .store-info {
            margin: 1rem 0;
            display: flex;
            align-items: flex-start;
            padding: 0.75rem;
            background-color: var(--info-bg);
            border-radius: 0.5rem;
            color: var(--info);
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .store-info svg {
            flex-shrink: 0;
            margin-right: 0.5rem;
            margin-top: 0.1rem;
        }

        /* Location Panel */
        .location-panel {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            overflow: hidden;
            transition: var(--transition-all);
            box-shadow: var(--shadow-sm);
            margin-top: 1rem;
        }

        .location-panel:hover {
            box-shadow: var(--shadow-md);
        }

        .location-panel-header {
            background-color: var(--gray-50);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .location-panel-title {
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .location-panel-body {
            padding: 1.25rem;
        }

        /* Location Search */
        .location-search {
            display: flex;
            margin-bottom: 1rem;
            gap: 0.5rem;
        }

        .location-search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: var(--transition-fast);
        }

        .location-search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        .location-search-btn {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-fast);
            white-space: nowrap;
        }

        .location-search-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .location-search-results {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            background-color: var(--white);
            box-shadow: var(--shadow-md);
            display: none;
        }

        .location-search-result {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition-fast);
        }

        .location-search-result:last-child {
            border-bottom: none;
        }

        .location-search-result:hover {
            background-color: var(--primary-bg);
        }

        .location-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .location-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            border: 1px solid var(--gray-300);
            background-color: var(--white);
            transition: var(--transition-fast);
        }

        .location-action-btn:hover {
            background-color: var(--gray-100);
            border-color: var(--gray-400);
            transform: translateY(-1px);
        }

        .location-action-btn-primary {
            background-color: var(--primary-bg);
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .location-action-btn-primary:hover {
            background-color: var(--primary-light);
            color: var(--white);
        }

        .distance-info {
            margin-top: 1rem;
            padding: 1rem;
            background-color: var(--success-bg);
            border-radius: 0.5rem;
            color: var(--success);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            border: 1px solid #a7f3d0;
            transition: var(--transition-fast);
        }

        .distance-info.updating {
            background-color: var(--primary-bg);
            color: var(--primary);
            animation: pulse 0.5s ease;
        }

        .distance-info::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 40px;
            background-color: rgba(167, 243, 208, 0.5);
            border-radius: 0 0 0 40px;
        }

        .map-container {
            height: 250px;
            border-radius: 0.75rem;
            overflow: hidden;
            margin-top: 1rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            position: relative;
            transition: var(--transition-fast);
        }

        .map-container:hover {
            box-shadow: var(--shadow-md);
        }

        /* Shipping Methods */
        .shipping-methods {
            display: grid;
            gap: 0.75rem;
        }

        .shipping-method-card {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 1rem;
            background-color: var(--white);
            cursor: pointer;
            transition: var(--transition-all);
            position: relative;
            overflow: hidden;
        }

        .shipping-method-card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
            transform: translateY(-2px);
        }

        .shipping-method-card.selected,
        .shipping-method-card.recommended {
            border-color: var(--primary);
            background-color: var(--primary-bg);
        }

        .shipping-method-card.recommended::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background-color: var(--primary);
            transform: rotate(45deg);
            z-index: 1;
        }

        .shipping-method-card .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .shipping-method-card .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shipping-method-card input[type="radio"] {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary);
            position: relative;
            z-index: 2;
        }

        .shipping-method-card .name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
        }

        .shipping-method-card .cost {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.125rem;
        }

        .shipping-method-card .description {
            color: var(--gray-600);
            font-size: 0.875rem;
            position: relative;
            z-index: 2;
            line-height: 1.4;
        }

        .recommendation-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: var(--primary);
            color: var(--white);
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            z-index: 2;
        }

        /* Order Summary */
        .summary-card {
            position: sticky;
            top: 2rem;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: var(--transition-all);
        }

        .summary-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .summary-header {
            background-color: var(--gray-800);
            color: var(--white);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-700);
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
            background-color: var(--white);
            padding: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: var(--gray-700);
            transition: var(--transition-fast);
        }

        .summary-row.discount {
            color: var(--success);
            font-weight: 500;
        }

        .summary-row.tax {
            color: var(--primary);
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
            transition: var(--transition-fast);
        }

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

        .checkout-btn {
            flex: 1.5;
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
            text-decoration: none;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .checkout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: checkout-btn-shine 2s infinite;
        }

        @keyframes checkout-btn-shine {
            100% {
                left: 100%;
            }
        }

        .checkout-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: var(--white);
            text-decoration: none;
        }

        .checkout-btn:disabled {
            background-color: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* User Info */
        .user-info {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--gray-200);
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        /* Loading Animation */
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

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .two-column-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .summary-card {
                position: static;
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 0 0.75rem;
                margin: 1rem auto;
            }

            .page-heading {
                font-size: 1.5rem;
            }

            .cart-card-header,
            .cart-card-body {
                padding: 1rem;
            }

            .cart-table th,
            .cart-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }

            .quantity-control {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                max-width: none;
            }

            .quantity-input {
                width: 50px;
            }

            .update-btn {
                margin-left: 0;
            }

            .location-search {
                flex-direction: column;
                gap: 0.75rem;
            }

            .location-actions {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .shipping-methods {
                gap: 0.5rem;
            }

            .shipping-method-card {
                padding: 0.75rem;
            }

            .shipping-method-card .header {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .shipping-method-card .cost {
                margin-left: 1.75rem;
            }
        }

        @media (min-width: 768px) {
            .shipping-methods {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@section('content')
    @php
        // Calculate cart totals
        $sub = $items->sum(fn($i) => $i->price * $i->quantity);
        $disc =
            session('promo_type') === 'percent'
                ? $sub * (session('promo_discount') / 100)
                : session('promo_discount') ?? 0;

        $ship = $shippingMethods->firstWhere('id', $selectedShipId);
        $shipCost = $ship->cost ?? 0;

        // Calculate tax 10% from subtotal after discount
        $subtotalAfterDisc = max(0, $sub - $disc);
        $tax = $subtotalAfterDisc * 0.1;

        // Grand total including tax
        $grand = $subtotalAfterDisc + $shipCost + $tax;

        // User and address information
        $user = auth()->user();
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        $primaryAddress = null;
        if ($hasAddress) {
            $primaryAddress = $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first();
        }

        // Store location (Toko Bunga Hendrik at KSU)
        $storeLocation = [
            'lat' => -6.4122794,
            'lng' => 106.829692,
            'address' => 'Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412',
            'plus_code' => 'HRQH+3VP',
        ];

        // Current timestamp and user - UPDATED
        $currentDateTime = '2025-07-31 14:27:34';
        $currentUser = 'DenuJanuari';

        // Available shipping methods with enhanced data
        $availableShippingMethods = [
            [
                'id' => 1,
                'code' => 'KURIR_TOKO_DEKAT',
                'name' => 'Kurir Toko (< 5km)',
                'cost' => 10000,
                'description' => 'Pengiriman langsung dari toko dengan jarak dekat (< 5km)',
                'max_distance' => 5,
                'estimated_time' => '1-2 jam',
            ],
            [
                'id' => 2,
                'code' => 'KURIR_TOKO_SEDANG',
                'name' => 'Kurir Toko (5-10km)',
                'cost' => 15000,
                'description' => 'Pengiriman langsung dari toko dengan jarak sedang (5-10km)',
                'min_distance' => 5,
                'max_distance' => 10,
                'estimated_time' => '2-3 jam',
            ],
            [
                'id' => 3,
                'code' => 'KURIR_TOKO_JAUH',
                'name' => 'Kurir Toko (> 10km)',
                'cost' => 20000,
                'description' => 'Pengiriman langsung dari toko dengan jarak jauh (> 10km)',
                'min_distance' => 10,
                'estimated_time' => '3-4 jam',
            ],
            [
                'id' => 4,
                'code' => 'JNE',
                'name' => 'JNE Regular',
                'cost' => 18000,
                'description' => 'Pengiriman via JNE (estimasi 2-3 hari kerja)',
                'recommended_distance' => 15,
                'estimated_time' => '2-3 hari',
            ],
            [
                'id' => 5,
                'code' => 'JNT',
                'name' => 'J&T Express',
                'cost' => 20000,
                'description' => 'Pengiriman via J&T (estimasi 1-2 hari kerja)',
                'recommended_distance' => 20,
                'estimated_time' => '1-2 hari',
            ],
            [
                'id' => 6,
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri di Toko',
                'cost' => 0,
                'description' => 'Ambil pesanan langsung di toko (gratis ongkir)',
                'estimated_time' => 'Langsung',
            ],
        ];

        // Initialize checkout state for JavaScript
        $checkoutState = [
            'distanceKm' => 7.2,
            'shipping' => $shipCost,
            'subtotal' => $subtotalAfterDisc,
            'tax' => $tax,
            'total' => $grand,
            'timestamp' => $currentDateTime,
            'user' => $currentUser,
        ];
    @endphp

    <div class="cart-container">
        <h1 class="page-heading">Keranjang Belanja</h1>

        {{-- Empty cart message --}}
        @if ($items->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <div class="empty-cart-message">Keranjang belanja Anda masih kosong</div>
                <a href="{{ route('products.index') }}" class="checkout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Belanja Sekarang
                </a>
            </div>
        @else
            <div class="two-column-grid">
                <div class="cart-main">
                    {{-- Cart items table --}}
                    <div class="cart-card">
                        <div class="cart-card-header">
                            <h2 class="cart-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Produk dalam Keranjang ({{ $items->count() }} item)
                            </h2>
                        </div>
                        <div class="cart-card-body">
                            <div class="cart-table-wrapper">
                                <table class="cart-table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $row)
                                            <tr data-item-id="{{ $row->id }}">
                                                <td>
                                                    <div class="product-name">{{ $row->product->name ?? $row->name }}</div>
                                                    @if (isset($row->product->weight) && $row->product->weight > 0)
                                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                            Berat: {{ $row->product->weight }}kg
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="product-price">
                                                        Rp{{ number_format($row->price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.update', $row->id) }}" method="POST"
                                                        class="update-quantity-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="quantity-control">
                                                            <input type="number" name="quantity" min="1"
                                                                max="100" value="{{ $row->quantity }}"
                                                                class="quantity-input"
                                                                data-original-value="{{ $row->quantity }}">
                                                            <button type="submit" class="update-btn">Ubah</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    <div class="subtotal">
                                                        Rp{{ number_format($row->price * $row->quantity, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.remove', $row->id) }}" method="POST"
                                                        class="remove-item-form"
                                                        onsubmit="return confirm('Hapus {{ $row->product->name ?? $row->name }} dari keranjang?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="remove-btn"
                                                            title="Hapus item">×</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping address and distance --}}
                    <div class="cart-card">
                        <div class="cart-card-header">
                            <h2 class="cart-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Alamat Pengiriman & Jarak
                            </h2>
                        </div>
                        <div class="cart-card-body">
                            @if ($primaryAddress)
                                <div class="address-card">
                                    <div class="address-label">{{ $primaryAddress->label }}</div>
                                    <div class="address-recipient">{{ $primaryAddress->recipient }}</div>
                                    <div class="address-details">{{ $primaryAddress->full_address }}</div>
                                    <div class="address-details">{{ $primaryAddress->city }},
                                        {{ $primaryAddress->zip_code }}</div>
                                    <div class="address-phone">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                            </path>
                                        </svg>
                                        {{ $primaryAddress->phone_number }}
                                    </div>
                                </div>

                                <div class="store-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Toko:</strong> Azka Garden (Toko Bunga Hendrik)<br>
                                        <span style="font-size: 0.875rem;">{{ $storeLocation['address'] }}</span>
                                    </div>
                                </div>

                                {{-- Location panel for distance checking --}}
                                <div class="location-panel">
                                    <div class="location-panel-header">
                                        <h3 class="location-panel-title">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                                <line x1="8" y1="2" x2="8" y2="18">
                                                </line>
                                                <line x1="16" y1="6" x2="16" y2="22">
                                                </line>
                                            </svg>
                                            Cek Jarak & Ongkir
                                        </h3>
                                    </div>

                                    <div class="location-panel-body">
                                        <div class="location-search">
                                            <input type="text" id="location-search-input"
                                                class="location-search-input"
                                                placeholder="Masukkan alamat atau tempat untuk cek jarak" />
                                            <button type="button" id="location-search-btn" class="location-search-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65">
                                                    </line>
                                                </svg>
                                                Cari
                                            </button>
                                        </div>

                                        <div id="location-search-results" class="location-search-results"></div>

                                        <div class="location-actions">
                                            <button type="button" id="get-current-location"
                                                class="location-action-btn location-action-btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                                                </svg>
                                                Lokasi Saat Ini
                                            </button>

                                            <button type="button" id="show-store-location" class="location-action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                </svg>
                                                Lihat Toko
                                            </button>
                                        </div>

                                        <div id="distance-info" class="distance-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                            <strong>Jarak ke toko:</strong> {{ $checkoutState['distanceKm'] }} km
                                            <span style="color: var(--gray-600);">(estimasi
                                                {{ round($checkoutState['distanceKm'] * 2) }} menit via kendaraan)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Map container --}}
                                <div id="map-container" class="map-container"></div>

                                <div class="user-info">
                                    <span>Login: {{ $currentUser }}</span>
                                    <span>Waktu: {{ $currentDateTime }}</span>
                                </div>
                            @else
                                <div class="empty-cart">
                                    <div class="empty-cart-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div class="empty-cart-message">Alamat pengiriman belum ada</div>
                                    <a href="{{ route('user.address.create') }}" class="checkout-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                        Tambah Alamat Baru
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Shipping methods based on distance --}}
                    <div class="cart-card">
                        <div class="cart-card-header">
                            <h2 class="cart-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                Metode Pengiriman
                            </h2>
                        </div>
                        <div class="cart-card-body">
                            <div id="shipping-methods-container" class="shipping-methods">
                                {{-- Default shipping methods (will be updated dynamically) --}}
                                @foreach ($availableShippingMethods as $index => $method)
                                    <div class="shipping-method-card {{ $index === 1 ? 'recommended' : '' }}"
                                        data-id="{{ $method['id'] }}">
                                        <div class="header">
                                            <div class="radio-label">
                                                <input type="radio" name="shipping_method_id"
                                                    id="shipping_method_{{ $method['id'] }}" value="{{ $method['id'] }}"
                                                    {{ $index === 1 ? 'checked' : '' }}>
                                                <label for="shipping_method_{{ $method['id'] }}"
                                                    class="name">{{ $method['name'] }}</label>
                                            </div>
                                            <div class="cost">
                                                {{ $method['cost'] > 0 ? 'Rp ' . number_format($method['cost'], 0, ',', '.') : 'Gratis' }}
                                            </div>
                                        </div>
                                        <div class="description">
                                            {{ $method['description'] }}
                                            @if (isset($method['estimated_time']))
                                                <br><em style="color: var(--gray-500);">Estimasi:
                                                    {{ $method['estimated_time'] }}</em>
                                            @endif
                                        </div>
                                        @if ($index === 1)
                                            <div class="recommendation-badge">Direkomendasikan</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="cart-sidebar">
                    <div class="summary-card">
                        <div class="summary-header">
                            <h2 class="summary-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                Ringkasan Pesanan
                            </h2>
                        </div>
                        <div class="summary-body">
                            <div class="summary-row">
                                <span>Subtotal ({{ $items->count() }} item)</span>
                                <span>Rp{{ number_format($sub, 0, ',', '.') }}</span>
                            </div>

                            @if ($disc > 0)
                                <div class="summary-row discount">
                                    <span>Diskon
                                        @if (session('promo_code'))
                                            ({{ session('promo_code') }})
                                        @endif
                                    </span>
                                    <span>−Rp{{ number_format($disc, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="summary-row">
                                <span>Ongkos Kirim</span>
                                <span id="summary-shipping-cost">Rp{{ number_format($shipCost, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row tax">
                                <span>Pajak (10%)</span>
                                <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-divider"></div>

                            <div class="summary-total">
                                <span>Total Bayar</span>
                                <span id="summary-total">Rp{{ number_format($grand, 0, ',', '.') }}</span>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('products.index') }}" class="back-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    Lanjut Belanja
                                </a>

                                @if ($hasAddress)
                                    <form action="{{ route('cart.checkout') }}" method="POST" id="cart-checkout-form">
                                        @csrf
                                        <input type="hidden" name="shipping_method_id" id="selected_shipping_method"
                                            value="{{ $availableShippingMethods[1]['code'] }}">
                                        <input type="hidden" name="distance_km" id="selected_distance"
                                            value="{{ $checkoutState['distanceKm'] }}">
                                        <input type="hidden" name="shipping_fee" id="selected_shipping_fee"
                                            value="{{ $availableShippingMethods[1]['cost'] }}">
                                        <input type="hidden" name="customer_lat"
                                            value="{{ $primaryAddress->latitude ?? '' }}">
                                        <input type="hidden" name="customer_lng"
                                            value="{{ $primaryAddress->longitude ?? '' }}">

                                        <button type="submit" class="checkout-btn" id="proceed-checkout-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6">
                                                </line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                            </svg>
                                            Lanjut ke Checkout
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('user.address.create') }}" class="checkout-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        Tambah Alamat Dulu
                                    </a>
                                @endif
                            </div>

                            <div class="user-info">
                                <span>Berat total:
                                    {{ number_format($items->sum(fn($i) => ($i->product->weight ?? 0) * $i->quantity), 1) }}
                                    kg</span>
                                <span>{{ date('d/m/Y H:i', strtotime($currentDateTime)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if ($primaryAddress)
        <script>
            /**
             * Enhanced Cart Page JavaScript
             * Updated: 2025-07-31 14:27:34 by DenuJanuari
             * Integrated with improved checkout system
             */

            // Initialize checkout state from PHP
            let checkoutState = @json($checkoutState);

            // Enhanced shipping data management
            function updateSelectedShippingData() {
                const selectedRadio = document.querySelector('input[name="shipping_method_id"]:checked');
                const selectedShippingMethod = document.getElementById('selected_shipping_method');
                const selectedDistance = document.getElementById('selected_distance');
                const selectedShippingFee = document.getElementById('selected_shipping_fee');

                if (selectedRadio && selectedShippingMethod) {
                    // Find the selected method data
                    const shippingMethods = @json($availableShippingMethods);
                    const selectedMethod = shippingMethods.find(m => m.id == selectedRadio.value);

                    if (selectedMethod) {
                        selectedShippingMethod.value = selectedMethod.code;
                        selectedDistance.value = checkoutState.distanceKm || 7.2;
                        selectedShippingFee.value = selectedMethod.cost || 0;

                        // Update checkout state
                        checkoutState.shipping = selectedMethod.cost || 0;

                        console.log('Shipping data updated:', {
                            method_code: selectedMethod.code,
                            method_id: selectedRadio.value,
                            distance_km: checkoutState.distanceKm,
                            shipping_fee: selectedMethod.cost,
                            timestamp: '{{ $currentDateTime }}',
                            user: '{{ $currentUser }}'
                        });
                    }
                }
            }

            // Enhanced change event handler for shipping methods
            // Enhanced change event handler for shipping methods
            document.addEventListener('change', function(e) {
                if (e.target.name === 'shipping_method_id') {
                    updateSelectedShippingData();

                    // Update visual feedback
                    document.querySelectorAll('.shipping-method-card').forEach(card => {
                        card.classList.remove('selected', 'recommended');
                        const badge = card.querySelector('.recommendation-badge');
                        if (badge) badge.remove();
                    });

                    const selectedCard = e.target.closest('.shipping-method-card');
                    if (selectedCard) {
                        selectedCard.classList.add('selected', 'recommended');

                        // Add recommendation badge
                        if (!selectedCard.querySelector('.recommendation-badge')) {
                            const badge = document.createElement('div');
                            badge.className = 'recommendation-badge';
                            badge.textContent = 'Dipilih';
                            selectedCard.appendChild(badge);
                        }

                        // Update order summary
                        const methodId = e.target.value;
                        const shippingMethods = @json($availableShippingMethods);
                        const selectedMethod = shippingMethods.find(m => m.id == methodId);

                        if (selectedMethod) {
                            updateOrderSummary(selectedMethod.cost);
                        }
                    }
                }
            });

            // Enhanced form submission handler
            document.addEventListener('DOMContentLoaded', function() {
                const cartCheckoutForm = document.getElementById('cart-checkout-form');

                if (cartCheckoutForm) {
                    cartCheckoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        updateSelectedShippingData();

                        const selectedMethod = document.getElementById('selected_shipping_method');
                        const selectedMethodValue = selectedMethod ? selectedMethod.value : '';

                        if (!selectedMethodValue) {
                            // Show better error message
                            const errorMsg =
                                'Silakan pilih metode pengiriman terlebih dahulu sebelum melanjutkan checkout.';
                            alert(errorMsg);

                            // Focus on shipping methods section
                            const shippingSection = document.getElementById('shipping-methods-container');
                            if (shippingSection) {
                                shippingSection.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });

                                // Add visual highlight
                                shippingSection.style.border = '2px solid var(--error)';
                                setTimeout(() => {
                                    shippingSection.style.border = '';
                                }, 3000);
                            }

                            console.warn('Checkout blocked: No shipping method selected', {
                                timestamp: '2025-07-31 14:31:02',
                                user: 'DenuJanuari'
                            });

                            return false;
                        }

                        // Add loading state to form
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = `
                                <div style="width: 20px; height: 20px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 0.5rem; display: inline-block;"></div>
                                Memproses Checkout...
                            `;
                        }

                        console.log('Form submission validated and proceeding:', {
                            shipping_method: selectedMethodValue,
                            distance_km: checkoutState.distanceKm,
                            shipping_fee: checkoutState.shipping,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        // Submit form after brief delay for UX
                        setTimeout(() => {
                            this.submit();
                        }, 800);
                    });
                }

                // Initialize Feather Icons with error handling
                if (typeof feather !== 'undefined') {
                    feather.replace();
                } else {
                    // Load Feather Icons if not available
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js';
                    script.onload = function() {
                        feather.replace();
                        console.log('Feather icons loaded successfully', {
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });
                    };
                    script.onerror = function() {
                        console.warn('Failed to load Feather icons', {
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });
                    };
                    document.head.appendChild(script);
                }

                // DOM elements
                const searchInput = document.getElementById('location-search-input');
                const searchBtn = document.getElementById('location-search-btn');
                const searchResults = document.getElementById('location-search-results');
                const getCurrentLocationBtn = document.getElementById('get-current-location');
                const showStoreLocationBtn = document.getElementById('show-store-location');
                const distanceInfo = document.getElementById('distance-info');
                const mapContainer = document.getElementById('map-container');
                const shippingMethodsContainer = document.getElementById('shipping-methods-container');
                const summaryShippingCost = document.getElementById('summary-shipping-cost');
                const summaryTotal = document.getElementById('summary-total');

                // Store location - Toko Bunga Hendrik di KSU
                const storeLocation = {
                    lat: {{ $storeLocation['lat'] }},
                    lng: {{ $storeLocation['lng'] }},
                    name: 'Azka Garden (Toko Bunga Hendrik)',
                    address: '{{ $storeLocation['address'] }}'
                };

                // Customer saved address location
                let customerLocation = {
                    lat: {{ $primaryAddress->latitude ?? 'null' }},
                    lng: {{ $primaryAddress->longitude ?? 'null' }},
                    name: 'Alamat Pengiriman',
                    address: '{{ $primaryAddress->full_address ?? '' }}, {{ $primaryAddress->city ?? '' }}, {{ $primaryAddress->zip_code ?? '' }}'
                };

                // Current selected location (initialized to customer address if coordinates exist)
                let currentLocation = customerLocation.lat && customerLocation.lng ? customerLocation : null;

                // Define shipping methods
                const shippingMethods = @json($availableShippingMethods);

                // Subtotal dan Tax sudah dihitung
                const subtotal = {{ $subtotalAfterDisc }};
                const tax = {{ $tax }};

                // Shipping method yang dipilih (default: method ID 2)
                let selectedShippingMethodId = 2;

                // Enhanced location search with better error handling
                if (searchBtn) {
                    searchBtn.addEventListener('click', function() {
                        const query = searchInput.value.trim();
                        if (!query) {
                            alert('Silakan masukkan alamat yang ingin dicari');
                            return;
                        }

                        // Show loading indicator
                        if (searchResults) {
                            searchResults.style.display = 'block';
                            searchResults.innerHTML = `
                                <div style="padding: 15px; text-align: center; color: var(--primary);">
                                    <div style="width: 20px; height: 20px; border: 2px solid var(--primary); border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
                                    Mencari lokasi "${query}"...
                                </div>
                            `;
                        }

                        console.log('Location search initiated:', {
                            query: query,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        // Use Nominatim API (OpenStreetMap) for geocoding - no API key needed
                        fetch(
                                `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=5&countrycodes=id`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (searchResults) {
                                    searchResults.innerHTML = '';

                                    if (data.length === 0) {
                                        searchResults.innerHTML = `
                                            <div style="padding: 15px; text-align: center; color: var(--error);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; display: block;">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                                </svg>
                                                Tidak ada hasil ditemukan untuk "${query}"<br>
                                                <small style="color: var(--gray-500);">Coba gunakan kata kunci yang lebih spesifik</small>
                                            </div>
                                        `;
                                        return;
                                    }

                                    console.log('Location search results:', {
                                        query: query,
                                        results_count: data.length,
                                        timestamp: '2025-07-31 14:31:02',
                                        user: 'DenuJanuari'
                                    });

                                    // Display results
                                    data.forEach((result, index) => {
                                        const resultItem = document.createElement('div');
                                        resultItem.className = 'location-search-result';
                                        resultItem.innerHTML = `
                                            <div style="padding: 12px; border-bottom: 1px solid var(--gray-200); cursor: pointer; transition: background-color 0.2s;">
                                                <div style="font-weight: 600; color: var(--gray-900); margin-bottom: 4px;">${result.display_name.split(',')[0]}</div>
                                                <div style="font-size: 0.875rem; color: var(--gray-600);">${result.display_name}</div>
                                            </div>
                                        `;

                                        // Hover effects
                                        resultItem.addEventListener('mouseenter', function() {
                                            this.style.backgroundColor =
                                            'var(--primary-bg)';
                                        });

                                        resultItem.addEventListener('mouseleave', function() {
                                            this.style.backgroundColor = '';
                                        });

                                        // Set location when clicked
                                        resultItem.addEventListener('click', function() {
                                            // Set as current location
                                            currentLocation = {
                                                lat: parseFloat(result.lat),
                                                lng: parseFloat(result.lon),
                                                name: 'Lokasi Pencarian',
                                                address: result.display_name
                                            };

                                            console.log('Location selected from search:', {
                                                location: currentLocation,
                                                timestamp: '2025-07-31 14:31:02',
                                                user: 'DenuJanuari'
                                            });

                                            // Hide results
                                            searchResults.style.display = 'none';
                                            searchInput.value = result.display_name;

                                            // Show on map
                                            showMap();

                                            // Calculate distance and update shipping methods
                                            calculateDistance(currentLocation,
                                                storeLocation);
                                        });

                                        searchResults.appendChild(resultItem);
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error searching for location:', {
                                    error: error.message,
                                    query: query,
                                    timestamp: '2025-07-31 14:31:02',
                                    user: 'DenuJanuari'
                                });

                                if (searchResults) {
                                    searchResults.innerHTML = `
                                        <div style="padding: 15px; text-align: center; color: var(--error);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; display: block;">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            Error mencari lokasi: ${error.message}<br>
                                            <small style="color: var(--gray-500);">Silakan coba lagi atau gunakan kata kunci yang berbeda</small>
                                        </div>
                                    `;
                                }
                            });
                    });
                }

                // Handle search on Enter key
                if (searchInput) {
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (searchBtn) {
                                searchBtn.click();
                            }
                        }
                    });
                }

                // Enhanced get current location
                if (getCurrentLocationBtn) {
                    getCurrentLocationBtn.addEventListener('click', function() {
                        if (!navigator.geolocation) {
                            alert("Browser Anda tidak mendukung geolokasi. Silakan cari lokasi secara manual.");
                            return;
                        }

                        // Show loading state
                        const originalText = this.innerHTML;
                        this.innerHTML = `
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <div style="width: 16px; height: 16px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 8px;"></div>
                                Mencari lokasi...
                            </div>
                        `;
                        this.disabled = true;

                        console.log('Getting current location:', {
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                const accuracy = position.coords.accuracy;

                                console.log('Current location obtained:', {
                                    lat: lat,
                                    lng: lng,
                                    accuracy: accuracy,
                                    timestamp: '2025-07-31 14:31:02',
                                    user: 'DenuJanuari'
                                });

                                // Set as current location
                                currentLocation = {
                                    lat: lat,
                                    lng: lng,
                                    name: 'Lokasi Anda Saat Ini',
                                    address: 'Lokasi saat ini'
                                };

                                // Reverse geocode to get address details
                                fetch(
                                        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.display_name) {
                                            currentLocation.address = data.display_name;
                                            if (searchInput) {
                                                searchInput.value = data.display_name;
                                            }
                                        }

                                        // Show on map
                                        showMap();

                                        // Calculate distance and update shipping methods
                                        calculateDistance(currentLocation, storeLocation);

                                        // Reset button state
                                        getCurrentLocationBtn.innerHTML = originalText;
                                        getCurrentLocationBtn.disabled = false;
                                        if (typeof feather !== 'undefined') feather.replace();
                                    })
                                    .catch(error => {
                                        console.warn('Error getting address details:', {
                                            error: error.message,
                                            timestamp: '2025-07-31 14:31:02',
                                            user: 'DenuJanuari'
                                        });

                                        // Show on map anyway
                                        showMap();

                                        // Calculate distance and update shipping methods
                                        calculateDistance(currentLocation, storeLocation);

                                        // Reset button state
                                        getCurrentLocationBtn.innerHTML = originalText;
                                        getCurrentLocationBtn.disabled = false;
                                        if (typeof feather !== 'undefined') feather.replace();
                                    });
                            },
                            function(error) {
                                // Reset button state
                                getCurrentLocationBtn.innerHTML = originalText;
                                getCurrentLocationBtn.disabled = false;
                                if (typeof feather !== 'undefined') feather.replace();

                                // Enhanced error messages
                                let errorMsg = "Tidak dapat mengakses lokasi Anda.";
                                let debugInfo = '';

                                switch (error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMsg =
                                            "Akses lokasi ditolak. Pastikan Anda mengizinkan akses lokasi di browser dan coba lagi.";
                                        debugInfo = 'PERMISSION_DENIED';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMsg =
                                            "Informasi lokasi tidak tersedia. Silakan coba lagi atau cari lokasi secara manual.";
                                        debugInfo = 'POSITION_UNAVAILABLE';
                                        break;
                                    case error.TIMEOUT:
                                        errorMsg =
                                            "Permintaan lokasi habis waktu. Silakan coba lagi atau cari lokasi secara manual.";
                                        debugInfo = 'TIMEOUT';
                                        break;
                                    default:
                                        errorMsg =
                                            "Terjadi kesalahan saat mengakses lokasi. Silakan coba lagi.";
                                        debugInfo = 'UNKNOWN_ERROR';
                                        break;
                                }

                                console.error('Geolocation error:', {
                                    code: error.code,
                                    message: error.message,
                                    debug_info: debugInfo,
                                    timestamp: '2025-07-31 14:31:02',
                                    user: 'DenuJanuari'
                                });

                                alert(errorMsg);
                            }, {
                                enableHighAccuracy: true,
                                timeout: 15000,
                                maximumAge: 300000
                            }
                        );
                    });
                }

                // Show store location
                if (showStoreLocationBtn) {
                    showStoreLocationBtn.addEventListener('click', function() {
                        console.log('Showing store location:', {
                            store_location: storeLocation,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        // Set store as current location for map center
                        showMap(true);

                        // Update search input with store address
                        if (searchInput) {
                            searchInput.value = storeLocation.address;
                        }
                    });
                }

                // Calculate distance using Haversine formula
                function haversineDistance(loc1, loc2) {
                    const toRad = (value) => value * Math.PI / 180;
                    const R = 6371; // Earth radius in km

                    const dLat = toRad(loc2.lat - loc1.lat);
                    const dLon = toRad(loc2.lng - loc1.lng);
                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(toRad(loc1.lat)) * Math.cos(toRad(loc2.lat)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    const distance = R * c;

                    return {
                        value: distance,
                        text: distance.toFixed(1) + ' km'
                    };
                }

                // Calculate and display distance, update shipping methods
                function calculateDistance(origin, destination) {
                    if (!origin || !destination) {
                        console.warn('Cannot calculate distance: missing origin or destination', {
                            origin: origin,
                            destination: destination,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });
                        return;
                    }

                    try {
                        // Calculate with Haversine formula
                        const distance = haversineDistance(origin, destination);

                        // Estimate driving time
                        const minutes = Math.round(distance.value * 2);

                        console.log('Distance calculated:', {
                            distance_km: distance.value,
                            distance_text: distance.text,
                            estimated_minutes: minutes,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        // Update checkoutState
                        checkoutState.distanceKm = distance.value;
                        checkoutState.estimatedMinutes = minutes;

                        // Update distance info with animation
                        if (distanceInfo) {
                            distanceInfo.classList.add('updating');
                            setTimeout(() => {
                                distanceInfo.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <strong>Jarak ke toko:</strong> ${distance.text} 
                                    <span style="color: var(--gray-600);">(estimasi ${minutes} menit via kendaraan)</span>
                                `;
                                distanceInfo.classList.remove('updating');
                            }, 300);
                        }

                        // Update shipping methods based on distance
                        updateShippingMethods(distance.value);

                    } catch (error) {
                        console.error('Error calculating distance:', {
                            error: error.message,
                            origin: origin,
                            destination: destination,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        // Fallback to default distance
                        if (distanceInfo) {
                            distanceInfo.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <strong>Jarak ke toko:</strong> tidak dapat dihitung dengan akurat
                                <span style="color: var(--error);">(menggunakan estimasi default)</span>
                            `;
                        }

                        // Use default distance for shipping calculation
                        updateShippingMethods(7.2);
                    }
                }

                // Update shipping methods based on calculated distance
                function updateShippingMethods(distanceKm) {
                    if (!shippingMethodsContainer) return;

                    // Find recommended shipping method based on distance
                    let recommendedMethod = null;

                    if (distanceKm <= 5) {
                        recommendedMethod = shippingMethods.find(m => m.code === 'KURIR_TOKO_DEKAT');
                    } else if (distanceKm <= 10) {
                        recommendedMethod = shippingMethods.find(m => m.code === 'KURIR_TOKO_SEDANG');
                    } else if (distanceKm <= 15) {
                        recommendedMethod = shippingMethods.find(m => m.code === 'KURIR_TOKO_JAUH');
                    } else {
                        // For longer distances, prefer courier services
                        const longDistanceMethods = shippingMethods.filter(m =>
                            m.recommended_distance && m.recommended_distance >= distanceKm);

                        if (longDistanceMethods.length > 0) {
                            longDistanceMethods.sort((a, b) => a.recommended_distance - b.recommended_distance);
                            recommendedMethod = longDistanceMethods[0];
                        } else {
                            // Fallback to JNT for very long distances
                            recommendedMethod = shippingMethods.find(m => m.code === 'JNT') ||
                                shippingMethods.find(m => m.code === 'KURIR_TOKO_JAUH');
                        }
                    }

                    // If no method was found, use the first courier method
                    if (!recommendedMethod && shippingMethods.length > 0) {
                        recommendedMethod = shippingMethods[1]; // Default to KURIR_TOKO_SEDANG
                    }

                    console.log('Updating shipping methods for distance:', {
                        distance_km: distanceKm,
                        recommended_method: recommendedMethod?.code,
                        timestamp: '2025-07-31 14:31:02',
                        user: 'DenuJanuari'
                    });

                    // Clear the container with fade out effect
                    shippingMethodsContainer.style.opacity = '0.6';

                    // Add a slight delay for visual effect
                    setTimeout(() => {
                        // Clear container
                        shippingMethodsContainer.innerHTML = '';

                        // Add each shipping method
                        shippingMethods.forEach(method => {
                            const isRecommended = method.id === recommendedMethod?.id;

                            // Create shipping method card
                            const methodCard = document.createElement('div');
                            methodCard.className =
                                `shipping-method-card ${isRecommended ? 'recommended' : ''}`;
                            methodCard.setAttribute('data-id', method.id);

                            // Card content
                            methodCard.innerHTML = `
                                <div class="header">
                                    <div class="radio-label">
                                        <input type="radio" name="shipping_method_id" id="shipping_method_${method.id}" 
                                            value="${method.id}" ${isRecommended ? 'checked' : ''}>
                                        <label for="shipping_method_${method.id}" class="name">${method.name}</label>
                                    </div>
                                    <div class="cost">${method.cost > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(method.cost) : 'Gratis'}</div>
                                </div>
                                <div class="description">
                                    ${method.description}
                                    ${method.estimated_time ? `<br><em style="color: var(--gray-500);">Estimasi: ${method.estimated_time}</em>` : ''}
                                </div>
                                ${isRecommended ? '<div class="recommendation-badge">Direkomendasikan</div>' : ''}
                            `;

                            // Add click event to select this shipping method
                            methodCard.addEventListener('click', function() {
                                const radio = this.querySelector('input[type="radio"]');
                                if (radio) {
                                    radio.checked = true;

                                    // Update selected method
                                    selectedShippingMethodId = method.id;

                                    // Update all cards
                                    document.querySelectorAll('.shipping-method-card').forEach(
                                        card => {
                                            card.classList.remove('selected',
                                            'recommended');
                                            const badge = card.querySelector(
                                                '.recommendation-badge');
                                            if (badge) badge.remove();
                                        });

                                    // Mark this card as selected
                                    this.classList.add('selected', 'recommended');
                                    if (!this.querySelector('.recommendation-badge')) {
                                        const badge = document.createElement('div');
                                        badge.className = 'recommendation-badge';
                                        badge.textContent = 'Dipilih';
                                        this.appendChild(badge);
                                    }

                                    // Update summary with animation
                                    updateOrderSummary(method.cost);

                                    // Update checkout state
                                    checkoutState.shipping = method.cost;

                                    // Trigger change event for form update
                                    const changeEvent = new Event('change', {
                                        bubbles: true
                                    });
                                    radio.dispatchEvent(changeEvent);
                                }
                            });

                            // Add to container
                            shippingMethodsContainer.appendChild(methodCard);
                        });

                        // Set initial selection
                        if (recommendedMethod) {
                            selectedShippingMethodId = recommendedMethod.id;
                            updateOrderSummary(recommendedMethod.cost);
                            checkoutState.shipping = recommendedMethod.cost;
                        }

                        // Restore opacity
                        shippingMethodsContainer.style.opacity = '1';
                    }, 300);
                }

                // Update order summary with new shipping cost
                function updateOrderSummary(shippingCost) {
                    if (summaryShippingCost) {
                        // Add animation
                        summaryShippingCost.style.transition = 'all 0.3s ease';
                        summaryShippingCost.style.color = 'var(--primary)';

                        // Update value
                        summaryShippingCost.textContent = shippingCost > 0 ?
                            `Rp${new Intl.NumberFormat('id-ID').format(shippingCost)}` :
                            'Gratis';

                        // Reset color after animation
                        setTimeout(() => {
                            summaryShippingCost.style.color = '';
                        }, 1000);
                    }

                    if (summaryTotal) {
                        const total = subtotal + tax + shippingCost;
                        checkoutState.total = total;

                        // Add animation
                        summaryTotal.style.transition = 'all 0.3s ease';
                        summaryTotal.style.transform = 'scale(1.05)';
                        summaryTotal.style.color = 'var(--primary)';

                        // Update value
                        summaryTotal.textContent = `Rp${new Intl.NumberFormat('id-ID').format(total)}`;

                        // Reset after animation
                        setTimeout(() => {
                            summaryTotal.style.transform = '';
                            summaryTotal.style.color = '';
                        }, 1000);
                    }
                }

                // Show map with OpenStreetMap
                function showMap(centerOnStore = false) {
                    if (!mapContainer) return;

                    try {
                        // Clear previous map with fade out effect
                        mapContainer.style.opacity = '0.5';

                        setTimeout(() => {
                            // Clear container
                            mapContainer.innerHTML = '';

                            // Create map iframe with OpenStreetMap
                            let targetLat, targetLng, zoom;

                            if (centerOnStore) {
                                // Center on store
                                targetLat = storeLocation.lat;
                                targetLng = storeLocation.lng;
                                zoom = 15;
                            } else if (currentLocation) {
                                // Center between store and current location
                                targetLat = (storeLocation.lat + currentLocation.lat) / 2;
                                targetLng = (storeLocation.lng + currentLocation.lng) / 2;
                                zoom = 13;
                            } else {
                                // Default to store if no current location
                                targetLat = storeLocation.lat;
                                targetLng = storeLocation.lng;
                                zoom = 15;
                            }

                            // Create an OpenStreetMap iframe
                            const iframe = document.createElement('iframe');
                            iframe.width = '100%';
                            iframe.height = '100%';
                            iframe.frameBorder = '0';
                            iframe.scrolling = 'no';
                            iframe.marginHeight = '0';
                            iframe.marginWidth = '0';
                            iframe.style.borderRadius = '0.75rem';

                            // Create OSM URL with markers
                            let osmUrl =
                                `https://www.openstreetmap.org/export/embed.html?bbox=${targetLng-0.02}%2C${targetLat-0.02}%2C${targetLng+0.02}%2C${targetLat+0.02}&amp;layer=mapnik`;

                            // Add markers
                            if (storeLocation) {
                                osmUrl += `&amp;marker=${storeLocation.lat}%2C${storeLocation.lng}`;
                            }

                            if (currentLocation && currentLocation !== storeLocation) {
                                osmUrl += `&amp;marker=${currentLocation.lat}%2C${currentLocation.lng}`;
                            }

                            iframe.src = osmUrl;
                            mapContainer.appendChild(iframe);

                            // Add link to larger map
                            const linkContainer = document.createElement('div');
                            linkContainer.className = 'text-center text-sm mt-1';
                            linkContainer.innerHTML =
                                `<a href="https://www.openstreetmap.org/?mlat=${targetLat}&mlon=${targetLng}#map=${zoom}/${targetLat}/${targetLng}" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 0.8rem;">Lihat Peta Lebih Besar</a>`;
                            mapContainer.appendChild(linkContainer);

                            // Calculate distance if we have both points
                            if (currentLocation && storeLocation) {
                                calculateDistance(currentLocation, storeLocation);
                            }

                            // Restore opacity
                            mapContainer.style.opacity = '1';
                        }, 300);
                    } catch (error) {
                        console.error('Error showing map:', {
                            error: error.message,
                            timestamp: '2025-07-31 14:31:02',
                            user: 'DenuJanuari'
                        });

                        mapContainer.innerHTML = `
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: var(--gray-100); color: var(--gray-500);">
                                <div style="text-center; padding: 1rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <p>Tidak dapat menampilkan peta</p>
                                    <p style="font-size: 0.75rem;">Koordinat Toko: ${storeLocation.lat}, ${storeLocation.lng}</p>
                                </div>
                            </div>
                        `;
                    }
                }

                // Initialize map with customer address if coordinates exist
                if (customerLocation.lat && customerLocation.lng) {
                    currentLocation = customerLocation;
                    if (searchInput) {
                        searchInput.value = customerLocation.address;
                    }
                    showMap();
                    calculateDistance(customerLocation, storeLocation);
                } else {
                    // Just show store location if no customer coordinates
                    showMap(true);
                    // Set default shipping methods
                    updateShippingMethods(7.2);
                }

                // Add animated entrance effect to elements
                const animateElements = () => {
                    const elements = [
                        '.cart-card',
                        '.shipping-method-card',
                        '.summary-card'
                    ];

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
                setTimeout(animateElements, 200);

                // Log successful initialization
                console.log('Enhanced cart system initialized successfully:', {
                    store_location: storeLocation,
                    customer_location: customerLocation,
                    shipping_methods_count: shippingMethods.length,
                    checkout_state: checkoutState,
                    timestamp: '2025-07-31 14:31:02',
                    user: 'DenuJanuari',
                    version: '3.0.0'
                });
            });
        </script>
    @endif

    {{-- Loading overlay for better UX --}}
    <div class="loading-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background-color: white; padding: 2rem; border-radius: 0.75rem; text-align: center; box-shadow: var(--shadow-xl);">
            <div
                style="width: 40px; height: 40px; border: 4px solid var(--gray-200); border-top: 4px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;">
            </div>
            <p style="margin: 0; font-weight: 600; color: var(--gray-800);">Memproses permintaan Anda...</p>
            <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: var(--gray-600);">Mohon tunggu sebentar</p>
        </div>
    </div>

    {{-- Additional CSS for loading overlay --}}
    <style>
        .loading-overlay.active {
            display: flex !important;
        }

        /* Enhanced form loading states */
        .update-quantity-form.loading .update-btn {
            background-color: var(--gray-400);
            cursor: not-allowed;
        }

        .remove-item-form.loading .remove-btn {
            color: var(--gray-400);
            cursor: not-allowed;
        }

        /* Improved responsive shipping methods grid */
        @media (max-width: 640px) {
            .shipping-methods {
                grid-template-columns: 1fr;
            }

            .shipping-method-card .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .shipping-method-card .cost {
                margin-left: 1.75rem;
                font-size: 1rem;
            }
        }
    </style>
@endsection
