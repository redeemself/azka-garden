@extends('layouts.app')
@section('title', 'Daftar Pesanan')
@section('content')
    <div class="container max-w-3xl py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-blue-600">Daftar Pesanan Anda</h1>

        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('user.orders.history.index') }}"
                class="inline-block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Lihat Riwayat Pesanan
            </a>
        </div>

        @if (session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
            <div class="p-4 mb-6 bg-white border rounded-lg shadow">
                <div class="flex justify-between mb-2">
                    <div>
                        <b>Kode Pesanan:</b> #{{ $order->id }}<br>
                        <b>Tanggal:</b> {{ $order->created_at->format('d-m-Y H:i') }}
                    </div>
                    <div>
                        <b>Status:</b> <span class="px-2 py-1 bg-gray-200 rounded">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
                <div class="mb-2">
                    <b>Metode Pengiriman:</b> {{ $order->shipping_method }}<br>
                    <b>Metode Pembayaran:</b> {{ $order->payment_method }}
                </div>
                <div>
                    <b>Produk:</b>
                    <ul class="ml-4 list-disc">
                        @foreach ($order->items as $item)
                            <li>{{ $item->product_name }} x {{ $item->quantity }}
                                (Rp{{ number_format($item->price, 0, ',', '.') }})
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <div class="font-bold text-green-700">
                        Total: Rp{{ number_format($order->total, 0, ',', '.') }}
                    </div>
                    <div>
                        <a href="{{ route('user.orders.show', $order->id) }}"
                            class="inline-block px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-600">Belum ada pesanan.</div>
        @endforelse
    </div>
@endsection
