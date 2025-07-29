@extends('layouts.app')

@section('title', 'Riwayat Pesanan Anda')

@section('content')
<style>
/* Copy-paste semua CSS dari daftar pesanan di atas, agar tampilan konsisten */
.orders-table-container {
  overflow-x: auto;
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 10px 28px rgba(56, 189, 248, 0.08), 0 2px 4px rgba(16, 185, 129, 0.02);
  margin-bottom: 2rem;
}
.orders-table { min-width: 100%; border-collapse: separate; border-spacing: 0; }
.orders-table th, .orders-table td { padding: 1rem 1rem; vertical-align: top; }
.orders-table thead th { background: #dcfce7; color: #166534; font-weight: bold; font-size: 0.95rem; letter-spacing: 0.05em; border-bottom: 2px solid #bbf7d0; }
.orders-table tbody tr { transition: background 0.15s; }
.orders-table tbody tr:hover { background: #f0fdf4; }
.status-badge { font-weight: 600; border-radius: 0.5rem; padding: 0.35em 0.9em; font-size: 0.95em; display: inline-block; }
.status-completed { background: #22c55e; color: #fff; }
.status-canceled { background: #f87171; color: #fff; }
.status-other { background: #d4d4d8; color: #444; }
.product-list { display: flex; flex-direction: column; gap: 0.65em; }
.product-item { display: flex; align-items: center; gap: 0.5em; margin-bottom: 0.15em; }
.product-img { width: 2.2em; height: 2.2em; object-fit: cover; border-radius: 0.5em; border: 1px solid #bbf7d0; background: #e5e7eb; }
.product-info { display: flex; flex-direction: column; gap: 0.1em; }
.product-title { color: #166534; font-weight: 700; font-size: 1em; }
.product-qty { color: #16a34a; font-size: 0.9em; }
.product-subtotal { color: #22c55e; font-size: 0.9em; font-weight: 500; }
@media (max-width: 900px) { .orders-table th, .orders-table td { padding: 0.6rem 0.5rem; } .orders-table-container { border-radius: 1rem; } }
@media (max-width: 700px) { .orders-table-container { display: none; } .mobile-orders-list { display: block !important; } }
@media (max-width: 600px) { h1 { font-size: 1.5em !important; } .product-img { width: 1.4em; height: 1.4em; } .mobile-order-card { padding: 1.1em 1em;} }
</style>
<section class="min-h-screen py-10 bg-green-50">
  <div class="px-2 mx-auto sm:px-5 max-w-7xl">
    <h1 class="mb-8 text-3xl font-extrabold tracking-tight text-center text-green-800 md:text-4xl">
      Riwayat Pesanan Anda
    </h1>

    {{-- Desktop Table --}}
    @if(isset($orders) && count($orders))
      <div class="my-4 orders-table-container">
        <table class="orders-table">
          <thead>
            <tr>
              <th>KODE ORDER</th>
              <th>TANGGAL</th>
              <th>STATUS</th>
              <th>TOTAL HARGA</th>
              <th>PRODUK</th>
              <th>PENGIRIMAN</th>
              <th>PEMBAYARAN</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
              @php
                $statusClass = ($order->enum_order_status_id == 4)
                  ? 'status-badge status-completed'
                  : (($order->enum_order_status_id == 5)
                      ? 'status-badge status-canceled'
                      : 'status-badge status-other');
                $statusText = ($order->enum_order_status_id == 4)
                  ? 'Selesai'
                  : (($order->enum_order_status_id == 5)
                      ? 'Dibatalkan'
                      : 'Lainnya');
              @endphp
              <tr>
                <td>
                  <span class="font-mono font-bold text-green-700">{{ $order->order_code }}</span>
                </td>
                <td>
                  <span class="block">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                  <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($order->order_date)->format('H:i') }}</span>
                </td>
                <td>
                  <span class="{{ $statusClass }}">{{ $statusText }}</span>
                </td>
                <td>
                  <span class="text-lg font-bold text-green-800">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </td>
                <td>
                  <div class="product-list">
                    @foreach($order->details as $detail)
                      <div class="product-item">
                        @php
                          $img = isset($detail->product->images) && $detail->product->images->count() > 0
                              ? asset($detail->product->images->first()->image_url)
                              : asset('images/no-image.png');
                        @endphp
                        <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                        <div class="product-info">
                          <span class="product-title">{{ $detail->product->name }}</span>
                          <span class="product-qty">x{{ $detail->quantity }}</span>
                          <span class="product-subtotal">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </td>
                <td>
                  @if($order->shipping)
                    <div class="text-xs leading-5">
                      <span class="font-semibold">{{ $order->shipping->courier ?? '-' }}</span>
                      <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                      <span class="block text-green-700">Ongkir: Rp{{ number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.') }}</span>
                      <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                      <span class="block text-green-600">Status: {{ $order->shipping->status ?? '-' }}</span>
                    </div>
                  @else
                    <span class="text-sm text-gray-400">Belum dikirim</span>
                  @endif
                </td>
                <td>
                  @if($order->payment)
                    <div class="text-xs leading-5">
                      <span class="font-semibold">{{ $order->payment->method->name ?? '-' }}</span>
                      <span class="block">Status: {{ $order->payment->status ?? '-' }}</span>
                      <span class="block">Transfer: Rp{{ number_format($order->payment->total ?? 0, 0, ',', '.') }}</span>
                    </div>
                  @else
                    <span class="text-sm text-gray-400">Belum dibayar</span>
                  @endif
                </td>
                <td style="min-width:120px;">
                  <a href="{{ route('user.orders.show', $order->id) }}"
                    class="order-action-btn detail">
                    Detail
                  </a>
                  {{-- Tidak tampilkan tombol batalkan di riwayat --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Mobile Card List --}}
      <div class="mobile-orders-list" style="display:none;">
        @foreach($orders as $order)
          @php
            $statusClass = ($order->enum_order_status_id == 4)
              ? 'status-badge status-completed'
              : (($order->enum_order_status_id == 5)
                  ? 'status-badge status-canceled'
                  : 'status-badge status-other');
            $statusText = ($order->enum_order_status_id == 4)
              ? 'Selesai'
              : (($order->enum_order_status_id == 5)
                  ? 'Dibatalkan'
                  : 'Lainnya');
          @endphp
          <div class="px-4 py-4 mb-4 bg-white border border-green-100 shadow-md mobile-order-card rounded-2xl">
            <div class="flex flex-col gap-1">
              <div class="flex items-center justify-between mb-2">
                <span class="font-mono text-base font-bold text-green-700">
                  {{ $order->order_code }}
                </span>
                <span class="{{ $statusClass }} text-xs">{{ $statusText }}</span>
              </div>
              <span class="block mb-2 text-xs text-gray-500">
                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y H:i') }}
              </span>
              <div class="flex items-center gap-2 mb-2">
                <span class="text-base font-bold text-green-800">
                  Rp{{ number_format($order->total_price, 0, ',', '.') }}
                </span>
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Produk:</div>
                <div class="product-list">
                  @foreach($order->details as $detail)
                    @php
                      $img = isset($detail->product->images) && $detail->product->images->count() > 0
                          ? asset($detail->product->images->first()->image_url)
                          : asset('images/no-image.png');
                    @endphp
                    <div class="product-item">
                      <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                      <div class="product-info">
                        <span class="product-title">{{ $detail->product->name }}</span>
                        <span class="product-qty">x{{ $detail->quantity }}</span>
                        <span class="product-subtotal">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Pengiriman:</div>
                @if($order->shipping)
                  <div class="text-xs leading-5">
                    <span class="font-semibold">{{ $order->shipping->courier ?? '-' }}</span>
                    <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                    <span class="block text-green-700">Ongkir: Rp{{ number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                    <span class="block text-green-600">Status: {{ $order->shipping->status ?? '-' }}</span>
                  </div>
                @else
                  <span class="text-sm text-gray-400">Belum dikirim</span>
                @endif
              </div>
              <div class="mb-2">
                <div class="mb-1 text-xs font-bold text-green-900">Pembayaran:</div>
                @if($order->payment)
                  <div class="text-xs leading-5">
                    <span class="font-semibold">{{ $order->payment->method->name ?? '-' }}</span>
                    <span class="block">Status: {{ $order->payment->status ?? '-' }}</span>
                    <span class="block">Transfer: Rp{{ number_format($order->payment->total ?? 0, 0, ',', '.') }}</span>
                  </div>
                @else
                  <span class="text-sm text-gray-400">Belum dibayar</span>
                @endif
              </div>
              <div class="flex gap-2 mt-3">
                <a href="{{ route('user.orders.show', $order->id) }}"
                  class="order-action-btn detail" style="margin-bottom:0;">
                  Detail
                </a>
                {{-- Tidak tampilkan tombol batalkan di riwayat --}}
              </div>
            </div>
          </div>
        @endforeach
        <div class="flex justify-center my-6">
          {{ $orders->links() }}
        </div>
      </div>
    @else
      <div class="flex flex-col items-center justify-center py-16 text-center text-green-800">
        <svg class="w-10 h-10 mb-4 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-5.586a2 2 0 01-1.414-.586l-1.414-1.414A2 2 0 009.586 3H8a2 2 0 00-2 2v14z" />
        </svg>
        <h2 class="mb-2 text-2xl font-bold">Belum ada riwayat pesanan</h2>
        <p class="mb-4 text-green-600">Tidak ada pesanan yang selesai atau dibatalkan.</p>
        <a href="{{ route('products.index') }}" class="px-6 py-3 text-base font-bold text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
          Lihat Produk
        </a>
      </div>
    @endif
  </div>
</section>
@endsection