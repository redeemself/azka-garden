@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
    <div class="container max-w-2xl py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-blue-600">Detail Pesanan</h1>

        <div class="p-6 mb-6 bg-white border rounded-lg shadow">
            <div class="flex justify-between mb-3">
                <div>
                    <b>Kode Pesanan:</b> #{{ $order->id }}<br>
                    <b>Tanggal:</b> {{ $order->created_at->format('d-m-Y H:i') }}
                </div>
                <div>
                    <b>Status:</b> <span class="px-2 py-1 bg-gray-200 rounded">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
            <div class="mb-3">
                <b>Metode Pengiriman:</b> {{ $order->shipping_method }}<br>
                <b>Metode Pembayaran:</b> {{ $order->payment_method }}
            </div>
            <div class="mb-3">
                <b>Alamat Pengiriman:</b> {{ $order->shipping_address ?? '-' }}
            </div>
            <div class="mb-3">
                <b>Produk:</b>
                <ul class="ml-4 list-disc">
                    @foreach ($order->items as $item)
                        <li>
                            {{ $item->product_name }} x {{ $item->quantity }}
                            (Rp{{ number_format($item->price, 0, ',', '.') }})
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-2 text-lg font-bold text-right text-green-700">
                Total: Rp{{ number_format($order->total, 0, ',', '.') }}
            </div>
        </div>

        <a href="{{ route('user.orders.index') }}" class="inline-block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
            &larr; Kembali ke Daftar Pesanan
        </a>
    </div>
@endsection
