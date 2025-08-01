@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
    <div class="container max-w-2xl py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-blue-600">Halaman Pembayaran</h1>

        <div class="p-6 mb-6 bg-white rounded-lg shadow">
            <h2 class="mb-2 text-xl font-semibold">Ringkasan Order</h2>
            <ul>
                @foreach ($cartItems ?? [] as $item)
                    <li>
                        {{ $item['product_name'] ?? '-' }} x {{ $item['quantity'] ?? 0 }} =
                        Rp{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 0, ',', '.') }}
                    </li>
                @endforeach
            </ul>
            <p class="mt-2"><b>Ongkir:</b> {{ $shippingMethod }}</p>
            <p><b>Pembayaran:</b> {{ $paymentMethod }}</p>
            <p class="mt-2 text-lg font-bold">Total: Rp{{ number_format($grandTotal ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="text-right">
            <a href="{{ route('cart.index') }}" class="px-4 py-2 mr-2 bg-gray-200 rounded">Kembali ke Keranjang</a>
            <!-- Di sini bisa tambahkan tombol proses pembayaran -->
            <button class="px-6 py-2 text-white transition bg-green-600 rounded shadow hover:bg-green-700">
                Bayar Sekarang
            </button>
        </div>
    </div>
@endsection
