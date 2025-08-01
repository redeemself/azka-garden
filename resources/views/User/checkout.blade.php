@extends('layouts.app')

@section('title', 'Checkout - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        /* Modern Checkout Styles - Updated: 2025-07-31 19:40:29 by DenuJanuari */
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
            --blue-500: #3b82f6;
            --yellow-500: #eab308;
            --success-green: #10b981;
            --info-blue: #0ea5e9;
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

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            position: relative;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-500);
            font-size: 1.125rem;
            font-weight: 500;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            font-size: 0.875rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-500);
        }

        .breadcrumb-item.active {
            color: var(--primary-green);
            font-weight: 600;
        }

        .breadcrumb-separator {
            color: var(--gray-300);
        }

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-100);
            overflow: hidden;
            margin-bottom: 2rem;
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

        .card-body {
            padding: 2rem;
        }

        /* Data Display */
        .data-section {
            margin-bottom: 2rem;
        }

        .data-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-grid {
            display: grid;
            gap: 1rem;
        }

        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--gray-50);
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }

        .data-item:hover {
            background: white;
            border-color: var(--primary-green);
            transform: translateY(-1px);
        }

        .data-label {
            font-weight: 500;
            color: var(--gray-700);
        }

        .data-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        /* JSON Display */
        .json-display {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1.5rem;
            border-radius: 12px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
            border: 2px solid #334155;
            position: relative;
        }

        .json-display::before {
            content: 'JSON';
            position: absolute;
            top: 8px;
            right: 12px;
            background: var(--success-green);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* Action Buttons */
        .btn-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), #22c55e);
            color: white;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-1px);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .status-success {
            background: var(--light-green);
            color: var(--primary-green);
        }

        .status-info {
            background: #dbeafe;
            color: var(--info-blue);
        }

        /* LocalStorage Data Styling */
        .localStorage-section {
            border: 2px dashed var(--info-blue);
            border-radius: 12px;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            margin-top: 2rem;
        }

        .localStorage-section h3 {
            color: var(--info-blue);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-container {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .breadcrumb {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title">Checkout</h1>
            <p class="page-subtitle">Halaman untuk memproses data keranjang belanja</p>
        </div>

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <div class="breadcrumb-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6h12z" />
                </svg>
                Keranjang
            </div>
            <span class="breadcrumb-separator">→</span>
            <div class="breadcrumb-item active">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4"></path>
                    <path d="M21 12c.552 0 1-.449 1-1V6a2 2 0 00-2-2H4a2 2 0 00-2 2v6c0 .551.448 1 1 1"></path>
                </svg>
                Checkout
            </div>
        </div>

        {{-- Cart Data Display --}}
        <div class="modern-card">
            <div class="card-header">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                <h2>Data Keranjang Belanja</h2>
            </div>
            <div class="card-body" id="main-card-body">
                {{-- Status Indicator --}}
                @if (session('cart_data'))
                    <div class="status-badge status-success">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Data dari Session (AJAX)
                    </div>
                @elseif (request()->has('items'))
                    <div class="status-badge status-info">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        Data dari URL Parameters
                    </div>
                @endif

                {{-- Cart Items from Session/Database --}}
                @if (session('cart_data') || request()->has('items'))
                    <div class="data-section">
                        <h3>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Produk dalam Keranjang
                        </h3>

                        {{-- Display session data if available --}}
                        @if (session('cart_data'))
                            <div id="session-data" class="json-display">
                                {{ json_encode(session('cart_data'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
                        @endif

                        {{-- Display URL parameters if available --}}
                        @if (request()->all())
                            <div class="data-section" style="margin-top: 2rem;">
                                <h3>
                                    <svg width="20" height="20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                    </svg>
                                    Data yang Diterima via URL
                                </h3>
                                <div class="data-grid">
                                    @foreach (request()->all() as $key => $value)
                                        <div class="data-item">
                                            <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                            <span class="data-value">
                                                @if (is_array($value))
                                                    {{ json_encode($value) }}
                                                @else
                                                    {{ Str::limit($value, 100) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="empty-state" id="empty-state">
                        <div class="empty-icon">📋</div>
                        <h3>Tidak Ada Data Keranjang</h3>
                        <p>Belum ada data keranjang yang diterima. Silakan kembali ke halaman keranjang untuk melanjutkan.
                        </p>
                        <a href="{{ route('user.cart.index') }}" class="btn-modern btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 12H5m7-7l-7 7 7 7" />
                            </svg>
                            Kembali ke Keranjang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Debug Information --}}
        <div class="modern-card">
            <div class="card-header">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <h2>Informasi Debug</h2>
            </div>
            <div class="card-body">
                <div class="data-section">
                    <h3>Request Information</h3>
                    <div class="data-grid">
                        <div class="data-item">
                            <span class="data-label">Method</span>
                            <span class="data-value">{{ request()->method() }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">URL</span>
                            <span class="data-value">{{ request()->url() }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Route Name</span>
                            <span class="data-value">{{ request()->route()->getName() ?? 'N/A' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Timestamp</span>
                            <span class="data-value">{{ now()->format('Y-m-d H:i:s') }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">User</span>
                            <span class="data-value">{{ auth()->user()->name ?? 'Guest' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Updated By</span>
                            <span class="data-value">DenuJanuari</span>
                        </div>
                    </div>
                </div>

                {{-- All Request Data --}}
                @if (request()->all())
                    <div class="data-section">
                        <h3>All Request Data (JSON)</h3>
                        <div class="json-display">
                            {{ json_encode(request()->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
                    </div>
                @endif

                {{-- Session Data --}}
                <div class="data-section">
                    <h3>Session Data</h3>
                    <div class="json-display">
                        {{ json_encode(session()->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
            <a href="{{ route('user.cart.index') }}" class="btn-modern btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 12H5m7-7l-7 7 7 7" />
                </svg>
                Kembali ke Keranjang
            </a>
            <a href="{{ route('products.index') }}" class="btn-modern btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6h12z" />
                </svg>
                Lanjut Belanja
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            /**
             * Enhanced Checkout Page - LocalStorage Integration
             * Updated: 2025-07-31 19:40:29 by DenuJanuari
             */
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Checkout page loading... - 2025-07-31 19:40:29 by DenuJanuari');

                // Check for data in localStorage if session is empty
                const checkoutData = localStorage.getItem('checkout_data');
                const hasSessionData = document.querySelector('#session-data');
                const emptyState = document.querySelector('#empty-state');
                const mainCardBody = document.querySelector('#main-card-body');

                if (checkoutData && !hasSessionData && emptyState) {
                    try {
                        const data = JSON.parse(checkoutData);
                        console.log('Found checkout data in localStorage:', data);

                        // Hide empty state
                        emptyState.style.display = 'none';

                        // Create status badge for localStorage data
                        const statusBadge = document.createElement('div');
                        statusBadge.className = 'status-badge status-info';
                        statusBadge.innerHTML = `
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        Data dari Browser Storage
                    `;

                        // Create a display section for localStorage data
                        const localStorageSection = document.createElement('div');
                        localStorageSection.className = 'localStorage-section';
                        localStorageSection.innerHTML = `
                        <div class="data-section">
                            <h3>
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                Data dari Browser Storage (Fallback)
                            </h3>
                            <div class="json-display">${JSON.stringify(data, null, 2)}</div>
                        </div>
                    `;

                        // Add status badge and data section to main card body
                        mainCardBody.insertBefore(statusBadge, mainCardBody.firstChild);
                        mainCardBody.appendChild(localStorageSection);

                        // Display summary information
                        if (data.summary) {
                            const summarySection = document.createElement('div');
                            summarySection.className = 'data-section';
                            summarySection.innerHTML = `
                            <h3>
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Ringkasan Belanja
                            </h3>
                            <div class="data-grid">
                                <div class="data-item">
                                    <span class="data-label">Jumlah Item</span>
                                    <span class="data-value">${data.summary.itemCount || 0} produk</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Subtotal</span>
                                    <span class="data-value">Rp${new Intl.NumberFormat('id-ID').format(data.summary.subtotal || 0)}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Ongkos Kirim</span>
                                    <span class="data-value">Rp${new Intl.NumberFormat('id-ID').format(data.summary.shipping || 0)}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Pajak</span>
                                    <span class="data-value">Rp${new Intl.NumberFormat('id-ID').format(data.summary.tax || 0)}</span>
                                </div>
                                <div class="data-item" style="border: 2px solid var(--primary-green); background: var(--light-green);">
                                    <span class="data-label" style="font-weight: 700;">Total Pembayaran</span>
                                    <span class="data-value" style="color: var(--primary-green); font-weight: 800; font-size: 1.125rem;">Rp${new Intl.NumberFormat('id-ID').format(data.summary.total || 0)}</span>
                                </div>
                            </div>
                        `;
                            localStorageSection.appendChild(summarySection);
                        }

                        console.log('LocalStorage data successfully displayed');

                    } catch (e) {
                        console.error('Error parsing localStorage data:', e);

                        // Show error message
                        const errorSection = document.createElement('div');
                        errorSection.className = 'localStorage-section';
                        errorSection.style.borderColor = '#ef4444';
                        errorSection.style.background = '#fef2f2';
                        errorSection.innerHTML = `
                        <div class="data-section">
                            <h3 style="color: #ef4444;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Error Reading Browser Storage
                            </h3>
                            <p style="color: #7f1d1d;">Terjadi kesalahan saat membaca data dari browser storage.</p>
                        </div>
                    `;
                        mainCardBody.appendChild(errorSection);
                    }

                    // Clear localStorage after processing (success or error)
                    localStorage.removeItem('checkout_data');
                    console.log('LocalStorage checkout_data cleared');
                }

                // Log completion
                console.log('Checkout page loaded successfully - 2025-07-31 19:40:29 by DenuJanuari');
            });
        </script>
    @endpush
@endsection
