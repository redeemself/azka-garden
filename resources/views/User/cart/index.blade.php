@extends('layouts.app')

@section('title', 'Keranjang Belanja | Azka Garden')

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

        /* Modern Selection Cards for Mobile - Enhanced by gerrymulyadi709 at 2025-08-02 05:05:35 */
        .selection-container {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .selection-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .selection-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .selection-card {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-height: 70px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .selection-card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        }

        .selection-card.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--primary-bg) 0%, rgba(220, 252, 231, 0.8) 100%);
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .selection-card.selected::before {
            content: '✓';
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: var(--primary);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 2;
        }

        .selection-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .selection-card.selected .selection-icon {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .selection-info {
            flex: 1;
            min-width: 0;
        }

        .selection-title {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .selection-card.selected .selection-title {
            color: var(--primary-dark);
        }

        .selection-description {
            color: var(--gray-600);
            font-size: 0.8rem;
            line-height: 1.3;
            margin-bottom: 0.25rem;
        }

        .selection-card.selected .selection-description {
            color: var(--gray-700);
        }

        .selection-price {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--gray-800);
        }

        .selection-card.selected .selection-price {
            color: var(--primary-dark);
        }

        .selection-price.free {
            color: var(--success);
        }

        .selection-price.with-fee {
            color: var(--warning);
        }

        /* Desktop Dropdown (fallback for larger screens) */
        .custom-dropdown-container {
            position: relative;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .custom-dropdown {
            width: 100%;
            padding: 1rem 3.5rem 1rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            color: var(--gray-800);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            font-weight: 500;
        }

        .custom-dropdown:hover {
            border-color: var(--primary-light);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
            transform: translateY(-1px);
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        }

        .custom-dropdown:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1), 0 4px 12px rgba(22, 163, 74, 0.15);
            transform: translateY(-1px);
            background: white;
        }

        .dropdown-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .dropdown-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        /* Enhanced Responsive Design */
        @media (max-width: 768px) {
            .cart-container {
                padding: 1rem 0.75rem;
                max-width: 100vw;
                box-sizing: border-box;
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
                padding: 1rem 0.75rem;
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
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
                box-sizing: border-box;
            }

            .toast {
                right: 1rem;
                left: 1rem;
                max-width: none;
                position: fixed;
                top: 1rem;
                z-index: 9999;
            }

            /* Hide dropdown on mobile */
            .custom-dropdown-container {
                display: none !important;
            }

            /* Show selection cards on mobile */
            .selection-container {
                display: block;
            }

            /* Enhanced mobile selection cards */
            .selection-card {
                padding: 0.75rem;
                min-height: 60px;
                gap: 0.75rem;
            }

            .selection-icon {
                width: 35px;
                height: 35px;
                font-size: 1.25rem;
            }

            .selection-title {
                font-size: 0.9rem;
            }

            .selection-description {
                font-size: 0.75rem;
            }

            .selection-price {
                font-size: 0.85rem;
            }

            /* Optimize selection grid for mobile */
            .selection-grid {
                gap: 0.5rem;
            }
        }

        @media (min-width: 769px) {

            /* Hide selection cards on desktop */
            .selection-container {
                display: none;
            }

            /* Show dropdown on desktop */
            .custom-dropdown-container {
                display: block;
            }
        }

        /* Ultra Small Mobile Devices */
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
                padding: 0.75rem 0.5rem;
            }

            .cart-container {
                padding: 0.75rem 0.5rem;
            }

            .selection-card {
                padding: 0.625rem;
                min-height: 55px;
                gap: 0.625rem;
            }

            .selection-icon {
                width: 30px;
                height: 30px;
                font-size: 1.125rem;
            }

            .selection-title {
                font-size: 0.85rem;
            }

            .selection-description {
                font-size: 0.7rem;
            }

            .selection-price {
                font-size: 0.8rem;
            }
        }

        /* Animation keyframes for mobile cards */
        @keyframes cardSelect {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardDeselect {
            0% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .selection-card.selected {
            animation: cardSelect 0.3s ease-out;
        }

        .selection-card:not(.selected) {
            animation: cardDeselect 0.2s ease-out;
        }

        /* Touch feedback for mobile */
        @media (max-width: 768px) {
            .selection-card:active {
                transform: scale(0.98);
                transition: transform 0.1s ease;
            }

            .selection-card:active .selection-icon {
                transform: scale(0.9);
            }
        }
    </style>
@endpush

@section('content')
    {{-- Updated: 2025-08-02 05:05:35 by gerrymulyadi709 --}}

    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Keranjang Belanja</h1>
            <p class="cart-subtitle">Kelola produk yang akan Anda beli dengan mudah</p>
        </div>

        @if (isset($error))
            <div class="cart-card">
                <div class="p-6">
                    <div class="px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded">
                        {{ $error }}
                    </div>
                </div>
            </div>
        @endif

        @if (isset($invalidItems) && $invalidItems->count() > 0)
            <div class="cart-card">
                <div class="p-6 border-b bg-yellow-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-medium text-yellow-800">
                            Beberapa item dalam keranjang tidak tersedia atau stok habis.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($cartItems) && $cartItems->count() > 0)
            <div class="cart-grid">
                <!-- Products Section -->
                <div class="cart-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            🛒 Daftar Produk
                        </h2>
                        <span class="px-3 py-1 ml-auto text-sm text-green-800 bg-green-100 rounded-full">
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

                                // Get product image with fallback
                                $productImage = null;
                                if ($item->product) {
                                    if ($item->product->product_images && $item->product->product_images->count() > 0) {
                                        $primaryImage = $item->product->product_images->where('is_primary', 1)->first();
                                        if ($primaryImage) {
                                            $productImage = asset($primaryImage->image_url);
                                        } else {
                                            $productImage = asset($item->product->product_images->first()->image_url);
                                        }
                                    } elseif ($item->product->image_url) {
                                        $productImage = asset($item->product->image_url);
                                    }
                                }
                            @endphp
                            <div class="product-item {{ !$item->hasValidProduct() || !$item->hasValidStock() ? 'bg-gray-50 opacity-75' : '' }}"
                                id="cart-item-{{ $item->id }}">
                                <div class="product-image-container">
                                    @if ($productImage)
                                        <img src="{{ $productImage }}" alt="{{ $item->product_name }}" class="product-image"
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
                                    <div class="product-name">{{ $item->product_name }}</div>
                                    @if ($item->product && $item->product->category)
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    @endif

                                    @if (!$item->hasValidProduct())
                                        <p class="text-sm font-medium text-red-600">Produk tidak tersedia</p>
                                    @elseif(!$item->hasValidStock())
                                        <p class="text-sm font-medium text-orange-600">Stok tidak mencukupi (tersedia:
                                            {{ $item->product->stock }})</p>
                                    @endif

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
                                            {{ $item->quantity <= 1 || !$item->canDecrement() ? 'disabled' : '' }}>
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
                                            {{ !$item->canIncrement() || ($item->product && $item->product->stock <= $item->quantity) ? 'disabled' : '' }}>
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
                                <div class="promo-description">{{ Session::get('promo_description', 'Promo aktif') }}
                                </div>
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

                <!-- Enhanced Summary Section -->
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

                    <!-- Subtotal Awal -->
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

                    <!-- Biaya Pembayaran -->
                    <div class="summary-row" id="payment-fee-row" style="display: none;">
                        <span class="summary-label">💳 Biaya Pembayaran</span>
                        <span class="summary-value" id="payment-fee-display">Rp0</span>
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
                        $userAddresses = $userAddresses ?? collect();
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

                <!-- Enhanced Checkout Form with Mobile Card Selection -->
                <div class="cart-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            🚚 Pengiriman & Pembayaran
                        </h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">

                            @php
                                // Fallback data untuk shipping options jika kosong
                                $defaultShippingOptions = [
                                    [
                                        'id' => 'regular',
                                        'name' => 'Pengiriman Regular',
                                        'price' => 15000,
                                        'description' => 'Estimasi 2-3 hari kerja',
                                        'icon' => '🚚',
                                    ],
                                    [
                                        'id' => 'express',
                                        'name' => 'Pengiriman Express',
                                        'price' => 25000,
                                        'description' => 'Estimasi 1-2 hari kerja',
                                        'icon' => '⚡',
                                    ],
                                    [
                                        'id' => 'pickup',
                                        'name' => 'Ambil Sendiri',
                                        'price' => 0,
                                        'description' => 'Ambil di toko Azka Garden',
                                        'icon' => '🏪',
                                    ],
                                ];

                                // Fallback data untuk payment methods jika kosong
                                $defaultPaymentMethods = [
                                    [
                                        'id' => 'bank_transfer',
                                        'name' => 'Transfer Bank',
                                        'fee' => 0,
                                        'description' => 'BCA, BNI, BRI, Mandiri',
                                        'icon' => '🏦',
                                    ],
                                    [
                                        'id' => 'ewallet',
                                        'name' => 'E-Wallet',
                                        'fee' => 2500,
                                        'description' => 'GoPay, OVO, DANA',
                                        'icon' => '📱',
                                    ],
                                    [
                                        'id' => 'cod',
                                        'name' => 'Bayar di Tempat (COD)',
                                        'fee' => 5000,
                                        'description' => 'Bayar saat barang diterima',
                                        'icon' => '💵',
                                    ],
                                ];

                                // Gunakan data dari controller jika ada, jika tidak gunakan default
                                $finalShippingOptions =
                                    isset($shippingOptions) && count($shippingOptions) > 0
                                        ? $shippingOptions
                                        : $defaultShippingOptions;

                                $finalPaymentMethods =
                                    isset($paymentMethods) && count($paymentMethods) > 0
                                        ? $paymentMethods
                                        : $defaultPaymentMethods;
                            @endphp

                            <!-- Mobile Card Selection for Shipping Methods -->
                            <div class="selection-container">
                                <label class="selection-label">
                                    🚛 Metode Pengiriman
                                </label>
                                <div class="selection-grid" id="shipping-selection">
                                    @foreach ($finalShippingOptions as $option)
                                        <div class="selection-card" data-type="shipping"
                                            data-value="{{ $option['id'] }}" data-price="{{ $option['price'] }}"
                                            data-description="{{ $option['description'] ?? '' }}"
                                            onclick="selectOption(this)">
                                            <div class="selection-icon">{{ $option['icon'] ?? '🚚' }}</div>
                                            <div class="selection-info">
                                                <div class="selection-title">{{ $option['name'] }}</div>
                                                <div class="selection-description">{{ $option['description'] ?? '' }}
                                                </div>
                                                <div class="selection-price {{ $option['price'] == 0 ? 'free' : '' }}">
                                                    @if ($option['price'] > 0)
                                                        Rp{{ number_format($option['price'], 0, ',', '.') }}
                                                    @else
                                                        GRATIS
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Desktop Dropdown for Shipping Methods -->
                            <div class="custom-dropdown-container">
                                <label class="dropdown-label">
                                    🚛 Metode Pengiriman
                                </label>
                                <select name="shipping_method" id="shipping_method" class="custom-dropdown" required>
                                    <option value="" disabled selected>Pilih metode pengiriman...</option>
                                    @foreach ($finalShippingOptions as $option)
                                        <option value="{{ $option['id'] }}" data-price="{{ $option['price'] }}"
                                            data-description="{{ $option['description'] ?? '' }}">
                                            {{ $option['icon'] ?? '🚚' }} {{ $option['name'] }} -
                                            @if ($option['price'] > 0)
                                                Rp{{ number_format($option['price'], 0, ',', '.') }}
                                            @else
                                                GRATIS
                                            @endif
                                            @if (!empty($option['description']))
                                                | {{ Str::limit($option['description'], 30) }}
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

                            <!-- Mobile Card Selection for Payment Methods -->
                            <div class="selection-container">
                                <label class="selection-label">
                                    💳 Metode Pembayaran
                                </label>
                                <div class="selection-grid" id="payment-selection">
                                    @foreach ($finalPaymentMethods as $method)
                                        <div class="selection-card" data-type="payment" data-value="{{ $method['id'] }}"
                                            data-fee="{{ $method['fee'] ?? 0 }}"
                                            data-description="{{ $method['description'] ?? '' }}"
                                            onclick="selectOption(this)">
                                            <div class="selection-icon">{{ $method['icon'] ?? '💳' }}</div>
                                            <div class="selection-info">
                                                <div class="selection-title">{{ $method['name'] }}</div>
                                                <div class="selection-description">{{ $method['description'] ?? '' }}
                                                </div>
                                                <div
                                                    class="selection-price {{ isset($method['fee']) && $method['fee'] > 0 ? 'with-fee' : 'free' }}">
                                                    @if (isset($method['fee']) && $method['fee'] > 0)
                                                        +Rp{{ number_format($method['fee'], 0, ',', '.') }}
                                                    @else
                                                        GRATIS
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Desktop Dropdown for Payment Methods -->
                            <div class="custom-dropdown-container">
                                <label class="dropdown-label">
                                    💳 Metode Pembayaran
                                </label>
                                <select name="payment_method" id="payment_method" class="custom-dropdown" required>
                                    <option value="" disabled selected>Pilih metode pembayaran...</option>
                                    @foreach ($finalPaymentMethods as $method)
                                        <option value="{{ $method['id'] }}" data-fee="{{ $method['fee'] ?? 0 }}"
                                            data-description="{{ $method['description'] ?? '' }}">
                                            {{ $method['icon'] ?? '💳' }} {{ $method['name'] }}
                                            @if (isset($method['fee']) && $method['fee'] > 0)
                                                (+Rp{{ number_format($method['fee'], 0, ',', '.') }})
                                            @endif
                                            @if (!empty($method['description']))
                                                | {{ Str::limit($method['description'], 25) }}
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

                            <!-- Debug Information (hanya tampil jika dalam development mode) -->
                            @if (config('app.debug'))
                                <div
                                    style="background: #f3f4f6; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.8rem; color: #6b7280;">
                                    <strong>Debug Info:</strong><br>
                                    Shipping Options: {{ count($finalShippingOptions) }} items<br>
                                    Payment Methods: {{ count($finalPaymentMethods) }} items<br>
                                    Controller Data Available:
                                    {{ isset($shippingOptions) && isset($paymentMethods) ? 'Yes' : 'No' }}<br>
                                    Mobile Card Selection: Active<br>
                                    Updated: 2025-08-02 05:09:20 UTC by gerrymulyadi709
                                </div>
                            @endif

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="subtotal" id="subtotal-input" value="{{ $subtotalFinal }}">
                            <input type="hidden" name="discount" id="discount-input" value="{{ $totalAllDiscounts }}">
                            <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">
                            <input type="hidden" name="payment_fee" id="payment-fee-input" value="0">
                            <input type="hidden" name="tax" id="tax-input" value="{{ $tax }}">
                            <input type="hidden" name="grand_total" id="grand-total-input"
                                value="{{ $subtotalFinal + $tax }}">
                            <input type="hidden" name="selected_address_id" id="selected-address-input" value="">
                            <input type="hidden" name="shipping_method" id="shipping-method-hidden" value="">
                            <input type="hidden" name="payment_method" id="payment-method-hidden" value="">

                            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="flex: 1;">
                                    ⬅️ Lanjut Belanja
                                </a>
                                <button type="submit" class="btn btn-primary btn-large" style="flex: 2;"
                                    id="checkout-button">
                                    ➡️ Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
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
        @endif
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
        /**
         * Enhanced Cart Management System with Mobile Card Selection
         * Updated: 2025-08-02 05:09:20 UTC by gerrymulyadi709
         */
        document.addEventListener('DOMContentLoaded', function() {
            console.log(
                'Enhanced cart page with mobile card selection initialized - 2025-08-02 05:09:20 UTC by gerrymulyadi709'
            );

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // Price calculation elements
            const shippingDropdown = document.getElementById('shipping_method');
            const paymentDropdown = document.getElementById('payment_method');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const paymentFeeDisplay = document.getElementById('payment-fee-display');
            const paymentFeeRow = document.getElementById('payment-fee-row');
            const grandTotalDisplay = document.getElementById('grand-total');
            const subtotalInput = document.getElementById('subtotal-input');
            const discountInput = document.getElementById('discount-input');
            const shippingCostInput = document.getElementById('shipping-cost-input');
            const paymentFeeInput = document.getElementById('payment-fee-input');
            const taxInput = document.getElementById('tax-input');
            const grandTotalInput = document.getElementById('grand-total-input');
            const selectedAddressInput = document.getElementById('selected-address-input');
            const shippingMethodHidden = document.getElementById('shipping-method-hidden');
            const paymentMethodHidden = document.getElementById('payment-method-hidden');

            // Initial values
            const subtotal = parseFloat(subtotalInput?.value) || 0;
            const discount = parseFloat(discountInput?.value) || 0;
            const tax = parseFloat(taxInput?.value) || 0;
            let shippingCost = 0;
            let paymentFee = 0;
            let selectedAddress = null;
            let selectedShippingMethod = null;
            let selectedPaymentMethod = null;

            // Enhanced Mobile Card Selection - Added by gerrymulyadi709 at 2025-08-02 05:09:20
            window.selectOption = function(cardElement) {
                const type = cardElement.dataset.type; // 'shipping' or 'payment'
                const value = cardElement.dataset.value;
                const containerSelector = type === 'shipping' ? '#shipping-selection' : '#payment-selection';

                // Remove selection from all cards in the same group
                document.querySelectorAll(`${containerSelector} .selection-card`).forEach(card => {
                    card.classList.remove('selected');
                });

                // Select current card
                cardElement.classList.add('selected');

                // Update values based on type
                if (type === 'shipping') {
                    const price = parseFloat(cardElement.dataset.price) || 0;
                    const description = cardElement.dataset.description || '';

                    shippingCost = price;
                    selectedShippingMethod = value;

                    // Update hidden input
                    if (shippingMethodHidden) {
                        shippingMethodHidden.value = value;
                    }

                    // Update dropdown if exists (for desktop)
                    if (shippingDropdown) {
                        shippingDropdown.value = value;
                        shippingDropdown.classList.add('selected');
                    }

                    // Show success message
                    const methodName = cardElement.querySelector('.selection-title').textContent;
                    showToast(`✅ ${methodName} dipilih`, 'success', 2000);

                    console.log('Shipping method selected via card:', {
                        method: value,
                        price: price,
                        description: description,
                        timestamp: '2025-08-02 05:09:20',
                        user: 'gerrymulyadi709'
                    });

                } else if (type === 'payment') {
                    const fee = parseFloat(cardElement.dataset.fee) || 0;
                    const description = cardElement.dataset.description || '';

                    paymentFee = fee;
                    selectedPaymentMethod = value;

                    // Update hidden input
                    if (paymentMethodHidden) {
                        paymentMethodHidden.value = value;
                    }

                    // Update dropdown if exists (for desktop)
                    if (paymentDropdown) {
                        paymentDropdown.value = value;
                        paymentDropdown.classList.add('selected');
                    }

                    // Show success message
                    const methodName = cardElement.querySelector('.selection-title').textContent;
                    showToast(`💳 ${methodName} dipilih`, 'success', 2000);

                    console.log('Payment method selected via card:', {
                        method: value,
                        fee: fee,
                        description: description,
                        timestamp: '2025-08-02 05:09:20',
                        user: 'gerrymulyadi709'
                    });
                }

                // Recalculate totals
                calculateTotals();

                // Add visual feedback
                cardElement.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    cardElement.style.transform = '';
                }, 200);
            };

            // Format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            // Calculate totals
            function calculateTotals() {
                if (shippingCostDisplay && grandTotalDisplay) {
                    // Update shipping cost display
                    shippingCostDisplay.textContent = 'Rp' + formatCurrency(shippingCost);
                    if (shippingCostInput) shippingCostInput.value = shippingCost;

                    // Update payment fee display
                    if (paymentFee > 0) {
                        paymentFeeRow.style.display = 'flex';
                        paymentFeeDisplay.textContent = 'Rp' + formatCurrency(paymentFee);
                    } else {
                        paymentFeeRow.style.display = 'none';
                    }
                    if (paymentFeeInput) paymentFeeInput.value = paymentFee;

                    // Calculate grand total
                    const grandTotal = subtotal + shippingCost + paymentFee + tax;
                    grandTotalDisplay.textContent = 'Rp' + formatCurrency(grandTotal);
                    if (grandTotalInput) grandTotalInput.value = grandTotal;

                    console.log('Total calculation with mobile card selection:', {
                        subtotalAfterAllDiscounts: subtotal,
                        totalDiscounts: discount,
                        shipping: shippingCost,
                        paymentFee: paymentFee,
                        tax: tax,
                        grandTotal: grandTotal,
                        selectedShipping: selectedShippingMethod,
                        selectedPayment: selectedPaymentMethod,
                        isMobile: window.innerWidth <= 768,
                        timestamp: '2025-08-02 05:09:20',
                        user: 'gerrymulyadi709'
                    });
                }
            }

            // Enhanced dropdown event handlers (for desktop)
            if (shippingDropdown) {
                shippingDropdown.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    shippingCost = parseFloat(selectedOption.dataset.price) || 0;
                    selectedShippingMethod = selectedOption.value;

                    // Update hidden input
                    if (shippingMethodHidden) {
                        shippingMethodHidden.value = selectedOption.value;
                    }

                    // Update corresponding mobile card if exists
                    const mobileCard = document.querySelector(
                        `[data-type="shipping"][data-value="${selectedOption.value}"]`);
                    if (mobileCard) {
                        selectOption(mobileCard);
                        return; // Prevent double calculation
                    }

                    calculateTotals();
                    this.classList.add('selected');

                    const methodName = selectedOption.text.split(' - ')[0];
                    showToast(`✅ ${methodName}`, 'success', 2000);
                });
            }

            if (paymentDropdown) {
                paymentDropdown.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    paymentFee = parseFloat(selectedOption.dataset.fee) || 0;
                    selectedPaymentMethod = selectedOption.value;

                    // Update hidden input
                    if (paymentMethodHidden) {
                        paymentMethodHidden.value = selectedOption.value;
                    }

                    // Update corresponding mobile card if exists
                    const mobileCard = document.querySelector(
                        `[data-type="payment"][data-value="${selectedOption.value}"]`);
                    if (mobileCard) {
                        selectOption(mobileCard);
                        return; // Prevent double calculation
                    }

                    calculateTotals();
                    this.classList.add('selected');

                    const methodName = selectedOption.text.split(' | ')[0];
                    showToast(`💳 ${methodName}`, 'success', 2000);
                });
            }

            // Enhanced address selection
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
                    id: addressId,
                    latitude: addressElement.dataset.latitude,
                    longitude: addressElement.dataset.longitude
                };

                showToast('📍 Alamat pengiriman dipilih', 'success');
            };

            // Initialize calculations
            calculateTotals();

            // Auto-select primary address if available
            const primaryAddress = document.querySelector('.address-card.selected');
            if (primaryAddress) {
                selectAddress(primaryAddress);
            }

            // Auto-select first options on page load for desktop
            setTimeout(() => {
                // Only auto-select for desktop
                if (window.innerWidth > 768) {
                    if (shippingDropdown && shippingDropdown.options.length > 1) {
                        shippingDropdown.selectedIndex = 1;
                        shippingDropdown.dispatchEvent(new Event('change'));
                    }

                    if (paymentDropdown && paymentDropdown.options.length > 1) {
                        paymentDropdown.selectedIndex = 1;
                        paymentDropdown.dispatchEvent(new Event('change'));
                    }
                } else {
                    // For mobile, auto-select first cards
                    const firstShippingCard = document.querySelector('#shipping-selection .selection-card');
                    const firstPaymentCard = document.querySelector('#payment-selection .selection-card');

                    if (firstShippingCard) {
                        selectOption(firstShippingCard);
                    }

                    if (firstPaymentCard) {
                        selectOption(firstPaymentCard);
                    }
                }
            }, 100);

            // Enhanced toast notification
            function showToast(message, type = 'success', duration = 3000) {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.textContent = message;
                    toast.className = `toast ${type}`;
                    toast.classList.add('show');

                    // Mobile-specific positioning
                    if (window.innerWidth <= 768) {
                        toast.style.position = 'fixed';
                        toast.style.top = '1rem';
                        toast.style.left = '1rem';
                        toast.style.right = '1rem';
                        toast.style.maxWidth = 'none';
                        toast.style.zIndex = '9999';
                    }

                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, duration);
                }
            }

            // Cart operations (existing functions)
            function createFetchOptions(method, data = null) {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                };

                if (data) {
                    options.body = JSON.stringify(data);
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

            // Update cart quantity
            function updateCartQuantity(button, action) {
                const itemId = button.dataset.itemId;
                const row = button.closest('.product-item');
                const quantityDisplay = row?.querySelector('.quantity-display');
                const decrementBtn = row?.querySelector('.decrement-btn');
                const incrementBtn = row?.querySelector('.increment-btn');
                const currentQuantity = parseInt(quantityDisplay?.textContent) || 1;

                let newQuantity;
                if (action === 'increment') {
                    newQuantity = currentQuantity + 1;
                } else {
                    newQuantity = Math.max(1, currentQuantity - 1);
                }

                // Show loading
                const originalContent = button.innerHTML;
                button.innerHTML = '<div class="loading"></div>';
                button.disabled = true;

                const updateData = {
                    quantity: newQuantity
                };
                safeFetch(button.dataset.url, createFetchOptions('PUT', updateData))
                    .then(data => {
                        if (data.success) {
                            if (quantityDisplay) {
                                quantityDisplay.textContent = data.quantity || newQuantity;
                            }

                            if (decrementBtn) {
                                decrementBtn.disabled = (data.quantity || newQuantity) <= 1;
                            }
                            if (incrementBtn) {
                                incrementBtn.disabled = data.stock_limit_reached || false;
                            }

                            showToast(
                                `Jumlah produk berhasil ${action === 'increment' ? 'ditambah' : 'dikurangi'}`
                            );

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
                        // Check shipping method (both dropdown and hidden input)
                        const hasShippingMethod = (shippingDropdown?.value) || (shippingMethodHidden?.value);
                        if (!hasShippingMethod) {
                            e.preventDefault();
                            showToast('Silakan pilih metode pengiriman terlebih dahulu', 'warning');
                            return false;
                        }

                        // Check payment method (both dropdown and hidden input)
                        const hasPaymentMethod = (paymentDropdown?.value) || (paymentMethodHidden?.value);
                        if (!hasPaymentMethod) {
                            e.preventDefault();
                            showToast('Silakan pilih metode pembayaran terlebih dahulu', 'warning');
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
                    const submitBtn = this.querySelector('#checkout-button');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<div class="loading"></div> Memproses...';
                    }

                    return true;
                });
        }

        // Enhanced promo code handling
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

                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<div class="loading"></div> Memproses...';
                }
            });
        }

        // Auto-save cart state
        function saveCartState() {
            const cartState = {
                subtotal: subtotal,
                discount: discount,
                tax: tax,
                shippingCost: shippingCost,
                paymentFee: paymentFee,
                selectedAddress: selectedAddress,
                selectedShippingMethod: selectedShippingMethod,
                selectedPaymentMethod: selectedPaymentMethod,
                timestamp: new Date().toISOString(),
                itemCount: document.querySelectorAll('.product-item').length,
                isMobile: window.innerWidth <= 768,
                selectionMethod: window.innerWidth <= 768 ? 'mobile-cards' : 'desktop-dropdown',
                updatedBy: 'gerrymulyadi709',
                version: '2025-08-02 05:09:20'
            };
            localStorage.setItem('azka_cart_state', JSON.stringify(cartState));
        }

        // Save cart state on page load
        saveCartState();

        // Monitor for cart changes
        const cartObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' &&
                    (mutation.target.classList.contains('product-item') ||
                        mutation.target.closest('.product-item'))) {
                    saveCartState();
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

        // Enhanced accessibility
        document.querySelectorAll('.selection-card').forEach(card => {
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.setAttribute('aria-pressed', 'false');

            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectOption(this);
                }
            });

            // Update aria-pressed when selected
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const isSelected = card.classList.contains('selected');
                        card.setAttribute('aria-pressed', isSelected.toString());
                    }
                });
            });

            observer.observe(card, {
                attributes: true
            });
        });

        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log('Cart page performance with mobile card selection:', {
                        loadTime: perfData.loadEventEnd - perfData.loadEventStart,
                        domContentLoaded: perfData.domContentLoadedEventEnd - perfData
                            .domContentLoadedEventStart,
                        calculationTime: performance.now(),
                        isMobile: window.innerWidth <= 768,
                        selectionMethod: window.innerWidth <= 768 ? 'mobile-cards' :
                            'desktop-dropdown',
                        cardsCount: document.querySelectorAll('.selection-card').length,
                        timestamp: '2025-08-02 05:09:20',
                        user: 'gerrymulyadi709'
                    });
                }, 0);
            });
        }

        // Enhanced error logging
        window.addEventListener('error', function(e) {
            console.error('Cart page error with mobile card selection:', {
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                isMobile: window.innerWidth <= 768,
                timestamp: '2025-08-02 05:09:20',
                user: 'gerrymulyadi709'
            });
        });

        console.log(
            'Enhanced cart functionality with mobile card selection loaded successfully - 2025-08-02 05:09:20 UTC by gerrymulyadi709'
        );
        });
    </script>
@endsection
