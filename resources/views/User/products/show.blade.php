@extends('layouts.app')

@section('content')
@php
    $displayImages = collect($productImages ?? [])
        ->filter(function($img) {
            return is_object($img) && isset($img->image_url) && preg_match('/\.(jpg|jpeg|png)$/i', trim($img->image_url));
        })->values();
    $likeCount = $likeCount ?? 0;
    $userLiked = $userLiked ?? false;
    $comments = $comments ?? [];
@endphp
<div class="flex flex-col min-h-screen">
  <section class="flex flex-col flex-grow bg-green-50">
    <div class="container p-6 mx-auto" style="padding-top:72px;">
      <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Gambar Produk -->
        <div class="flex flex-row justify-center gap-4 py-6 px-4 lg:w-1/2 bg-gray-50 rounded-2xl shadow-xl items-center">
            @foreach($displayImages->take(2) as $img)
                <div class="relative group w-1/2 flex justify-center items-center">
                    <img src="{{ asset($img->image_url) }}"
                        alt="{{ $product->name }}"
                        class="object-contain h-72 w-full max-w-xs rounded-xl border-2 border-green-100 shadow-lg transition-all duration-300 ease-in-out group-hover:scale-105 group-hover:shadow-2xl bg-gradient-to-br from-green-50 to-white"
                        style="min-width:140px;"
                    >
                    <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded-lg shadow transition opacity-0 group-hover:opacity-100">
                        Gambar {{ $loop->iteration }}
                    </span>
                </div>
            @endforeach
            @if($displayImages->isEmpty())
                <img src="{{ asset($product->image_url ?? 'images/produk/placeholder.png') }}"
                    alt="{{ $product->name }}"
                    class="object-cover h-72 w-full rounded-xl border-2 border-green-100 shadow-lg bg-gradient-to-br from-green-50 to-white">
            @endif
        </div>
        <!-- Product Details -->
        <div class="flex flex-col justify-center lg:w-1/2 px-2">
          <h1 class="mb-2 text-4xl font-bold text-green-700 leading-tight">{{ $product->name }}</h1>
          <div class="flex items-center gap-2 mb-4 flex-wrap">
            @php
              $promo_code = session('promo_code');
              $promo_type = $promo->discount_type ?? null;
              $promo_discount = $promo->discount_value ?? null;
              $final_price = $product->price;
              $promo_label = '';
              $diskon = 0;
              // Perbaikan diskon percent: pastikan hanya antara 0-100
              if($promo_code && $promo_type && $promo_discount) {
                if($promo_type === 'percent') {
                  // Diskon 'percent' harus dibagi 100, dan tidak boleh > 100
                  $promo_discount = floatval($promo_discount);
                  if($promo_discount < 0) $promo_discount = 0;
                  if($promo_discount > 100) $promo_discount = 100;
                  $diskon = round($product->price * ($promo_discount / 100));
                  $promo_label = $promo_discount . '%';
                } else {
                  // Diskon 'fixed' tidak boleh melebihi harga produk
                  $diskon = min($product->price, max(0, floatval($promo_discount)));
                  $promo_label = 'Rp ' . number_format($diskon, 0, ',', '.');
                }
                $final_price = max(0, $product->price - $diskon);
              }
            @endphp
            @if($promo_code && $final_price < $product->price)
              <span class="text-2xl text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
              <span class="ml-2 text-3xl font-extrabold text-green-700 drop-shadow">Rp {{ number_format($final_price, 0, ',', '.') }}</span>
              <span class="px-3 py-1 ml-2 text-xs font-semibold text-green-900 bg-green-100 rounded-lg border border-green-300 shadow">
                {{ $promo_code }} Diskon ({{ $promo_label }})
              </span>
            @else
              <span class="text-3xl font-extrabold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @endif
          </div>
          <div class="mb-6 prose text-gray-700 max-w-none text-base leading-relaxed bg-white/50 p-4 rounded-xl border border-green-100 shadow">
            {!! nl2br(e($product->description)) !!}
          </div>

          <!-- Comment Section Only -->
          <div class="mb-6 flex flex-row items-center gap-4">
            <!-- Comment Button (scroll to textarea komentar) -->
            <button
              type="button"
              class="px-3 py-2 rounded-full border border-green-200 bg-white shadow hover:bg-green-50 transition flex items-center gap-1 text-green-700 comment-scroll-btn"
              title="Komentar produk"
              id="btn-scroll-comment"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" viewBox="0 0 20 20" fill="currentColor">
                <path d="M18 10c0 3.866-3.582 7-8 7a8.962 8.962 0 01-4.938-1.49l-2.447.816a1 1 0 01-1.27-1.27l.816-2.447A8.962 8.962 0 013 10c0-4.418 3.134-8 7-8s7 3.582 7 8z"/>
              </svg>
              <span>Komentar</span>
            </button>
          </div>

          <!-- Comment Area -->
          <div id="comment-area" class="mb-6">
            <h3 class="mb-2 text-lg font-bold text-green-700">Komentar Produk</h3>
            @auth
            <form action="{{ route('products.comment', $product->id) }}" method="POST" class="mb-4 flex flex-col gap-2 comment-form">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <textarea id="comment-textarea" name="comment" rows="2" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 resize-none" placeholder="Tulis komentar..."></textarea>
              <button type="submit" class="self-end px-4 py-2 font-bold text-white bg-green-600 rounded-lg shadow hover:bg-green-700 transition">Kirim</button>
            </form>
            @endauth
            <div class="flex flex-col gap-3 comment-list">
              @forelse($comments as $comment)
                <div class="p-4 bg-white rounded-lg border border-green-100 shadow flex flex-col">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="font-bold text-green-800">{{ optional($comment->user)->name ?? 'User' }}</span>
                    <span class="text-xs text-gray-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                  </div>
                  <div class="text-gray-700">{{ $comment->comment }}</div>
                </div>
              @empty
                <span class="text-gray-500 italic">Belum ada komentar.</span>
              @endforelse
            </div>
          </div>

          @auth
          <form action="{{ route('user.cart.add') }}" method="POST" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            @if($promo_code)
              <input
                type="text"
                name="promo_code"
                value="{{ $promo_code }}"
                readonly
                class="block w-full px-4 py-2 font-mono transition border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50 text-green-700"
              >
            @else
              <input
                type="text"
                name="promo_code"
                value="{{ old('promo_code') }}"
                placeholder="Masukkan kode promo (jika ada)"
                class="block w-full px-4 py-2 transition border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
              >
            @endif
            <button
              type="submit"
              class="px-6 py-3 font-bold text-white bg-gradient-to-br from-green-600 to-green-500 rounded-lg shadow-lg hover:from-green-700 hover:to-green-600 transition"
            >
              Tambah ke Keranjang
            </button>
          </form>
          @else
          <span
            class="inline-block px-6 py-3 mt-2 text-gray-600 bg-gray-200 rounded-lg shadow cursor-not-allowed select-none"
            title="Login untuk membeli">
            Login untuk beli
          </span>
          @endauth

          @if(session('success'))
            <div class="mt-4 font-semibold text-green-700 bg-green-100 px-4 py-2 rounded">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="mt-4 font-semibold text-red-700 bg-red-100 px-4 py-2 rounded">{{ session('error') }}</div>
          @endif
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
@auth
<script src="{{ asset('js/product-actions.js') }}"></script>
@endauth
<script>
  // Smooth scroll ke textarea komentar tanpa reload
  document.addEventListener('DOMContentLoaded', function () {
    const commentBtn = document.getElementById('btn-scroll-comment');
    const commentTextarea = document.getElementById('comment-textarea');
    if (commentBtn && commentTextarea) {
      commentBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        commentTextarea.focus();
        commentTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    }
  });
</script>
@endsection

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
