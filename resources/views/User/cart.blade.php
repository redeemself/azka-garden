@extends('layouts.app')

@section('title', 'Keranjang Anda')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @php
        $user = auth()->user();
        $promo_code = session('promo_code') ?? '';
        $localMethods = \DB::table('local_payment_methods')->where('status', 1)->get();
        $globalMethods = \DB::table('global_payment_methods')->where('status', 1)->get();
        $allMethods = collect($localMethods)->merge($globalMethods);
        $selected_payment = old('payment_method', session('payment_method') ?? ($allMethods->first()->code ?? null));
        $shippingMethods = [
            [
                'code' => 'KURIR_TOKO',
                'name' => 'Kurir Toko',
                'desc' =>
                    'Pengiriman langsung dari toko Azka Garden. <b>Ongkir flat sesuai jarak:</b> <br><span style="color:#047857;font-size:0.98em;">(&lt;5km) Rp10.000, (5-10km) Rp15.000, (&gt;10km) Rp20.000</span>',
                'icon' => 'truck',
            ],
            [
                'code' => 'GOSEND',
                'name' => 'GoSend Sameday',
                'desc' => 'Kirim instan via GoSend (estimasi Rp15.000-30.000 sesuai aplikasi)',
                'icon' => 'send',
            ],
            [
                'code' => 'JNE',
                'name' => 'JNE REG',
                'desc' => 'Reguler via JNE (8.000-20.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'package',
            ],
            [
                'code' => 'JNT',
                'name' => 'J&T EZ',
                'desc' => 'J&T EZ (10.000-22.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'truck',
            ],
            [
                'code' => 'SICEPAT',
                'name' => 'SiCepat BEST',
                'desc' => 'SiCepat BEST (10.000-18.000/kg, estimasi aplikasi atau admin)',
                'icon' => 'zap',
            ],
            [
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri di Toko',
                'desc' => 'Ambil langsung ke Azka Garden, <b style="color:#16a34a;">bebas ongkir</b>!',
                'icon' => 'shopping-bag',
            ],
        ];
        $selected_shipping = old('shipping_method', session('shipping_method') ?? $shippingMethods[0]['code']);
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        $primaryAddress = $hasAddress
            ? $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first()
            : null;
    @endphp

    <style>
        :root {
            --primary: #166534;
            --primary-light: #16a34a;
            --primary-dark: #14532d;
            --primary-bg: #f0fdf4;
            --primary-bg-hover: #dcfce7;
            --accent: #4ade80;
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
            --error: #b91c1c;
            --error-bg: #fee2e2;
            --warning: #ea580c;
            --warning-bg: #ffedd5;
            --success: #16a34a;
            --success-bg: #dcfce7;
            --info: #0284c7;
            --info-bg: #e0f2fe;
        }

        .cart-container {
            max-width: 1200px;
            margin: 2rem auto 3rem;
            padding: 0 1rem;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .cart-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .cart-header p {
            color: var(--gray-600);
            font-size: 1.1rem;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 1.5rem;
            transition: opacity 0.3s ease;
        }

        .cart-grid.fade-out {
            opacity: 0;
        }

        .cart-items {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .cart-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cart-panel {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cart-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .cart-panel-header {
            background: var(--primary-bg);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .cart-panel-header h2 {
            color: var(--primary);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-panel-body {
            padding: 1.5rem;
        }

        .cart-panel-footer {
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .cart-table th {
            background: var(--primary-bg);
            color: var(--primary);
            font-weight: 600;
            text-align: left;
            padding: 1rem 1.5rem;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .cart-table td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }

        .cart-table tbody tr:last-child td {
            border-bottom: none;
        }

        .cart-table tbody tr {
            transition: background-color 0.2s ease, opacity 0.3s ease, transform 0.3s ease;
        }

        .cart-table tbody tr:hover {
            background: var(--gray-50);
        }

        .cart-table tbody tr.deleting {
            opacity: 0.7;
            transform: translateX(-10px);
            background-color: var(--gray-100);
        }

        .cart-table tbody tr.deleted {
            opacity: 0;
            transform: translateX(-100%);
            height: 0;
            padding: 0;
            overflow: hidden;
        }

        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
        }

        .cart-item-details {
            padding-left: 1rem;
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            font-size: 1.05rem;
        }

        .cart-item-variant {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .cart-price {
            font-weight: 600;
            color: var(--gray-800);
            white-space: nowrap;
        }

        .cart-price-original {
            text-decoration: line-through;
            color: var(--gray-400);
            font-size: 0.875rem;
            display: block;
            font-weight: 400;
        }

        .cart-price-discounted {
            color: var(--primary-dark);
        }

        .cart-quantity {
            display: flex;
            align-items: center;
            max-width: 120px;
        }

        .cart-quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            border: 1px solid var(--gray-300);
            background: var(--white);
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }

        .cart-quantity-btn:hover {
            background: var(--gray-100);
            color: var(--gray-900);
            transform: scale(1.05);
        }

        .cart-quantity-btn:active {
            transform: scale(0.95);
        }

        .cart-quantity-input {
            width: 40px;
            text-align: center;
            border: none;
            padding: 0.25rem;
            font-size: 0.95rem;
            color: var(--gray-800);
            -moz-appearance: textfield;
        }

        .cart-quantity-input::-webkit-outer-spin-button,
        .cart-quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .cart-remove {
            color: var(--error);
            background: var(--error-bg);
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .cart-remove:hover {
            background: var(--error);
            color: var(--white);
            transform: scale(1.1);
        }

        .cart-remove:active {
            transform: scale(0.95);
        }

        .cart-promo {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .cart-promo input {
            flex: 1;
            min-width: 200px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .cart-promo input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        .cart-promo button {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 120px;
        }

        .cart-promo button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-promo button:active {
            transform: translateY(0);
        }

        .cart-promo button:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
        }

        .promo-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid var(--primary-light);
            margin-top: 0.5rem;
            transition: all 0.2s ease;
        }

        .promo-badge:hover {
            background: var(--primary-bg-hover);
            transform: translateY(-1px);
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            color: var(--gray-600);
        }

        .cart-summary-row.cart-total {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.25rem;
            border-top: 1px solid var(--gray-200);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }

        .cart-checkout {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            padding: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .cart-checkout:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .cart-checkout:active {
            transform: translateY(0);
        }

        .cart-checkout::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
        }

        .cart-checkout:hover::after {
            animation: checkout-shine 1.5s infinite;
        }

        @keyframes checkout-shine {
            100% {
                transform: translateX(100%);
            }
        }

        .cart-checkout:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .cart-empty {
            text-align: center;
            padding: 3rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            animation: fadeIn 0.5s ease-out;
        }

        .cart-empty-icon {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .cart-empty-icon svg {
            width: 100px;
            height: 100px;
            stroke-width: 1;
        }

        .cart-empty-title {
            font-size: 1.5rem;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
        }

        .cart-empty-text {
            color: var(--gray-500);
            margin-bottom: 1.5rem;
        }

        .cart-shop-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .cart-shop-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Shipping methods */
        .shipping-methods {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .shipping-method {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .shipping-method:hover {
            border-color: var(--primary-light);
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        .shipping-method.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        .shipping-method-radio {
            margin-top: 0.25rem;
            margin-right: 0.75rem;
            accent-color: var(--primary);
        }

        .shipping-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: var(--primary-bg);
            color: var(--primary);
            border-radius: 0.5rem;
            margin-right: 0.75rem;
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .shipping-method.selected .shipping-method-icon {
            background: var(--primary);
            color: var(--white);
        }

        .shipping-method-details {
            flex: 1;
        }

        .shipping-method-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .shipping-method-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Payment methods */
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-light);
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        .payment-method-radio {
            margin-right: 0.75rem;
            accent-color: var(--primary);
        }

        .payment-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 2rem;
            margin-right: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary);
            transition: all 0.2s ease;
        }

        .payment-method.selected .payment-method-icon {
            transform: scale(1.1);
        }

        .payment-method-details {
            flex: 1;
        }

        .payment-method-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        .payment-method-desc {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Address */
        .address-panel {
            background: var(--info-bg);
            border: 1px solid #bae6fd;
            padding: 1rem;
            border-radius: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .address-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .address-panel h3 {
            color: var(--info);
            font-size: 1rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .address-card {
            background: var(--white);
            border: 1px solid #93c5fd;
            padding: 1rem;
            border-radius: 0.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        .address-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .address-detail {
            color: var(--gray-600);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .shipping-estimate {
            background: var(--primary-bg);
            border: 1px solid #a7f3d0;
            padding: 1rem;
            border-radius: 0.75rem;
            margin: 1rem 0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .shipping-estimate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .shipping-estimate-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shipping-estimate-text {
            color: var(--gray-600);
        }

        /* Mobile Cart */
        .mobile-cart {
            display: none;
        }

        .mobile-cart-item {
            background: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.2s ease;
        }

        .mobile-cart-item:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .mobile-cart-item.deleting {
            opacity: 0.7;
            transform: translateX(-10px);
            background-color: var(--gray-100);
        }

        .mobile-cart-item.deleted {
            opacity: 0;
            transform: translateX(-100%);
            height: 0;
            padding: 0;
            overflow: hidden;
            margin: 0;
        }

        .mobile-cart-item-top {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-cart-item-image {
            width: 64px;
            height: 64px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
        }

        .mobile-cart-item-details {
            flex: 1;
        }

        .mobile-cart-item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            font-size: 1.05rem;
        }

        .mobile-cart-item-price {
            color: var(--primary);
            font-weight: 600;
        }

        .mobile-cart-item-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.75rem;
        }

        .mobile-cart-item-quantity {
            display: flex;
            align-items: center;
        }

        /* Modern Toast Notification System */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 350px;
            width: 100%;
            pointer-events: none;
        }

        .toast {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                opacity 0.3s ease;
            pointer-events: auto;
            position: relative;
            overflow: hidden;
        }

        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 4px;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-success::before {
            background-color: var(--success);
        }

        .toast-error::before {
            background-color: var(--error);
        }

        .toast-warning::before {
            background-color: var(--warning);
        }

        .toast-info::before {
            background-color: var(--info);
        }

        .toast-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            color: var(--success);
        }

        .toast-error .toast-icon {
            color: var(--error);
        }

        .toast-warning .toast-icon {
            color: var(--warning);
        }

        .toast-info .toast-icon {
            color: var(--info);
        }

        .toast-content {
            flex: 1;
            padding-right: 20px;
            /* Space for close button */
        }

        .toast-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--gray-800);
        }

        .toast-message {
            font-size: 0.875rem;
            color: var(--gray-600);
            word-break: break-word;
        }

        .toast-close {
            color: var(--gray-400);
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.2s ease;
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .toast-close:hover {
            color: var(--gray-600);
        }

        .toast-retry-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            padding: 6px 12px;
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toast-retry-btn:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        /* Modern Confirmation Dialog */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
            visibility: hidden;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-dialog {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 450px;
            transform: scale(0.95);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal-overlay.show .modal-dialog {
            transform: scale(1);
            opacity: 1;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            pointer-events: auto;
            background-clip: padding-box;
            outline: 0;
            overflow: hidden;
            border-radius: 16px;
        }

        .modal-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .modal-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.5;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--gray-400);
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .modal-close:hover {
            background-color: var(--gray-100);
            color: var(--gray-600);
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1.5rem;
        }

        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            gap: 0.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            text-align: center;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.15s ease;
            cursor: pointer;
            gap: 0.5rem;
        }

        .btn-primary {
            color: var(--white);
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            color: var(--white);
            background-color: var(--error);
            border-color: var(--error);
        }

        .btn-danger:hover {
            background-color: #991b1b;
            border-color: #991b1b;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-secondary {
            color: var(--gray-600);
            background-color: transparent;
            border-color: var(--gray-300);
        }

        .btn-outline-secondary:hover {
            color: var(--gray-800);
            background-color: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-1px);
        }

        /* Product preview in confirmation dialog */
        .product-preview {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 8px;
            margin-top: 1rem;
            border: 1px solid var(--gray-200);
        }

        .product-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
            border: 1px solid var(--gray-200);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0 0 0.25rem;
        }

        .product-price {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            border: 4px solid var(--gray-200);
            border-radius: 50%;
            border-top: 4px solid var(--primary);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        /* Active promo code styling */
        .active-promo-container {
            background: var(--success-bg);
            border: 1px solid #86efac;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            animation: fadeIn 0.5s;
        }

        .active-promo-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .active-promo-info {
            flex: 1;
            min-width: 180px;
        }

        .active-promo-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .active-promo-title svg {
            color: var(--primary);
        }

        .active-promo-title span {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .active-promo-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background-color: var(--white);
            color: var(--primary);
            border: 1px solid var(--primary-light);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .active-promo-action {
            flex-shrink: 0;
        }

        .promo-remove-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--error-bg);
            color: var(--error);
            border: 1px solid var(--error);
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .promo-remove-btn:hover {
            background-color: var(--error);
            color: var(--white);
        }

        .promo-remove-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .flash-update {
            animation: pulse 0.5s;
        }

        .quantity-update-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid var(--gray-200);
            border-radius: 50%;
            border-top: 2px solid var(--primary);
            animation: spin 1s linear infinite;
            display: none;
            margin: 0 4px;
        }

        .updating .quantity-update-spinner {
            display: inline-block;
        }

        .updating .cart-quantity-btn {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Map related styles */
        #map-container {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        /* Improved mobile responsiveness */
        @media (max-width: 1024px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }

            .cart-items {
                margin-bottom: 1rem;
            }

            .cart-promo {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .cart-promo input {
                flex: 1 1 200px;
            }

            .cart-promo button {
                flex: 0 0 auto;
            }
        }

        @media (max-width: 768px) {
            .cart-table {
                display: none;
            }

            .mobile-cart {
                display: block;
            }

            .cart-container {
                margin-top: 1rem;
                padding: 0 0.5rem;
            }

            .cart-header h1 {
                font-size: 1.75rem;
            }

            .cart-header p {
                font-size: 1rem;
            }

            .cart-panel-header {
                padding: 0.75rem 1rem;
            }

            .cart-panel-header h2 {
                font-size: 1.1rem;
            }

            .cart-panel-body {
                padding: 1rem;
            }

            /* Improved promo code section for mobile */
            .cart-promo {
                flex-direction: column;
                gap: 0.5rem;
            }

            .cart-promo input {
                width: 100%;
            }

            .cart-promo button {
                width: 100%;
            }

            .active-promo-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .active-promo-action {
                width: 100%;
            }

            .promo-remove-btn {
                width: 100%;
                justify-content: center;
            }

            .modal-dialog {
                max-width: 100%;
                margin: 0 16px;
            }

            /* Improved toast positioning for mobile */
            .toast-container {
                top: auto;
                bottom: 20px;
                left: 20px;
                right: 20px;
                max-width: none;
                width: auto;
            }

            .toast {
                transform: translateY(100%);
                width: 100%;
                max-width: none;
            }

            .toast.show {
                transform: translateY(0);
            }

            .toast-content {
                min-width: 0;
                /* Helps with text wrapping */
            }

            .cart-empty {
                min-height: 300px;
            }

            .cart-empty-icon svg {
                width: 80px;
                height: 80px;
            }
        }

        @media (max-width: 480px) {
            .cart-panel-body {
                padding: 0.75rem;
            }

            .cart-header h1 {
                font-size: 1.5rem;
            }

            .shipping-method,
            .payment-method {
                padding: 0.75rem;
            }

            .shipping-method-icon,
            .payment-method-icon {
                width: 2rem;
                height: 2rem;
                margin-right: 0.5rem;
            }

            .toast {
                padding: 12px;
                border-radius: 8px;
            }

            .toast-icon {
                display: none;
                /* Save space on very small screens */
            }

            .toast-retry-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Improved Icons */
        .icon {
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        /* For empty cart styling */
        .empty-cart-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 5rem 2rem;
            text-align: center;
        }

        .empty-cart-image {
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .cart-illustration {
            position: relative;
            margin-bottom: 2rem;
        }

        .cart-illustration svg {
            width: 160px;
            height: 160px;
            color: var(--gray-300);
        }

        .cart-illustration .arrow {
            position: absolute;
            top: 40%;
            right: -50px;
            width: 100px;
            height: 40px;
            color: #dc2626;
        }

        /* Hidden token input */
        .token-container {
            display: none;
        }
    </style>

    <form id="csrf-form">
        @csrf
    </form>

    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="toast-container"></div>

    <div class="modal-container"></div>

    <div class="cart-container">
        <div class="cart-header">
            <h1>Keranjang Belanja</h1>
            @if (session('promo_code'))
                <div class="promo-badge">
                    <i class="icon" data-feather="tag"></i>
                    <span>Promo: {{ session('promo_code') }}</span>
                </div>
            @endif
        </div>

        @if ($cartItems->count() > 0)
            <div class="cart-grid" id="cart-content">
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grand_total = 0;
                                $total_discount = 0;
                            @endphp
                            @foreach ($cartItems as $index => $item)
                                @php
                                    $promo = $item->promo_code ?? $promo_code;
                                    $promotion = $promo
                                        ? \App\Models\Promotion::where('promo_code', $promo)->first()
                                        : null;
                                    $discount = 0;
                                    $unit_price = $item->product->price ?? 0;
                                    $qty = $item->quantity ?? 0;

                                    if ($promotion) {
                                        if ($promotion->discount_type === 'percent') {
                                            $percent = $promotion->discount_value ?: 10;
                                            $discount = round($unit_price * ($percent / 100));
                                        } elseif ($promotion->discount_type === 'fixed') {
                                            $discount = min($promotion->discount_value ?: 0, $unit_price);
                                        }
                                    }

                                    $discounted_price = max(0, $unit_price - $discount);
                                    $item_total = $discounted_price * $qty;
                                    $grand_total += $item_total;
                                    $total_discount += $discount * $qty;
                                @endphp
                                <tr data-item-row="{{ $item->id }}" data-unit-price="{{ $unit_price }}"
                                    data-discounted-price="{{ $discounted_price }}">
                                    <td>
                                        <div class="flex items-center">
                                            <img src="{{ asset($item->product->image_url ?? 'images/no-image.png') }}"
                                                alt="{{ $item->product->name }}" class="cart-item-image">
                                            <div class="cart-item-details">
                                                <div class="cart-item-name">{{ $item->product->name }}</div>
                                                <div class="cart-item-variant">
                                                    {{ Str::limit($item->product->description, 60) }}</div>
                                                @if ($promotion)
                                                    <div class="mt-2 promo-badge">
                                                        <i class="icon" data-feather="tag"></i>
                                                        <span>{{ $promotion->promo_code }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cart-price">
                                            @if ($discount > 0)
                                                <span class="cart-price-original">Rp
                                                    {{ number_format($unit_price, 0, ',', '.') }}</span>
                                            @endif
                                            <span class="cart-price-discounted">Rp
                                                {{ number_format($discounted_price, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cart-quantity" data-item-id="{{ $item->id }}">
                                            <button type="button" class="cart-quantity-btn quantity-decrease">
                                                <span style="font-weight: bold;">−</span>
                                            </button>
                                            <input type="number" min="1" max="{{ $item->product->stock }}"
                                                value="{{ $qty }}" class="cart-quantity-input"
                                                data-item-id="{{ $item->id }}">
                                            <button type="button" class="cart-quantity-btn quantity-increase">
                                                <span style="font-weight: bold;">+</span>
                                            </button>
                                            <div class="quantity-update-spinner"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cart-price item-total" data-item-id="{{ $item->id }}">Rp
                                            {{ number_format($item_total, 0, ',', '.') }}</div>
                                    </td>
                                    <!-- Untuk desktop view -->
                                    <td>
                                        <button type="button" class="cart-remove" data-item-id="{{ $item->id }}"
                                            title="Hapus">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mobile Cart View -->
                    <div class="mobile-cart">
                        @foreach ($cartItems as $item)
                            @php
                                $promo = $item->promo_code ?? $promo_code;
                                $promotion = $promo
                                    ? \App\Models\Promotion::where('promo_code', $promo)->first()
                                    : null;
                                $discount = 0;
                                $unit_price = $item->product->price ?? 0;
                                $qty = $item->quantity ?? 0;

                                if ($promotion) {
                                    if ($promotion->discount_type === 'percent') {
                                        $percent = $promotion->discount_value ?: 10;
                                        $discount = round($unit_price * ($percent / 100));
                                    } elseif ($promotion->discount_type === 'fixed') {
                                        $discount = min($promotion->discount_value ?: 0, $unit_price);
                                    }
                                }

                                $discounted_price = max(0, $unit_price - $discount);
                                $item_total = $discounted_price * $qty;
                            @endphp
                            <div class="mobile-cart-item" data-item-row="{{ $item->id }}"
                                data-unit-price="{{ $unit_price }}" data-discounted-price="{{ $discounted_price }}">
                                <div class="mobile-cart-item-top">
                                    <img src="{{ asset($item->product->image_url ?? 'images/no-image.png') }}"
                                        alt="{{ $item->product->name }}" class="mobile-cart-item-image">
                                    <div class="mobile-cart-item-details">
                                        <div class="mobile-cart-item-name">{{ $item->product->name }}</div>
                                        @if ($discount > 0)
                                            <div class="text-sm text-gray-400 line-through">Rp
                                                {{ number_format($unit_price, 0, ',', '.') }}</div>
                                        @endif
                                        <div class="mobile-cart-item-price">Rp
                                            {{ number_format($discounted_price, 0, ',', '.') }}</div>
                                        @if ($promotion)
                                            <div class="mt-2 promo-badge">
                                                <i class="icon" data-feather="tag"></i>
                                                <span>{{ $promotion->promo_code }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mobile-cart-item-bottom">
                                    <div class="mobile-cart-item-quantity" data-item-id="{{ $item->id }}">
                                        <button type="button" class="cart-quantity-btn quantity-decrease">
                                            <span style="font-weight: bold;">−</span>
                                        </button>
                                        <input type="number" min="1" max="{{ $item->product->stock }}"
                                            value="{{ $qty }}" class="cart-quantity-input"
                                            data-item-id="{{ $item->id }}">
                                        <button type="button" class="cart-quantity-btn quantity-increase">
                                            <span style="font-weight: bold;">+</span>
                                        </button>
                                        <div class="quantity-update-spinner"></div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="font-semibold mobile-item-total" data-item-id="{{ $item->id }}">Rp
                                            {{ number_format($item_total, 0, ',', '.') }}</div>
                                        <!-- Untuk mobile view -->
                                        <button type="button" class="cart-remove" data-item-id="{{ $item->id }}"
                                            title="Hapus">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="cart-sidebar">
                    <div class="cart-panel">
                        <div class="cart-panel-header">
                            <h2>
                                <i class="icon" data-feather="credit-card"></i>
                                Ringkasan Pesanan
                            </h2>
                        </div>
                        <div class="cart-panel-body">
                            <div class="cart-summary-row">
                                <span>Subtotal</span>
                                <span id="cart-subtotal">Rp
                                    {{ number_format($grand_total + $total_discount, 0, ',', '.') }}</span>
                            </div>
                            <div class="cart-summary-row" id="cart-discount-row"
                                style="{{ $total_discount > 0 ? '' : 'display: none;' }}">
                                <span>Diskon</span>
                                <span id="cart-discount">-Rp {{ number_format($total_discount, 0, ',', '.') }}</span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Ongkir</span>
                                <span id="cart-shipping" data-value="0">Dihitung saat checkout</span>
                            </div>
                            <div class="cart-summary-row cart-total">
                                <span>Total</span>
                                <span id="cart-total">Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="cart-panel">
                        <div class="cart-panel-header">
                            <h2>
                                <i class="icon" data-feather="tag"></i>
                                Kode Promo
                            </h2>
                        </div>
                        <div class="cart-panel-body">
                            @if (session('promo_code'))
                                <!-- Redesigned active promo section -->
                                <div class="active-promo-container">
                                    <div class="active-promo-content">
                                        <div class="active-promo-info">
                                            <div class="active-promo-title">
                                                <i data-feather="check-circle"></i>
                                                <span>Kode promo aktif:</span>
                                            </div>
                                            <strong class="text-primary-dark">{{ session('promo_code') }}</strong>
                                            @if (isset($activePromo))
                                                <div class="active-promo-badge">
                                                    @if ($activePromo->discount_type == 'percent')
                                                        Diskon {{ $activePromo->discount_value }}%
                                                    @elseif($activePromo->discount_type == 'fixed')
                                                        Diskon Rp
                                                        {{ number_format($activePromo->discount_value, 0, ',', '.') }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="active-promo-action">
                                            <button type="button" id="remove-promo-btn" class="promo-remove-btn">
                                                <i data-feather="x"></i>
                                                Hapus Promo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('promo.deactivate') }}" method="POST" id="promo-remove-form"
                                    style="display:none;">
                                    @csrf
                                </form>
                            @else
                                <form action="{{ route('promo.activate') }}" method="POST" class="cart-promo"
                                    id="promo-apply-form">
                                    @csrf
                                    <input type="text" name="promo_code" placeholder="Masukkan kode promo" required>
                                    <button type="submit" id="apply-promo-btn">Terapkan</button>
                                </form>
                                <p class="mt-2 text-sm text-gray-500">Masukkan kode promo untuk mendapatkan diskon</p>
                            @endif
                        </div>
                    </div>

                    @if ($hasAddress && $primaryAddress)
                        <div class="address-panel">
                            <h3>
                                <i class="icon" data-feather="map-pin"></i>
                                Alamat Pengiriman
                            </h3>
                            <div class="address-card">
                                <div class="address-name">{{ $primaryAddress->label ?? 'Alamat Utama' }}</div>
                                <div class="address-detail"><strong>{{ $primaryAddress->recipient }}</strong></div>
                                <div class="address-detail">{{ $primaryAddress->full_address }}</div>
                                <div class="address-detail">{{ $primaryAddress->city }}, {{ $primaryAddress->zip_code }}
                                </div>
                                <div class="address-detail">{{ $primaryAddress->phone_number }}</div>
                            </div>

                            <!-- Add Map Container for address visualization -->
                            <div id="map-container"></div>
                        </div>
                    @endif

                    <div class="cart-panel">
                        <div class="cart-panel-header">
                            <h2>
                                <i class="icon" data-feather="truck"></i>
                                Metode Pengiriman
                            </h2>
                        </div>
                        <div class="cart-panel-body">
                            <div class="shipping-methods">
                                <form id="shipping-method-form" method="POST">
                                    @csrf
                                    @foreach ($shippingMethods as $method)
                                        <label
                                            class="shipping-method{{ $selected_shipping == $method['code'] ? ' selected' : '' }}">
                                            <input type="radio" name="shipping_method" value="{{ $method['code'] }}"
                                                class="shipping-method-radio"
                                                {{ $selected_shipping == $method['code'] ? 'checked' : '' }}>
                                            <div class="shipping-method-icon">
                                                <i data-feather="{{ $method['icon'] }}"></i>
                                            </div>
                                            <div class="shipping-method-details">
                                                <div class="shipping-method-name">{!! $method['name'] !!}</div>
                                                <div class="shipping-method-desc">{!! $method['desc'] !!}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="shippingCalc" class="shipping-estimate">
                        <div class="shipping-estimate-title">
                            <i class="icon" data-feather="map"></i>
                            Estimasi Ongkir
                        </div>
                        <div id="shippingEstimateText" class="shipping-estimate-text">
                            Silakan pilih alamat/metode pengiriman untuk estimasi biaya kirim.
                        </div>
                    </div>

                    <div class="cart-panel">
                        <div class="cart-panel-header">
                            <h2>
                                <i class="icon" data-feather="credit-card"></i>
                                Metode Pembayaran
                            </h2>
                        </div>
                        <div class="cart-panel-body">
                            <div class="payment-methods">
                                @foreach ($allMethods as $method)
                                    <label
                                        class="payment-method {{ $selected_payment == $method->code ? 'selected' : '' }}">
                                        <input type="radio" name="payment_method" value="{{ $method->code }}"
                                            class="payment-method-radio"
                                            {{ $selected_payment == $method->code ? 'checked' : '' }}>
                                        <div class="payment-method-icon">
                                            @switch($method->code)
                                                @case('QRIS')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 3H9V9H3V3Z" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15 3H21V9H15V3Z" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M3 15H9V21H3V15Z" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15 15H21V21H15V15Z" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                @break

                                                @case('EWALLET')
                                                    <i data-feather="smartphone"></i>
                                                @break

                                                @default
                                                    <i data-feather="credit-card"></i>
                                            @endswitch
                                        </div>
                                        <div class="payment-method-details">
                                            <div class="payment-method-name">{{ $method->name }}</div>
                                            @if ($method->config)
                                                <div class="payment-method-desc">
                                                    {{ is_array($method->config) ? $method->config['desc'] ?? '' : json_decode($method->config)->desc ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <!-- Hidden input for shipping and address to be sent to checkout page via session or query, not directly posted here -->
                            <input type="hidden" name="shipping_method" id="shipping_method_input"
                                value="{{ $selected_shipping }}">
                            @if ($primaryAddress)
                                <input type="hidden" name="shipping_address_id" value="{{ $primaryAddress->id }}">
                            @endif
                            <!-- Tombol Checkout dipindahkan ke bawah setelah E-wallet -->
                            <div class="cart-checkout-wrapper" style="margin-top: 1.5rem;">
                                <a href="{{ route('checkout.index') }}" class="cart-checkout" id="checkout-link">
                                    <i data-feather="shopping-bag"></i>
                                    Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="cart-empty">
                <div class="cart-illustration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
                <h2 class="cart-empty-title">Keranjang Belanja Anda Kosong</h2>
                <p class="cart-empty-text">Belum ada produk yang Anda tambahkan ke keranjang.</p>
                <a href="{{ route('products.index') }}" class="cart-shop-btn">
                    <i data-feather="shopping-bag"></i>
                    Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

    <script>
        /**
         * Enhanced Cart System
         * Modern, dynamic and smooth shopping cart experience
         * Current date and time: 2025-07-30 14:51:21
         * Current user: marseltriwanto
         */

        /**
         * Complete cart solution that works without relying on AJAX endpoints
         * Date: 2025-07-30 15:28:24
         * User: marseltriwanto
         */

        // Modify the cart state to support client-side updates
        const cartState = {
            items: [],
            updating: false,
            pendingQuantityUpdates: {},
            shippingMethod: null,
            shippingCost: 0,
            paymentMethod: null,
            pendingChanges: [], // Track changes that need to be committed

            // Initialize with data from the page
            init(items) {
                this.items = items || [];
                this.loadFromLocalStorage();
            },

            // Save important state to localStorage
            saveToLocalStorage() {
                const dataToSave = {
                    itemCount: this.items.length,
                    shippingMethod: this.shippingMethod,
                    paymentMethod: this.paymentMethod,
                    pendingChanges: this.pendingChanges
                };
                localStorage.setItem('cartState', JSON.stringify(dataToSave));
            },

            // Load saved state from localStorage
            loadFromLocalStorage() {
                try {
                    const savedState = JSON.parse(localStorage.getItem('cartState') || '{}');
                    this.shippingMethod = savedState.shippingMethod || null;
                    this.paymentMethod = savedState.paymentMethod || null;
                    this.pendingChanges = savedState.pendingChanges || [];
                } catch (e) {
                    console.error('Error loading cart state from localStorage:', e);
                }
            },

            // Update cart counter in UI and localStorage
            updateCartCounter() {
                const counter = document.getElementById('cart-counter');
                if (counter) {
                    counter.textContent = this.items.length;
                }
                localStorage.setItem('cartItemCount', this.items.length);
            },

            // Client-side removal - doesn't actually delete from server
            removeItem(itemId) {
                const index = this.items.findIndex(item => item.id === itemId);
                if (index !== -1) {
                    this.items.splice(index, 1);
                    this.updateCartCounter();

                    // Track this change for synchronization on checkout
                    this.pendingChanges.push({
                        action: 'delete',
                        itemId: itemId
                    });

                    this.saveToLocalStorage();
                }
            },

            // Client-side quantity update - doesn't actually update server
            updateQuantity(itemId, quantity) {
                const item = this.items.find(item => item.id === itemId);
                if (item) {
                    item.quantity = quantity;

                    // Track this change for synchronization on checkout
                    const existingChange = this.pendingChanges.find(change =>
                        change.action === 'update' && change.itemId === itemId);

                    if (existingChange) {
                        existingChange.quantity = quantity;
                    } else {
                        this.pendingChanges.push({
                            action: 'update',
                            itemId: itemId,
                            quantity: quantity
                        });
                    }

                    this.saveToLocalStorage();
                }
            },

            // Add item to cart
            addItem(item) {
                // Check if item already exists
                const index = this.items.findIndex(i => i.id === item.id);
                if (index !== -1) {
                    // Update quantity if exists
                    this.items[index].quantity += item.quantity || 1;
                } else {
                    // Add new item
                    this.items.push(item);
                }
                this.updateCartCounter();
                this.saveToLocalStorage();
            }
        };


        /**
         * Create a form to sync all pending changes and redirect to checkout
         */
        function createSyncForm() {
            // Create a hidden form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/cart/sync';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_token"]')?.value;

            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
            }

            // Add all pending changes as JSON
            const changesInput = document.createElement('input');
            changesInput.type = 'hidden';
            changesInput.name = 'pending_changes';
            changesInput.value = JSON.stringify(cartState.pendingChanges);
            form.appendChild(changesInput);

            // Add shipping method
            if (cartState.shippingMethod) {
                const shippingInput = document.createElement('input');
                shippingInput.type = 'hidden';
                shippingInput.name = 'shipping_method';
                shippingInput.value = cartState.shippingMethod;
                form.appendChild(shippingInput);
            }

            // Add payment method
            if (cartState.paymentMethod) {
                const paymentInput = document.createElement('input');
                paymentInput.type = 'hidden';
                paymentInput.name = 'payment_method';
                paymentInput.value = cartState.paymentMethod;
                form.appendChild(paymentInput);
            }

            // Add the form to the document
            document.body.appendChild(form);

            return form;
        }


        /**
         * Helper function to get CSRF token from various sources
         */
        function getCSRFToken() {
            // Try meta tag first
            let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // If not found, try hidden input
            if (!token) {
                token = document.querySelector('input[name="_token"]')?.value;
            }

            // Check dedicated form
            if (!token) {
                token = document.querySelector('#csrf-form input[name="_token"]')?.value;
            }

            return token;
        }

        /**
         * Modern Toast Notification System
         */
        const toastSystem = {
            container: null,

            init() {
                // Create toast container if it doesn't exist
                this.container = document.querySelector('.toast-container');
                if (!this.container) {
                    this.container = document.createElement('div');
                    this.container.className = 'toast-container';
                    document.body.appendChild(this.container);
                }
            },

            show(type, title, message, duration = 5000) { // Increased duration for better readability
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;

                // Determine icon based on type
                const iconName = type === 'success' ? 'check-circle' :
                    type === 'error' ? 'alert-circle' :
                    type === 'warning' ? 'alert-triangle' : 'info';

                // Toast content
                toast.innerHTML = `
            <div class="toast-icon">
                <i data-feather="${iconName}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">&times;</button>
        `;

                // Add to container
                this.container.appendChild(toast);

                // Initialize Feather icons
                if (window.feather) {
                    feather.replace();
                }

                // Add event listener for close button
                toast.querySelector('.toast-close').addEventListener('click', () => {
                    this.dismiss(toast);
                });

                // Show with animation
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.add('show');
                    });
                });

                // Auto dismiss after duration
                if (duration > 0) {
                    setTimeout(() => {
                        this.dismiss(toast);
                    }, duration);
                }

                return toast;
            },

            dismiss(toast) {
                toast.classList.remove('show');

                toast.addEventListener('transitionend', () => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, {
                    once: true
                });
            },

            success(title, message, duration) {
                return this.show('success', title, message, duration);
            },

            error(title, message, duration) {
                return this.show('error', title, message, duration);
            },

            warning(title, message, duration) {
                return this.show('warning', title, message, duration);
            },

            info(title, message, duration) {
                return this.show('info', title, message, duration);
            }
        };

        /**
         * Modern Confirmation Dialog System
         */
        const confirmDialog = {
            container: null,
            activeDialog: null,

            init() {
                this.container = document.querySelector('.modal-container');
                if (!this.container) {
                    this.container = document.createElement('div');
                    this.container.className = 'modal-container';
                    document.body.appendChild(this.container);
                }
            },

            show(options = {}) {
                return new Promise((resolve) => {
                    // Remove any existing dialogs
                    this.dismiss();

                    // Default options
                    const config = Object.assign({
                        title: 'Konfirmasi',
                        message: 'Apakah Anda yakin?',
                        confirmText: 'Ya',
                        cancelText: 'Batal',
                        confirmStyle: 'primary', // primary, danger, warning
                        showCancel: true,
                        icon: 'help-circle',
                        product: null // for product deletion
                    }, options);

                    // Create overlay
                    const overlay = document.createElement('div');
                    overlay.className = 'modal-overlay';

                    // Create dialog container
                    const dialog = document.createElement('div');
                    dialog.className = 'modal-dialog';

                    // Determine button style class
                    let buttonClass = 'btn-primary';
                    if (config.confirmStyle === 'danger') {
                        buttonClass = 'btn-danger';
                    } else if (config.confirmStyle === 'warning') {
                        buttonClass = 'btn-warning';
                    }

                    // Dialog content
                    let productHtml = '';
                    if (config.product) {
                        productHtml = `
                    <div class="product-preview">
                        <img src="${config.product.image}" alt="${config.product.name}" class="product-image">
                        <div class="product-details">
                            <h4 class="product-name">${config.product.name}</h4>
                            <div class="product-price">${config.product.price}</div>
                        </div>
                    </div>
                `;
                    }

                    dialog.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i data-feather="${config.icon}"></i>
                            ${config.title}
                        </h5>
                        <button type="button" class="modal-close" data-action="cancel">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>${config.message}</p>
                        ${productHtml}
                    </div>
                    <div class="modal-footer">
                        ${config.showCancel ?
                            `<button type="button" class="btn btn-outline-secondary" data-action="cancel">${config.cancelText}</button>` :
                            ''}
                        <button type="button" class="btn ${buttonClass}" data-action="confirm">${config.confirmText}</button>
                    </div>
                </div>
            `;

                    // Add to DOM
                    overlay.appendChild(dialog);
                    this.container.appendChild(overlay);
                    this.activeDialog = overlay;

                    // Initialize Feather icons
                    if (window.feather) {
                        feather.replace();
                    }

                    // Add event listeners
                    overlay.querySelectorAll('[data-action="confirm"]').forEach(button => {
                        button.addEventListener('click', () => {
                            this.dismiss();
                            resolve(true);
                        });
                    });

                    overlay.querySelectorAll('[data-action="cancel"]').forEach(button => {
                        button.addEventListener('click', () => {
                            this.dismiss();
                            resolve(false);
                        });
                    });

                    // Allow clicking outside to dismiss (optional)
                    overlay.addEventListener('click', (e) => {
                        if (e.target === overlay) {
                            this.dismiss();
                            resolve(false);
                        }
                    });

                    // Show with animation
                    requestAnimationFrame(() => {
                        overlay.classList.add('show');
                    });

                    // Make dialog focusable and focus it
                    dialog.setAttribute('tabindex', '-1');
                    dialog.focus();
                });
            },

            dismiss() {
                if (this.activeDialog) {
                    this.activeDialog.classList.remove('show');

                    // Remove after animation
                    setTimeout(() => {
                        if (this.activeDialog && this.activeDialog.parentNode) {
                            this.activeDialog.parentNode.removeChild(this.activeDialog);
                        }
                        this.activeDialog = null;
                    }, 300);
                }
            }
        };

        /**
         * Loading Overlay System
         */
        const loadingOverlay = {
            overlay: null,
            counter: 0,

            init() {
                this.overlay = document.querySelector('.loading-overlay');
                if (!this.overlay) {
                    this.overlay = document.createElement('div');
                    this.overlay.className = 'loading-overlay';
                    this.overlay.innerHTML = '<div class="loading-spinner"></div>';
                    document.body.appendChild(this.overlay);
                }
            },

            show() {
                this.counter++;
                this.overlay.classList.add('active');
            },

            hide() {
                this.counter--;
                if (this.counter <= 0) {
                    this.counter = 0;
                    this.overlay.classList.remove('active');
                }
            },

            hideAll() {
                this.counter = 0;
                this.overlay.classList.remove('active');
            }
        };

        /**
         * Format number as currency
         * @param {number} value - Number to format
         * @returns {string} - Formatted number
         */
        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        /**
         * Add product to cart
         * @param {number} productId - Product ID
         * @param {number} quantity - Quantity to add
         * @returns {Promise} - Promise that resolves when product is added
         */
        async function addToCart(productId, quantity = 1) {
            try {
                // Get CSRF token
                const csrfToken = getCSRFToken();

                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan. Silakan refresh halaman.');
                }

                // Show loading overlay
                loadingOverlay.show();

                // Create form data
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', quantity);
                formData.append('_token', csrfToken);

                // Use fetch for AJAX request
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    // Add to cart state
                    cartState.addItem({
                        id: data.item.id,
                        productId: productId,
                        quantity: quantity,
                        unitPrice: data.item.price,
                        discountedPrice: data.item.discounted_price || data.item.price,
                        maxStock: data.item.max_stock || 100
                    });

                    // Show success notification
                    toastSystem.success('Berhasil', 'Produk berhasil ditambahkan ke keranjang');

                    // Update cart counter
                    cartState.updateCartCounter();

                    return data;
                } else {
                    throw new Error(data.message || 'Gagal menambahkan produk ke keranjang');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);

                loadingOverlay.hide();

                // Show error notification
                toastSystem.error('Gagal', error.message ||
                    'Gagal menambahkan produk ke keranjang. Silakan coba lagi.');

                throw error;
            } finally {
                loadingOverlay.hide();
            }
        }

        /**
         * Client-side cart item deletion
         * This doesn't communicate with the server at all, just updates the UI
         * @param {number} itemId - The ID of the item to delete
         * @returns {Promise} - A promise that resolves when the item is visually deleted
         */
        async function deleteCartItem(itemId) {
            try {
                // Find all elements for this item (desktop and mobile views)
                const itemElements = document.querySelectorAll(`[data-item-row="${itemId}"]`);
                if (itemElements.length === 0) {
                    throw new Error('Item tidak ditemukan di halaman');
                }

                // Show loading overlay
                loadingOverlay.show();

                // Add deleting animation class
                itemElements.forEach(el => el.classList.add('deleting'));

                // Client-side only deletion (no server communication)
                // Remove from cart state
                cartState.removeItem(itemId);

                // Simulate delay for animation
                await new Promise(resolve => setTimeout(resolve, 300));

                // Smoothly remove from UI with animation
                itemElements.forEach(el => {
                    el.classList.add('deleted');

                    // Remove from DOM after animation completes
                    el.addEventListener('transitionend', () => {
                        if (el.parentNode) {
                            el.parentNode.removeChild(el);
                        }

                        // Check if cart is empty after removal
                        const remainingItems = document.querySelectorAll('[data-item-row]');
                        if (remainingItems.length === 0) {
                            showEmptyCart();
                        } else {
                            calculateTotals();
                        }
                    }, {
                        once: true
                    });
                });

                // Show success notification
                toastSystem.success('Produk Dihapus', 'Produk berhasil dihapus dari keranjang');

                return {
                    success: true
                };
            } catch (error) {
                console.error('Error deleting cart item:', error);

                // Remove deleting animation from items
                document.querySelectorAll(`[data-item-row="${itemId}"]`).forEach(el => {
                    el.classList.remove('deleting');
                });

                loadingOverlay.hide();

                // Show error notification with retry button
                const errorToast = toastSystem.error('Gagal Menghapus', error.message ||
                    'Gagal menghapus produk dari keranjang.', 0);

                // Add retry button to error toast
                const toastContent = errorToast.querySelector('.toast-content');
                const retryBtn = document.createElement('button');
                retryBtn.className = 'toast-retry-btn';
                retryBtn.innerHTML = '<i data-feather="refresh-cw"></i> Coba Lagi';
                retryBtn.addEventListener('click', () => {
                    toastSystem.dismiss(errorToast);
                    // Create a slight delay before retrying
                    setTimeout(() => handleDeleteItem(itemId), 300);
                });
                toastContent.appendChild(retryBtn);

                // Initialize feather icons in retry button
                if (window.feather) {
                    feather.replace();
                }

                throw error;
            } finally {
                loadingOverlay.hide();
            }
        }


        /**
         * Handle item deletion with confirmation
         * @param {number} itemId - ID of the item to delete
         */
        async function handleDeleteItem(itemId) {
            try {
                // Find the item elements
                const itemElements = document.querySelectorAll(`[data-item-row="${itemId}"]`);
                if (itemElements.length === 0) return;

                // Get product information for confirmation dialog
                const firstElement = itemElements[0];
                const itemName = firstElement.querySelector('.cart-item-name, .mobile-cart-item-name')?.textContent ||
                    'Produk';
                const itemImage = firstElement.querySelector('.cart-item-image, .mobile-cart-item-image')?.src || '';
                const itemPrice = firstElement.querySelector('.cart-price, .mobile-cart-item-price')?.textContent || '';

                // Show confirmation dialog
                const confirmed = await confirmDialog.show({
                    title: 'Konfirmasi Hapus',
                    message: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                    confirmText: 'Hapus',
                    cancelText: 'Batal',
                    confirmStyle: 'danger',
                    icon: 'trash-2',
                    product: {
                        name: itemName,
                        image: itemImage,
                        price: itemPrice
                    }
                });

                // If confirmed, proceed with deletion
                if (confirmed) {
                    try {
                        await deleteCartItem(itemId);
                    } catch (error) {
                        console.error('Error deleting item:', error);
                        // Error is already handled in deleteCartItem
                    }
                }
            } catch (error) {
                console.error('Error in delete handler:', error);
            }
        }

        /**
         * Shows empty cart message when all items are removed
         */
        function showEmptyCart() {
            const cartContent = document.getElementById('cart-content');
            if (!cartContent) return;

            // Animate fade out
            cartContent.classList.add('fade-out');

            setTimeout(() => {
                cartContent.innerHTML = `
            <div class="cart-empty">
                <div class="cart-illustration">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
                <h2 class="cart-empty-title">Keranjang Belanja Anda Kosong</h2>
                <p class="cart-empty-text">Belum ada produk yang Anda tambahkan ke keranjang.</p>
                <a href="${document.querySelector('a[href*="products.index"]')?.href || '/products'}" class="cart-shop-btn">
                    <i data-feather="shopping-bag"></i>
                    Belanja Sekarang
                </a>
            </div>
        `;

                cartContent.classList.remove('fade-out');

                // Initialize feather icons
                if (window.feather) {
                    feather.replace();
                }

                // Update cart state
                cartState.items = [];
                cartState.updateCartCounter();
                cartState.saveToLocalStorage();
            }, 300);
        }

        /**
         * Calculate cart totals
         */
        function calculateTotals() {
            let subtotal = 0;
            let discount = 0;

            // Get all cart items from the DOM
            const cartItems = document.querySelectorAll('[data-item-row]');

            cartItems.forEach(item => {
                const unitPrice = parseFloat(item.getAttribute('data-unit-price')) || 0;
                const discountedPrice = parseFloat(item.getAttribute('data-discounted-price')) || unitPrice;
                const quantityInput = item.querySelector('.cart-quantity-input');
                const quantity = parseInt(quantityInput?.value || 1);

                subtotal += unitPrice * quantity;
                discount += (unitPrice - discountedPrice) * quantity;
            });

            const shipping = cartState.shippingCost || 0;
            const total = subtotal - discount + shipping;

            // Update UI
            const subtotalEl = document.getElementById('cart-subtotal');
            const discountEl = document.getElementById('cart-discount');
            const shippingEl = document.getElementById('cart-shipping');
            const totalEl = document.getElementById('cart-total');

            if (subtotalEl) subtotalEl.textContent = `Rp ${formatCurrency(subtotal)}`;
            if (discountEl) discountEl.textContent = `-Rp ${formatCurrency(discount)}`;
            if (shippingEl && !shippingEl.getAttribute('data-value')) {
                shippingEl.textContent = `Rp ${formatCurrency(shipping)}`;
            }
            if (totalEl) totalEl.textContent = `Rp ${formatCurrency(total)}`;

            // Show/hide discount row
            const discountRow = document.getElementById('cart-discount-row');
            if (discountRow) {
                discountRow.style.display = discount > 0 ? '' : 'none';
            }
        }

        /**
         * Function to update a specific item's quantity and subtotal in the DOM
         * @param {number} itemId - ID of the cart item
         * @param {number} newQuantity - New quantity value
         * @returns {number} - Previous quantity
         */
        function updateItemDisplay(itemId, newQuantity) {
            // Find all DOM elements for this item (desktop and mobile views)
            const itemElements = document.querySelectorAll(`[data-item-row="${itemId}"]`);
            if (itemElements.length === 0) return 1;

            // Find this item in cart state
            let oldQuantity = 1;
            for (let i = 0; i < cartState.items.length; i++) {
                if (cartState.items[i].id === itemId) {
                    oldQuantity = cartState.items[i].quantity;
                    cartState.items[i].quantity = newQuantity;
                    break;
                }
            }

            // Get unit price and discounted price
            const unitPrice = parseFloat(itemElements[0].getAttribute('data-unit-price')) || 0;
            const discountedPrice = parseFloat(itemElements[0].getAttribute('data-discounted-price')) || unitPrice;

            // Calculate new total
            const itemTotal = discountedPrice * newQuantity;

            // Update quantity inputs
            itemElements.forEach(element => {
                const quantityInput = element.querySelector('.cart-quantity-input');
                if (quantityInput) quantityInput.value = newQuantity;

                // Update total display
                const totalElement = element.querySelector('.item-total, .mobile-item-total');
                if (totalElement) {
                    totalElement.textContent = `Rp ${formatCurrency(itemTotal)}`;
                    totalElement.classList.add('flash-update');
                    setTimeout(() => totalElement.classList.remove('flash-update'), 500);
                }
            });

            // Recalculate cart totals
            calculateTotals();

            return oldQuantity;
        }

        /**
         * Client-side only quantity update function
         * @param {number} itemId - ID of the cart item
         * @param {number} newQuantity - New quantity to set
         */
        function updateItemQuantity(itemId, newQuantity) {
            // Get all elements with this item ID
            const itemElements = document.querySelectorAll(`[data-item-row="${itemId}"]`);
            if (itemElements.length === 0) return;

            // Validate the new quantity
            if (isNaN(newQuantity) || newQuantity < 1) {
                newQuantity = 1;
            }

            // Maximum quantity check
            const quantityField = itemElements[0].querySelector('.cart-quantity-input');
            const maxStock = parseInt(quantityField?.getAttribute('max')) || 100;
            if (newQuantity > maxStock) {
                newQuantity = maxStock;
                toastSystem.warning('Perhatian', 'Jumlah melebihi stok yang tersedia');
            }

            // Convert itemId to integer
            itemId = parseInt(itemId);

            // Check if there's already a pending update for this item
            if (cartState.pendingQuantityUpdates[itemId]) {
                // If we have a pending update, just store this new request
                cartState.pendingQuantityUpdates[itemId].nextQuantity = newQuantity;
                return;
            }

            // Store this update as pending
            cartState.pendingQuantityUpdates[itemId] = {
                nextQuantity: null
            };

            // Show updating state
            cartState.updating = true;
            const quantityContainers = document.querySelectorAll(
                `.cart-quantity[data-item-id="${itemId}"], .mobile-cart-item-quantity[data-item-id="${itemId}"]`);
            quantityContainers.forEach(container => {
                container.classList.add('updating');
            });

            // Get the current value to restore in case of failure
            const oldQuantity = updateItemDisplay(itemId, newQuantity);

            // Update cart state
            cartState.updateQuantity(itemId, newQuantity);

            // Simulate server delay for better UX
            setTimeout(() => {
                // Flash the total price to show it's been updated
                const cartTotal = document.getElementById('cart-total');
                if (cartTotal) {
                    cartTotal.classList.add('flash-update');
                    setTimeout(() => cartTotal.classList.remove('flash-update'), 500);
                }

                // Recalculate totals
                calculateTotals();

                // Remove updating state
                quantityContainers.forEach(container => {
                    container.classList.remove('updating');
                });

                // Check for next pending update
                const nextQuantity = cartState.pendingQuantityUpdates[itemId]?.nextQuantity;
                delete cartState.pendingQuantityUpdates[itemId];

                if (nextQuantity !== null) {
                    // Process the next update after a small delay
                    setTimeout(() => updateItemQuantity(itemId, nextQuantity), 200);
                } else if (Object.keys(cartState.pendingQuantityUpdates).length === 0) {
                    cartState.updating = false;
                }
            }, 300);
        }

        /**
         * Redirect to checkout with all pending changes
         */
        function redirectToCheckout() {
            // Show loading overlay
            loadingOverlay.show();

            // Create form with all pending changes
            const form = createSyncForm();

            // Add a redirect field
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect';
            redirectInput.value = 'checkout';
            form.appendChild(redirectInput);

            // Submit form to sync and redirect
            form.submit();
        }

        /**
         * Function to save selected shipping method (client-side only)
         * @param {string} methodCode - Shipping method code
         */
        function saveShippingMethod(methodCode) {
            // Store in cart state
            cartState.shippingMethod = methodCode;
            cartState.saveToLocalStorage();

            // Update hidden input
            const shippingMethodInput = document.getElementById('shipping_method_input');
            if (shippingMethodInput) {
                shippingMethodInput.value = methodCode;
            }

            console.log('Shipping method saved locally:', methodCode);
        }

        /**
         * Function to save selected payment method (client-side only)
         * @param {string} methodCode - Payment method code
         */
        function savePaymentMethod(methodCode) {
            // Store in cart state
            cartState.paymentMethod = methodCode;
            cartState.saveToLocalStorage();

            // Update hidden input
            const paymentMethodInput = document.getElementById('payment_method_input');
            if (paymentMethodInput) {
                paymentMethodInput.value = methodCode;
            } else {
                // Create input if it doesn't exist
                const input = document.createElement('input');
                input.type = 'hidden';
                input.id = 'payment_method_input';
                input.name = 'payment_method';
                input.value = methodCode;

                // Add to checkout form or body
                const checkoutLink = document.getElementById('checkout-link');
                if (checkoutLink && checkoutLink.closest('div')) {
                    checkoutLink.closest('div').appendChild(input);
                } else {
                    document.body.appendChild(input);
                }
            }

            console.log('Payment method saved locally:', methodCode);
        }


        /**
         * Enhanced checkout handler to validate and redirect
         */
        function setupCheckoutHandler() {
            const checkoutLink = document.getElementById('checkout-link');
            if (checkoutLink) {
                checkoutLink.addEventListener('click', function(e) {
                    // Prevent default link behavior
                    e.preventDefault();

                    // Ensure shipping method is selected
                    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
                    if (!shippingMethod) {
                        toastSystem.error('Perhatian', 'Silakan pilih metode pengiriman terlebih dahulu');
                        return false;
                    }

                    // Ensure payment method is selected
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (!paymentMethod) {
                        toastSystem.error('Perhatian', 'Silakan pilih metode pembayaran terlebih dahulu');
                        return false;
                    }

                    // Validate cart updates not in progress
                    if (Object.keys(cartState.pendingQuantityUpdates).length > 0 || cartState.updating) {
                        toastSystem.error('Perhatian', 'Sedang mengupdate keranjang, mohon tunggu sebentar');
                        return false;
                    }

                    // Store selected methods in cartState
                    cartState.shippingMethod = shippingMethod.value;
                    cartState.paymentMethod = paymentMethod.value;
                    cartState.saveToLocalStorage();

                    // If we have pending changes, use form submission to sync
                    if (cartState.pendingChanges.length > 0) {
                        redirectToCheckout();
                    } else {
                        // If no pending changes, redirect directly to checkout
                        window.location.href = checkoutLink.getAttribute('href');
                        loadingOverlay.show();
                    }
                });
            }
        }


        /**
         * Setup form submission handlers with improved error handling
         */
        function setupFormSubmissions() {
            // Handle promo code application
            const promoForm = document.getElementById('promo-apply-form');
            const applyPromoButton = document.getElementById('apply-promo-btn');

            if (promoForm && applyPromoButton) {
                promoForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Disable button to prevent multiple submissions
                    applyPromoButton.disabled = true;
                    applyPromoButton.textContent = 'Memproses...';

                    const formData = new FormData(this);
                    const promoCode = formData.get('promo_code');

                    if (!promoCode) {
                        toastSystem.error('Error', 'Masukkan kode promo terlebih dahulu');
                        applyPromoButton.disabled = false;
                        applyPromoButton.textContent = 'Terapkan';
                        return;
                    }

                    loadingOverlay.show();

                    // Use fetch API for better error handling
                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCSRFToken(),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Show success toast
                                toastSystem.success('Berhasil', 'Kode promo berhasil diterapkan');

                                // Reload page to reflect promo after a slight delay
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Gagal menerapkan kode promo');
                            }
                        })
                        .catch(error => {
                            console.error('Error applying promo code:', error);
                            toastSystem.error('Gagal', error.message ||
                                'Kode promo tidak valid atau tidak dapat diterapkan');
                        })
                        .finally(() => {
                            loadingOverlay.hide();
                            applyPromoButton.disabled = false;
                            applyPromoButton.textContent = 'Terapkan';
                        });
                });
            }

            // Handle promo code removal
            const promoRemoveBtn = document.getElementById('remove-promo-btn');
            const promoRemoveForm = document.getElementById('promo-remove-form');

            if (promoRemoveBtn && promoRemoveForm) {
                promoRemoveBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Disable button to prevent multiple clicks
                    promoRemoveBtn.disabled = true;

                    loadingOverlay.show();

                    // Use fetch API for better error handling
                    fetch(promoRemoveForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCSRFToken(),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _token: getCSRFToken()
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Show success toast
                                toastSystem.success('Berhasil', 'Kode promo berhasil dihapus');

                                // Reload page to reflect changes after a slight delay
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Gagal menghapus kode promo');
                            }
                        })
                        .catch(error => {
                            console.error('Error removing promo code:', error);
                            toastSystem.error('Gagal', error.message ||
                                'Gagal menghapus kode promo. Silakan coba lagi.');
                            promoRemoveBtn.disabled = false;
                        })
                        .finally(() => {
                            loadingOverlay.hide();
                        });
                });
            }

            // Checkout link validation
            const checkoutLink = document.getElementById('checkout-link');
            if (checkoutLink) {
                checkoutLink.addEventListener('click', function(e) {
                    // Validasi metode pengiriman tidak dipilih
                    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
                    if (!shippingMethod) {
                        e.preventDefault();
                        toastSystem.error('Perhatian', 'Silakan pilih metode pengiriman terlebih dahulu');
                        return false;
                    }

                    // Validasi metode pembayaran tidak dipilih
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (!paymentMethod) {
                        e.preventDefault();
                        toastSystem.error('Perhatian', 'Silakan pilih metode pembayaran terlebih dahulu');
                        return false;
                    }

                    // Validasi update keranjang masih dalam proses
                    if (Object.keys(cartState.pendingQuantityUpdates).length > 0 || cartState.updating) {
                        e.preventDefault();
                        toastSystem.error('Perhatian', 'Sedang mengupdate keranjang, mohon tunggu sebentar');
                        return false;
                    }

                    // Semua validasi passed, lanjut ke checkout
                    loadingOverlay.show();
                    return true;
                });
            }
        }

        /**
         * Initialize quantity handlers with debouncing for better performance
         */
        function setupQuantityHandlers() {
            // Debounce function to limit how often a function can fire
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Decrease quantity buttons
            document.querySelectorAll('.quantity-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (cartState.updating) return;

                    const container = this.closest('.cart-quantity, .mobile-cart-item-quantity');
                    if (!container) return;

                    const itemId = parseInt(container.getAttribute('data-item-id'));
                    const input = container.querySelector('.cart-quantity-input');
                    if (!input) return;

                    const currentValue = parseInt(input.value);

                    if (currentValue > 1) {
                        const newQuantity = currentValue - 1;
                        input.value = newQuantity; // Update input immediately for better UX

                        // Debounce the actual update to reduce rapid-fire API calls
                        debounce(() => updateItemQuantity(itemId, newQuantity), 300)();
                    }
                });
            });

            // Increase quantity buttons
            document.querySelectorAll('.quantity-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (cartState.updating) return;

                    const container = this.closest('.cart-quantity, .mobile-cart-item-quantity');
                    if (!container) return;

                    const itemId = parseInt(container.getAttribute('data-item-id'));
                    const input = container.querySelector('.cart-quantity-input');
                    if (!input) return;

                    const currentValue = parseInt(input.value);
                    const maxValue = parseInt(input.getAttribute('max') || '100');

                    if (currentValue < maxValue) {
                        const newQuantity = currentValue + 1;
                        input.value = newQuantity; // Update input immediately for better UX

                        // Debounce the actual update to reduce rapid-fire API calls
                        debounce(() => updateItemQuantity(itemId, newQuantity), 300)();
                    }
                });
            });

            // Manual quantity input
            document.querySelectorAll('.cart-quantity-input').forEach(input => {
                // Use input event for better responsiveness
                const debouncedUpdate = debounce(function() {
                    if (cartState.updating) return;

                    const itemId = parseInt(this.getAttribute('data-item-id'));
                    if (itemId) {
                        const newQuantity = parseInt(this.value) || 1;
                        updateItemQuantity(itemId, newQuantity);
                    }
                }, 500);

                input.addEventListener('input', debouncedUpdate);
                input.addEventListener('change', debouncedUpdate);
            });
        }

        /**
         * Set up shipping calculation with OpenStreetMap API instead of Google Maps
         * This makes it compatible with create.blade.php approach
         */
        window.initCartShippingCalc = function() {
            const userAddress =
                `{{ $primaryAddress->full_address ?? '' }}, {{ $primaryAddress->city ?? '' }}, {{ $primaryAddress->zip_code ?? '' }}`;
            const shippingEstimateText = document.getElementById('shippingEstimateText');
            const radios = document.querySelectorAll('.shipping-method-radio');
            const shippingMethodInput = document.getElementById('shipping_method_input');
            const mapContainer = document.getElementById('map-container');
            let userCoordinates = {
                lat: {{ $primaryAddress->latitude ?? '-6.4122794' }},
                lng: {{ $primaryAddress->longitude ?? '106.829692' }}
            };
            let tokoCoordinates = {
                lat: -6.4122794,
                lng: 106.829692
            }; // Koordinat Azka Garden

            // Initialize map if container exists
            let map, marker, storeMarker;

            function initMap() {
                if (!mapContainer) return;

                try {
                    // Create map using Leaflet instead of Google Maps
                    if (!window.L) {
                        // Load Leaflet if not available
                        loadLeaflet();
                        return;
                    }

                    // Initialize map
                    map = L.map(mapContainer).setView([userCoordinates.lat, userCoordinates.lng], 13);

                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Add markers
                    marker = L.marker([userCoordinates.lat, userCoordinates.lng], {
                        icon: createCustomIcon('blue', 'Alamat Pengiriman')
                    }).addTo(map);

                    storeMarker = L.marker([tokoCoordinates.lat, tokoCoordinates.lng], {
                        icon: createCustomIcon('green', 'Azka Garden')
                    }).addTo(map);

                    // Create bounds to fit both markers
                    const bounds = L.latLngBounds([
                        [userCoordinates.lat, userCoordinates.lng],
                        [tokoCoordinates.lat, tokoCoordinates.lng]
                    ]);

                    map.fitBounds(bounds, {
                        padding: [30, 30]
                    });

                    // Calculate distance and update shipping cost
                    calculateDistance(userCoordinates, tokoCoordinates);

                } catch (error) {
                    console.error('Error initializing map:', error);
                    if (mapContainer) {
                        mapContainer.style.display = 'none';
                    }
                }
            }

            function createCustomIcon(color, title) {
                return L.divIcon({
                    className: 'custom-map-marker',
                    html: `<div style="background-color: ${color === 'blue' ? '#3b82f6' : '#16a34a'};
                     width: 25px; height: 25px; border-radius: 50%;
                     border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.3);"
                     title="${title}"></div>`,
                    iconSize: [25, 25],
                    iconAnchor: [12, 12]
                });
            }

            function loadLeaflet() {
                // Load Leaflet CSS
                const linkEl = document.createElement('link');
                linkEl.rel = 'stylesheet';
                linkEl.href = 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.css';
                document.head.appendChild(linkEl);

                // Load Leaflet JS
                const scriptEl = document.createElement('script');
                scriptEl.src = 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js';
                scriptEl.onload = initMap;
                document.head.appendChild(scriptEl);
            }

            function calculateDistance(origin, destination) {
                // Calculate distance using Haversine formula
                function haversine(lat1, lon1, lat2, lon2) {
                    // Convert degrees to radians
                    lat1 = lat1 * Math.PI / 180;
                    lon1 = lon1 * Math.PI / 180;
                    lat2 = lat2 * Math.PI / 180;
                    lon2 = lon2 * Math.PI / 180;

                    // Haversine formula
                    const dlon = lon2 - lon1;
                    const dlat = lat2 - lat1;
                    const a = Math.sin(dlat / 2) ** 2 + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dlon / 2) ** 2;
                    const c = 2 * Math.asin(Math.sqrt(a));
                    const r = 6371; // Radius of earth in kilometers

                    return c * r;
                }

                try {
                    const distance = haversine(
                        origin.lat,
                        origin.lng,
                        destination.lat,
                        destination.lng
                    );

                    const distanceKm = distance.toFixed(2);
                    let selectedShipping = document.querySelector('.shipping-method-radio:checked')?.value ||
                        'KURIR_TOKO';

                    updateShippingEstimate(selectedShipping, distanceKm);

                } catch (error) {
                    console.error('Error calculating distance:', error);
                    updateShippingEstimate(document.querySelector('.shipping-method-radio:checked')?.value ||
                        'KURIR_TOKO');
                }
            }

            /**
             * Update shipping estimation display based on shipping method
             */
            function updateShippingEstimate(selectedShipping, distance = null) {
                // Update hidden input and global state
                const shippingMethodInput = document.getElementById('shipping_method_input');
                if (shippingMethodInput) {
                    shippingMethodInput.value = selectedShipping;
                }
                cartState.shippingMethod = selectedShipping;

                // Update visual selection
                document.querySelectorAll('.shipping-method').forEach(method => {
                    method.classList.remove('selected');
                });

                const selectedMethod = document.querySelector(`.shipping-method input[value="${selectedShipping}"]`);
                if (selectedMethod) {
                    selectedMethod.closest('.shipping-method').classList.add('selected');
                }

                // Save to local storage instead of server
                cartState.shippingMethod = selectedShipping;
                cartState.saveToLocalStorage();

                // Get shipping cost estimate text
                const shippingEstimateText = document.getElementById('shippingEstimateText');
                if (!shippingEstimateText) return;

                if (selectedShipping === 'KURIR_TOKO') {
                    shippingEstimateText.innerHTML = 'Menghitung ongkir...';

                    let ongkir = 15000; // default
                    let label = '5-10km';

                    if (distance) {
                        if (distance > 10) {
                            ongkir = 20000;
                            label = '&gt;10km';
                        } else if (distance > 5) {
                            ongkir = 15000;
                            label = '5-10km';
                        } else {
                            ongkir = 10000;
                            label = '&lt;5km';
                        }

                        // Store shipping cost in global state
                        cartState.shippingCost = ongkir;

                        // Update shipping cost in totals
                        const shippingEl = document.getElementById('cart-shipping');
                        if (shippingEl) {
                            shippingEl.textContent = `Rp ${formatCurrency(ongkir)}`;
                            shippingEl.setAttribute('data-value', ongkir);
                        }

                        // Recalculate total with shipping
                        calculateTotals();

                        shippingEstimateText.innerHTML =
                            `<b>Kurir Toko</b> | Jarak ±${distance} km<br>Zona ${label}, Estimasi Ongkir: <b>Rp ${formatCurrency(ongkir)}</b>`;
                    } else {
                        // If calculation failed
                        cartState.shippingCost = 15000; // Default value
                        const shippingEl = document.getElementById('cart-shipping');
                        if (shippingEl) {
                            shippingEl.textContent = `Rp ${formatCurrency(15000)}`;
                            shippingEl.setAttribute('data-value', '15000');
                        }
                        calculateTotals();
                        shippingEstimateText.innerHTML =
                            '<b>Kurir Toko</b> | Estimasi Ongkir: <b>Rp 10.000 - Rp 20.000</b> (sesuai jarak)';
                    }
                } else if (selectedShipping === 'AMBIL_SENDIRI') {
                    cartState.shippingCost = 0;
                    // Update shipping cost in totals
                    const shippingEl = document.getElementById('cart-shipping');
                    if (shippingEl) {
                        shippingEl.textContent = `Rp 0`;
                        shippingEl.setAttribute('data-value', '0');
                    }
                    // Recalculate total without shipping
                    calculateTotals();
                    shippingEstimateText.innerHTML =
                        `<b>Ambil Sendiri di Toko</b> | <a href="https://www.openstreetmap.org/?mlat=-6.4122794&mlon=106.829692&zoom=16" target="_blank" class="underline text-emerald-700">Lihat Lokasi Toko</a><br>Bebas Ongkir!`;
                } else if (selectedShipping === 'GOSEND') {
                    cartState.shippingCost = 25000;
                    // Update shipping cost in totals
                    const shippingEl = document.getElementById('cart-shipping');
                    if (shippingEl) {
                        shippingEl.textContent = `Rp ${formatCurrency(25000)}`;
                        shippingEl.setAttribute('data-value', '25000');
                    }
                    // Recalculate total with shipping
                    calculateTotals();
                    shippingEstimateText.innerHTML = `<b>GoSend Sameday</b> | Estimasi aplikasi Rp15.000-30.000`;
                } else if (selectedShipping === 'JNE') {
                    cartState.shippingCost = 12000;
                    // Update shipping cost in totals
                    const shippingEl = document.getElementById('cart-shipping');
                    if (shippingEl) {
                        shippingEl.textContent = `Rp ${formatCurrency(12000)}`;
                        shippingEl.setAttribute('data-value', '12000');
                    }
                    // Recalculate total with shipping
                    calculateTotals();
                    shippingEstimateText.innerHTML = `<b>JNE REG</b> | Estimasi 8.000-20.000/kg`;
                } else if (selectedShipping === 'JNT') {
                    cartState.shippingCost = 14000;
                    // Update shipping cost in totals
                    const shippingEl = document.getElementById('cart-shipping');
                    if (shippingEl) {
                        shippingEl.textContent = `Rp ${formatCurrency(14000)}`;
                        shippingEl.setAttribute('data-value', '14000');
                    }
                    // Recalculate total with shipping
                    calculateTotals();
                    shippingEstimateText.innerHTML = `<b>J&T EZ</b> | Estimasi 10.000-22.000/kg`;
                } else if (selectedShipping === 'SICEPAT') {
                    cartState.shippingCost = 15000;
                    // Update shipping cost in totals
                    const shippingEl = document.getElementById('cart-shipping');
                    if (shippingEl) {
                        shippingEl.textContent = `Rp ${formatCurrency(15000)}`;
                        shippingEl.setAttribute('data-value', '15000');
                    }
                    // Recalculate total with shipping
                    calculateTotals();
                    shippingEstimateText.innerHTML = `<b>SiCepat BEST</b> | Estimasi 10.000-18.000/kg`;
                }
            }

            // Add event listeners to shipping method radios
            radios.forEach(function(radio) {
                if (radio) {
                    radio.addEventListener('change', function() {
                        updateShippingEstimate(this.value, distance);
                    });
                }
            });

            // Add event listeners to payment method radios
            document.querySelectorAll('.payment-method-radio').forEach(function(radio) {
                if (radio) {
                    radio.addEventListener('change', function() {
                        cartState.paymentMethod = this.value;

                        // Update visual selection
                        document.querySelectorAll('.payment-method').forEach(method => {
                            method.classList.remove('selected');
                        });
                        const parent = this.closest('.payment-method');
                        if (parent) {
                            parent.classList.add('selected');
                        }

                        savePaymentMethod(this.value);
                    });
                }
            });

            // Initialize map
            if (mapContainer && userAddress) {
                initMap();
            }

            // Run once on page load to initialize shipping estimate
            try {
                const selectedShipping = document.querySelector('.shipping-method-radio:checked')?.value ||
                    'KURIR_TOKO';
                updateShippingEstimate(selectedShipping);
            } catch (error) {
                console.error('Error initializing shipping estimate:', error);
                if (shippingEstimateText) {
                    shippingEstimateText.innerHTML =
                        'Terjadi kesalahan saat menghitung ongkir. Silakan refresh halaman.';
                }
            }
        };

        /**
         * Initialize everything when DOM is ready
         */
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Initialize systems
                toastSystem.init();
                confirmDialog.init();
                loadingOverlay.init();

                // Store cart items data for client-side calculations
                const items = [
                    @foreach ($cartItems as $item)
                        @php
                            $promo = $item->promo_code ?? $promo_code;
                            $promotion = $promo ? \App\Models\Promotion::where('promo_code', $promo)->first() : null;
                            $discount = 0;
                            $unit_price = $item->product->price ?? 0;
                            $qty = $item->quantity ?? 0;

                            if ($promotion) {
                                if ($promotion->discount_type === 'percent') {
                                    $percent = $promotion->discount_value ?: 10;
                                    $discount = round($unit_price * ($percent / 100));
                                } elseif ($promotion->discount_type === 'fixed') {
                                    $discount = min($promotion->discount_value ?: 0, $unit_price);
                                }
                            }

                            $discounted_price = max(0, $unit_price - $discount);
                        @endphp {
                            id: {{ $item->id }},
                            productId: {{ $item->product_id }},
                            quantity: {{ $qty }},
                            unitPrice: {{ $unit_price }},
                            discountedPrice: {{ $discounted_price }},
                            discount: {{ $discount }},
                            maxStock: {{ $item->product->stock ?? 100 }},
                        },
                    @endforeach
                ];

                // Initialize cart state
                cartState.init(items);

                // Clear pending changes on fresh page load
                cartState.pendingChanges = [];
                cartState.saveToLocalStorage();

                // Set up event listeners for cart actions

                // Delete buttons
                document.querySelectorAll('.cart-remove').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const itemId = this.getAttribute('data-item-id');
                        if (itemId) {
                            handleDeleteItem(parseInt(itemId));
                        }
                    });
                });

                // Set up quantity handlers
                setupQuantityHandlers();

                // Setup form submissions
                setupFormSubmissions();

                // Initialize shipping method selection behavior
                document.querySelectorAll('.shipping-method').forEach(method => {
                    method.addEventListener('click', function() {
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio && !radio.checked) {
                            radio.checked = true;

                            // Trigger the change event manually
                            const event = new Event('change', {
                                bubbles: true
                            });
                            radio.dispatchEvent(event);
                        }
                    });
                });

                // Initialize payment method selection behavior
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.addEventListener('click', function() {
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio && !radio.checked) {
                            radio.checked = true;

                            // Trigger the change event manually
                            const event = new Event('change', {
                                bubbles: true
                            });
                            radio.dispatchEvent(event);
                        }
                    });
                });

                // Add hidden payment method input if it doesn't exist
                if (!document.getElementById('payment_method_input')) {
                    const paymentMethodInput = document.createElement('input');
                    paymentMethodInput.type = 'hidden';
                    paymentMethodInput.id = 'payment_method_input';
                    paymentMethodInput.name = 'payment_method';

                    // Set initial value if a payment method is already selected
                    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')
                        ?.value;
                    if (selectedPaymentMethod) {
                        paymentMethodInput.value = selectedPaymentMethod;
                        cartState.paymentMethod = selectedPaymentMethod;
                        cartState.saveToLocalStorage();
                    }

                    // Append to checkout link container
                    const checkoutLink = document.getElementById('checkout-link');
                    if (checkoutLink) {
                        checkoutLink.closest('div').appendChild(paymentMethodInput);
                    } else {
                        document.body.appendChild(paymentMethodInput);
                    }
                }

                // Set up enhanced checkout handler to avoid AJAX errors
                setupCheckoutHandler();

                // If we have user address coordinates, initialize shipping calculator
                if (document.getElementById('map-container')) {
                    // Check if we need to load an external mapping library
                    if (typeof L === 'undefined') {
                        // If Leaflet isn't loaded, we'll load it and then initialize
                        window.initCartShippingCalc();
                    } else {
                        // Leaflet is already loaded
                        window.initCartShippingCalc();
                    }
                } else {
                    // No map needed, just initialize shipping calculator
                    window.initCartShippingCalc();
                }

            } catch (error) {
                console.error('Error in cart initialization:', error);
                toastSystem.error('Error',
                    'Terjadi kesalahan saat menginisialisasi keranjang. Silakan refresh halaman.');
            }

            // Current timestamp for tracking
            console.log('Current Date and Time (UTC): 2025-07-30 15:28:24');
            console.log('Current User: redeemself');
        });

        // Add error handling for AJAX requests
        window.addEventListener('error', function(e) {
            console.error('Global error caught:', e.error || e.message);
            loadingOverlay.hideAll();

            // If there's an active spinner for quantity updates, remove it
            document.querySelectorAll('.updating').forEach(el => {
                el.classList.remove('updating');
            });

            cartState.updating = false;
            cartState.pendingQuantityUpdates = {}; // Clear pending updates on global error
        });

        // Confirm before leaving if cart has items and updates are pending
        window.addEventListener('beforeunload', function(e) {
            if ((cartState.updating || Object.keys(cartState.pendingQuantityUpdates).length > 0) && cartState.items
                .length > 0) {
                // Show confirmation dialog if we're in the middle of updating
                const message = 'Perubahan mungkin belum tersimpan. Yakin ingin meninggalkan halaman?';
                e.returnValue = message;
                return message;
            }
        });

        // Display the current date and time in the format used by the system
        console.log('Current date and time: 2025-07-30 14:57:56');
        console.log('Current user: marseltriwanto');
    </script>
@endsection
