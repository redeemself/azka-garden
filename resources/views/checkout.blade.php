@extends('layouts.app')

@section('title', 'Checkout - Azka Garden')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        /* Basic Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #1f2937;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }

        .page-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2rem;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .checkout-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background-color: #f9fafb;
            padding: 1rem;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Order Items */
        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 1rem;
            border: 1px solid #e5e7eb;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #111827;
        }

        .item-price {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .item-quantity {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .item-total {
            font-weight: 700;
            color: #16a34a;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.15s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Summary Card */
        .summary-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 2rem;
        }

        .summary-header {
            background-color: #1f2937;
            color: #fff;
            padding: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-body {
            padding: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: #374151;
        }

        .summary-row.total {
            font-size: 1.25rem;
            font-weight: bold;
            padding-top: 0.75rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 0.75rem;
        }

        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .payment-option {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.15s;
            text-align: center;
        }

        .payment-option:hover {
            border-color: #16a34a;
        }

        .payment-option.selected {
            border-color: #16a34a;
            background-color: #f0fdf4;
        }

        .payment-option input {
            display: none;
        }

        .payment-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .payment-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .payment-desc {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Shipping Methods */
        .shipping-methods {
            margin-top: 1rem;
        }

        .shipping-option {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .shipping-option:hover {
            border-color: #16a34a;
        }

        .shipping-option.selected {
            border-color: #16a34a;
            background-color: #f0fdf4;
        }

        .shipping-option input {
            margin-right: 0.5rem;
        }

        .shipping-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shipping-details {
            flex: 1;
        }

        .shipping-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .shipping-desc {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .shipping-price {
            font-weight: 600;
            color: #16a34a;
        }

        /* Buttons */
        .btn-primary,
        .btn-secondary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.15s;
            border: none;
        }

        .btn-primary {
            background-color: #16a34a;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #14532d;
        }

        .btn-primary:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }

        .btn-secondary {
            background-color: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background-color: #ffedd5;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <h1 class="page-title">Checkout</h1>

        @if (session('success'))
            <div class="alert alert-success">
                <strong>✓</strong> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <strong>✗</strong> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>✗</strong> Terdapat kesalahan pada form:
                <ul style="margin: 0.5rem 0 0 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="checkout-grid">
                <!-- Main Checkout Form -->
                <div class="checkout-main">
                    <!-- Order Items -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Produk Pesanan ({{ $items->count() }} item)
                        </div>
                        <div class="card-body">
                            @foreach ($items as $item)
                                <div class="order-item">
                                    @if ($item->product && $item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                            alt="{{ $item->product->name ?? $item->name }}" class="item-image">
                                    @else
                                        <div class="item-image"
                                            style="background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2"
                                                    ry="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21,15 16,10 5,21" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->product->name ?? $item->name }}</div>
                                        <div class="item-price">Rp{{ number_format($item->price, 0, ',', '.') }}</div>
                                        <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="item-total">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="checkout-card" style="margin-top: 2rem;">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Alamat Pengiriman
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="receiver_name" class="form-input"
                                    value="{{ old('receiver_name', auth()->user()->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" name="receiver_phone" class="form-input"
                                    value="{{ old('receiver_phone', auth()->user()->phone) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="shipping_address" class="form-textarea" required>{{ old('shipping_address') }}</textarea>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Kota</label>
                                    <input type="text" name="city" class="form-input" value="{{ old('city') }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="postal_code" class="form-input"
                                        value="{{ old('postal_code') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="checkout-card" style="margin-top: 2rem;">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <path d="m16 8 4-4-4-4"></path>
                            </svg>
                            Metode Pengiriman
                        </div>
                        <div class="card-body">
                            <div class="shipping-methods">
                                <div class="shipping-option selected">
                                    <input type="radio" name="shipping_method" value="KURIR_TOKO_SEDANG" checked>
                                    <div class="shipping-info">
                                        <div class="shipping-details">
                                            <div class="shipping-name">Kurir Toko (Sedang)</div>
                                            <div class="shipping-desc">Estimasi 2-3 hari kerja</div>
                                        </div>
                                        <div class="shipping-price">Rp15.000</div>
                                    </div>
                                </div>
                                <div class="shipping-option">
                                    <input type="radio" name="shipping_method" value="KURIR_TOKO_CEPAT">
                                    <div class="shipping-info">
                                        <div class="shipping-details">
                                            <div class="shipping-name">Kurir Toko (Cepat)</div>
                                            <div class="shipping-desc">Estimasi 1-2 hari kerja</div>
                                        </div>
                                        <div class="shipping-price">Rp25.000</div>
                                    </div>
                                </div>
                                <div class="shipping-option">
                                    <input type="radio" name="shipping_method" value="AMBIL_SENDIRI">
                                    <div class="shipping-info">
                                        <div class="shipping-details">
                                            <div class="shipping-name">Ambil Sendiri</div>
                                            <div class="shipping-desc">Ambil di toko (Gratis)</div>
                                        </div>
                                        <div class="shipping-price">Gratis</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card" style="margin-top: 2rem;">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            Metode Pembayaran
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <label class="payment-option selected">
                                    <input type="radio" name="payment_method" value="COD" checked>
                                    <div class="payment-icon">💵</div>
                                    <div class="payment-name">Cash on Delivery</div>
                                    <div class="payment-desc">Bayar saat barang diterima</div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="QRIS">
                                    <div class="payment-icon">📱</div>
                                    <div class="payment-name">QRIS</div>
                                    <div class="payment-desc">Scan QR untuk bayar</div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="TRANSFER">
                                    <div class="payment-icon">🏦</div>
                                    <div class="payment-name">Transfer Bank</div>
                                    <div class="payment-desc">Transfer ke rekening toko</div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="EWALLET">
                                    <div class="payment-icon">📲</div>
                                    <div class="payment-name">E-Wallet</div>
                                    <div class="payment-desc">GoPay, OVO, DANA, dll</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-card" style="margin-top: 2rem;">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path>
                                <polygon points="18,2 22,6 12,16 8,16 8,12 18,2"></polygon>
                            </svg>
                            Catatan Pesanan
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Catatan untuk Kurir (Opsional)</label>
                                <textarea name="notes" class="form-textarea" placeholder="Contoh: Rumah cat biru, dekat masjid...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="checkout-sidebar">
                    <div class="summary-card">
                        <div class="summary-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c.552 0 1-.449 1-1V6a2 2 0 00-2-2H4a2 2 0 00-2 2v6c0 .551.448 1 1 1"></path>
                                <path d="M3 13h18"></path>
                            </svg>
                            Ringkasan Pesanan
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

                            <div class="summary-row">
                                <span>Subtotal ({{ $items->count() }} item)</span>
                                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            @if ($discount > 0)
                                <div class="summary-row">
                                    <span>Diskon</span>
                                    <span>-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="summary-row">
                                <span>Ongkos Kirim</span>
                                <span id="shipping-cost">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Pajak (10%)</span>
                                <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row total">
                                <span>Total</span>
                                <span id="final-total">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <!-- Hidden inputs for calculations -->
                            <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                            <input type="hidden" name="discount" value="{{ $discount }}">
                            <input type="hidden" name="shipping_fee" value="{{ $shippingFee }}"
                                id="shipping-fee-input">
                            <input type="hidden" name="tax" value="{{ $tax }}">
                            <input type="hidden" name="total" value="{{ $total }}" id="total-input">

                            <button type="submit" class="btn-primary" id="place-order-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 12l2 2 4-4"></path>
                                    <path d="M21 12c.552 0 1-.449 1-1V6a2 2 0 00-2-2H4a2 2 0 00-2 2v6c0 .551.448 1 1 1">
                                    </path>
                                </svg>
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle shipping method changes
                const shippingOptions = document.querySelectorAll('input[name="shipping_method"]');
                const shippingCostElement = document.getElementById('shipping-cost');
                const shippingFeeInput = document.getElementById('shipping-fee-input');
                const finalTotalElement = document.getElementById('final-total');
                const totalInput = document.getElementById('total-input');

                const shippingCosts = {
                    'KURIR_TOKO_SEDANG': 15000,
                    'KURIR_TOKO_CEPAT': 25000,
                    'AMBIL_SENDIRI': 0
                };

                shippingOptions.forEach(option => {
                    option.addEventListener('change', function() {
                        // Update UI
                        document.querySelectorAll('.shipping-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.closest('.shipping-option').classList.add('selected');

                        // Update costs
                        const newShippingCost = shippingCosts[this.value] || 15000;
                        const subtotal = {{ $subtotalAfterDiscount }};
                        const tax = {{ $tax }};
                        const newTotal = subtotal + tax + newShippingCost;

                        shippingCostElement.textContent = newShippingCost === 0 ? 'Gratis' :
                            'Rp' + new Intl.NumberFormat('id-ID').format(newShippingCost);
                        finalTotalElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(
                            newTotal);

                        shippingFeeInput.value = newShippingCost;
                        totalInput.value = newTotal;
                    });
                });

                // Handle payment method changes
                const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
                paymentOptions.forEach(option => {
                    option.addEventListener('change', function() {
                        document.querySelectorAll('.payment-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.closest('.payment-option').classList.add('selected');
                    });
                });

                // Handle form submission
                const checkoutForm = document.getElementById('checkout-form');
                const placeOrderBtn = document.getElementById('place-order-btn');

                checkoutForm.addEventListener('submit', function(e) {
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.innerHTML = `
                        <svg class="animate-spin" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12a9 9 0 11-6.219-8.56"></path>
                        </svg>
                        Memproses...
                    `;
                });
            });
        </script>
    @endpush
@endsection
