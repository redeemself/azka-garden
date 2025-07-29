@extends('layouts.app')

@section('content')
@php
    $promo_code = session('promo_code') ?? '';
@endphp
<div class="container px-2 mx-auto md:px-6 lg:px-8">

    {{-- Banner Promo --}}
    @if(isset($banners) && count($banners))
        <div class="mb-10">
            <!-- ... banner code sama seperti sebelumnya ... -->
        </div>
    @endif

    {{-- Promo Code Activation --}}
    <div class="mb-6">
        @if($promo_code)
            <div class="flex items-center justify-center gap-2 px-4 py-2 mb-4 font-bold text-center text-green-800 bg-green-100 rounded-lg">
                <span>Promo sudah aktif:</span>
                <span class="px-2 py-1 font-mono text-green-900 bg-green-200 rounded">{{ $promo_code }}</span>
            </div>
        @else
            <form method="POST" action="{{ route('promo.activate') }}" class="flex flex-col items-center gap-2 md:flex-row">
                @csrf
                <input type="text" name="promo_code" value="{{ old('promo_code', $promo_code) }}" placeholder="Masukkan kode promo" required class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 md:w-auto" />
                <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 md:w-auto">Aktifkan</button>
                @if(session('success'))
                    <span class="ml-2 text-green-700">{{ session('success') }}</span>
                @endif
                @if(session('error'))
                    <span class="ml-2 text-red-700">{{ session('error') }}</span>
                @endif
            </form>
        @endif
    </div>

    {{-- Produk Grid --}}
    <h1 class="mb-6 text-3xl font-bold">Produk Kami</h1>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($products as $product)
            @php
                // Promo logic
                $final_price = $product->price;
                $promo_active = false;
                $promo_label = '';
                $promo_diskon = 0;
                if(isset($active_promo) && $active_promo && isset($active_promo->discount_type) && isset($active_promo->discount_value)) {
                    if($active_promo->discount_type === 'percent') {
                        $promo_diskon = round($product->price * ($active_promo->discount_value/100));
                        $promo_label = $active_promo->discount_value . '%';
                    } else {
                        $promo_diskon = $active_promo->discount_value;
                        $promo_label = 'Rp ' . number_format($promo_diskon, 0, ',', '.');
                    }
                    $final_price = max(0, $product->price - $promo_diskon);
                    $promo_active = true;
                }
            @endphp
            <div class="flex flex-col overflow-hidden transition bg-white border rounded-lg shadow hover:shadow-lg">
                <img src="{{ asset('storage/' . ($product->image_path ?? 'placeholder.png')) }}" alt="{{ $product->name }}"
                    class="object-cover w-full h-48">
                <div class="flex flex-col flex-1 p-4">
                    <h2 class="mb-1 text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="mb-2 text-gray-600">{{ $product->description }}</p>
                    <div class="flex flex-col mb-2">
                        @if($promo_active && $final_price < $product->price)
                            <span class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-lg font-bold text-green-700">
                                Rp {{ number_format($final_price, 0, ',', '.') }}
                            </span>
                            <span class="px-2 py-1 mt-1 text-xs text-green-700 bg-green-100 rounded">
                                Diskon: {{ $promo_code }} ({{ $promo_label }})
                            </span>
                        @else
                            <span class="mb-2 text-lg font-bold text-gray-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                    {{-- Tombol sejajar horizontal & ukuran sama --}}
                    <div class="flex flex-row gap-2 mt-4">
                        <a href="{{ route('user.products.show', $product) }}"
                            class="w-1/2 px-4 py-2 text-center text-white transition bg-green-500 rounded shadow hover:bg-green-600">
                            Lihat Detail
                        </a>
                        @auth
                            <form method="POST" action="{{ route('user.cart.add') }}" class="w-1/2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="promo_code" value="{{ $promo_code }}">
                                <button type="submit" class="w-full px-4 py-2 text-white transition bg-green-600 rounded shadow hover:bg-green-700">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <span
                                class="w-1/2 px-4 py-2 text-center text-gray-600 bg-gray-200 rounded cursor-not-allowed select-none"
                                title="Login untuk membeli">
                                Login untuk beli
                            </span>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">Belum ada produk tersedia.</p>
        @endforelse
    </div>

    {{-- Newsletter Section --}}
    <div class="mt-10 mb-8">
        <!-- ... newsletter code sama seperti sebelumnya ... -->
    </div>
</div>
@endsection