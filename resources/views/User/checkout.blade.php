@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <div class="container max-w-2xl py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-green-600">Halaman Checkout</h1>

        <div class="p-6 mb-6 bg-white rounded-lg shadow">
            <h2 class="mb-2 text-xl font-semibold">Ringkasan Belanja</h2>
            <table class="w-full mb-2">
                <thead>
                    <tr class="text-sm text-gray-500 border-b">
                        <th class="py-1 text-left">Produk</th>
                        <th class="py-1 text-center">Qty</th>
                        <th class="py-1 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($cartItems as $item)
                        @php
                            $subtotal = $item->price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <tr class="border-b">
                            <td class="py-1">
                                <span class="font-medium">{{ $item->product->name ?? '-' }}</span>
                            </td>
                            <td class="py-1 text-center">{{ $item->quantity }}</td>
                            <td class="py-1 text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="pt-2 font-bold text-right">Total:</td>
                        <td class="pt-2 font-bold text-right text-green-700">Rp{{ number_format($grandTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-6 mb-6 bg-white rounded-lg shadow">
            <div class="mb-4">
                <h2 class="mb-1 text-lg font-semibold">Metode Pengiriman</h2>
                @php
                    $selectedShipping = collect($shippingOptions)->firstWhere('id', request('shipping_method'));
                @endphp
                <div class="text-gray-800">
                    <b>
                        {{ $selectedShipping ? $selectedShipping['name'] : '-' }}
                    </b>
                    @if ($selectedShipping)
                        <span class="text-gray-600"> (Rp{{ number_format($selectedShipping['price'], 0, ',', '.') }})</span>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="mb-1 text-lg font-semibold">Metode Pembayaran</h2>
                @php
                    $selectedPayment = $paymentMethods->firstWhere('code', request('payment_method'));
                @endphp
                <div class="text-gray-800">
                    <b>
                        {{ $selectedPayment ? $selectedPayment->name : '-' }}
                    </b>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <form action="{{ route('user.payment.index') }}" method="POST">
                @csrf
                {{-- Kirim semua data penting ke halaman payment --}}
                <input type="hidden" name="shipping_method" value="{{ request('shipping_method') }}">
                <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                {{-- Kirim data produk satu per satu --}}
                @foreach ($cartItems as $item)
                    <input type="hidden" name="cart_items[{{ $loop->index }}][product_id]"
                        value="{{ $item->product->id }}">
                    <input type="hidden" name="cart_items[{{ $loop->index }}][product_name]"
                        value="{{ $item->product->name }}">
                    <input type="hidden" name="cart_items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}">
                    <input type="hidden" name="cart_items[{{ $loop->index }}][price]" value="{{ $item->price }}">
                @endforeach
                <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
                <button type="submit" class="px-6 py-2 text-white transition bg-blue-600 rounded shadow hover:bg-blue-700">
                    Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    </div>
@endsection
