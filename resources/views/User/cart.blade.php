@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <h1 class="mb-4 text-3xl font-bold">Keranjang Belanja</h1>

        @if ($cartItems->isEmpty())
            <p class="text-gray-600">Keranjang Anda kosong. <a href="{{ route('products.index') }}"
                    class="text-green-600 underline">Mulai belanja</a>.</p>
        @else
            <table class="w-full mb-6 border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-center">Qty</th>
                        <th class="p-2 text-right">Harga</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr class="border-t">
                            <td class="flex items-center gap-2 p-2">
                                @if ($item->product && $item->product->image_url)
                                    <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                        class="object-cover w-12 h-12 rounded" alt="">
                                @endif
                                <span>{{ $item->product->name ?? $item->name }}</span>
                            </td>
                            <td class="p-2 text-center">{{ $item->quantity }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('checkout.index') }}" method="GET" class="space-y-4">
                <div>
                    <label for="shipping_method" class="block mb-1 font-medium">Metode Pengiriman</label>
                    <select name="shipping_method" id="shipping_method" class="w-full p-2 border rounded">
                        @foreach ($shippingOptions as $opt)
                            <option value="{{ $opt['id'] }}">
                                {{ $opt['name'] }} &mdash; Rp{{ number_format($opt['price'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block mb-1 font-medium">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="w-full p-2 border rounded">
                        @foreach ($paymentMethods as $pm)
                            <option value="{{ $pm->code }}">
                                {{ $pm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-4 py-2 text-white transition bg-green-600 rounded hover:bg-green-700">
                        Lanjut ke Checkout
                    </button>
                </div>
            </form>
        @endif
    </div>
    @endsection@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <h1 class="mb-4 text-3xl font-bold">Keranjang Belanja</h1>

        @if ($cartItems->isEmpty())
            <p class="text-gray-600">Keranjang Anda kosong. <a href="{{ route('products.index') }}"
                    class="text-green-600 underline">Mulai belanja</a>.</p>
        @else
            <table class="w-full mb-6 border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-center">Qty</th>
                        <th class="p-2 text-right">Harga</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr class="border-t">
                            <td class="flex items-center gap-2 p-2">
                                @if ($item->product && $item->product->image_url)
                                    <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                        class="object-cover w-12 h-12 rounded" alt="">
                                @endif
                                <span>{{ $item->product->name ?? $item->name }}</span>
                            </td>
                            <td class="p-2 text-center">{{ $item->quantity }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('checkout.index') }}" method="GET" class="space-y-4">
                <div>
                    <label for="shipping_method" class="block mb-1 font-medium">Metode Pengiriman</label>
                    <select name="shipping_method" id="shipping_method" class="w-full p-2 border rounded">
                        @foreach ($shippingOptions as $opt)
                            <option value="{{ $opt['id'] }}">
                                {{ $opt['name'] }} &mdash; Rp{{ number_format($opt['price'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block mb-1 font-medium">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="w-full p-2 border rounded">
                        @foreach ($paymentMethods as $pm)
                            <option value="{{ $pm->code }}">
                                {{ $pm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-4 py-2 text-white transition bg-green-600 rounded hover:bg-green-700">
                        Lanjut ke Checkout
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
