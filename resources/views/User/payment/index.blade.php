@extends('layouts.app')

@section('title', 'Pembayaran - Azka Garden')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
    :root {
        --primary: #166534;
        --primary-light: #16a34a;
        --primary-dark: #14532d;
        --primary-bg: #f0fdf4;
        --success: #16a34a;
        --success-bg: #dcfce7;
        --white: #ffffff;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
    }

    .payment-container {
        max-width: 800px;
        margin: 2rem auto 3rem;
        padding: 0 1rem;
    }

    .payment-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .payment-header h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .payment-panel {
        background: var(--white);
        border-radius: 1rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .payment-panel-header {
        background: var(--primary-bg);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .payment-panel-header h2 {
        color: var(--primary);
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .payment-panel-body {
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        color: var(--gray-600);
        border-bottom: 1px solid var(--gray-100);
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row.summary-total {
        font-weight: 700;
        color: var(--gray-800);
        font-size: 1.25rem;
        border-top: 2px solid var(--primary);
        padding-top: 1rem;
        margin-top: 0.5rem;
    }

    .payment-info {
        background: var(--success-bg);
        border: 1px solid #a7f3d0;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
    }

    .payment-info h3 {
        color: var(--success);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button {
        background: var(--gray-100);
        color: var(--gray-600);
        border: 1px solid var(--gray-200);
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        margin-right: 1rem;
    }

    .back-button:hover {
        background: var(--gray-200);
        color: var(--gray-600);
        text-decoration: none;
    }

    .pay-button {
        background: var(--primary);
        color: var(--white);
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pay-button:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .order-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-image {
        width: 50px;
        height: 50px;
        border-radius: 0.5rem;
        object-fit: cover;
        margin-right: 1rem;
    }

    .order-item-details {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .order-item-price {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .order-item-total {
        font-weight: 600;
        color: var(--primary);
    }

    @media (max-width: 768px) {
        .payment-container {
            margin-top: 1rem;
            padding: 0 0.5rem;
        }

        .payment-header h1 {
            font-size: 1.75rem;
        }

        .payment-panel-body {
            padding: 1rem;
        }
    }
</style>

<div class="payment-container">
    <div class="payment-header">
        <h1>Pembayaran</h1>
        <p>Konfirmasi pembayaran untuk pesanan Anda</p>
    </div>

    <!-- Order Summary -->
    <div class="payment-panel">
        <div class="payment-panel-header">
            <h2>
                <i data-feather="shopping-bag"></i>
                Ringkasan Pesanan ({{ $cartItems->count() }} item)
            </h2>
        </div>
        <div class="payment-panel-body">
            @foreach($cartItems as $item)
                <div class="order-item">
                    <img src="{{ asset($item->product->image_url ?? 'images/no-image.png') }}" alt="{{ $item->product->name }}" class="order-item-image">
                    <div class="order-item-details">
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-item-price">Qty: {{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="order-item-total">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-panel">
        <div class="payment-panel-header">
            <h2>
                <i data-feather="credit-card"></i>
                Detail Pembayaran
            </h2>
        </div>
        <div class="payment-panel-body">
            <div class="summary-row">
                <span>Subtotal ({{ $cartItems->count() }} item)</span>
                <span>Rp {{ number_format($grand_total + $total_discount, 0, ',', '.') }}</span>
            </div>
            @if($total_discount > 0)
                <div class="summary-row">
                    <span>Diskon</span>
                    <span style="color: var(--success);">-Rp {{ number_format($total_discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span>Pajak (10%)</span>
                <span>Rp {{ number_format($tax_total, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Ongkir ({{ $checkoutData['shipping_method'] ?? 'Unknown' }})</span>
                <span>Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($final_total, 0, ',', '.') }}</span>
            </div>

            <!-- Payment Method Info -->
            <div class="payment-info">
                <h3>
                    <i data-feather="info"></i>
                    Metode Pembayaran
                </h3>
                <p><strong>{{ $checkoutData['payment_method'] ?? 'Tidak dipilih' }}</strong></p>
                <p>Silakan lanjutkan pembayaran dengan metode yang telah Anda pilih.</p>
            </div>

            <!-- Action Buttons -->
            <form action="{{ route('user.payment.process') }}" method="POST" style="margin-top: 1.5rem;">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $checkoutData['payment_method'] }}">
                <input type="hidden" name="final_amount" value="{{ $final_total }}">
                
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <a href="{{ route('checkout.index') }}" class="back-button">
                        <i data-feather="arrow-left"></i>
                        Kembali ke Checkout
                    </a>
                    
                    <button type="submit" class="pay-button">
                        <i data-feather="check-circle"></i>
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection