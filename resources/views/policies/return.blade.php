@extends('layouts.app')

@section('title', 'Return & Refund Policy - Azka Garden')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-3xl px-4 mx-auto space-y-10">
    <header class="flex flex-col items-center mb-8">
      <span class="inline-flex items-center gap-2">
        <svg class="w-10 h-10 text-green-500 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
        </svg>
        <h1 class="text-3xl font-extrabold tracking-tight text-green-700 md:text-4xl">Return & Refund Policy</h1>
      </span>
      <p class="mt-2 text-base font-medium text-center text-gray-600">Kebijakan retur & refund Azka Garden untuk kenyamanan belanja Anda.</p>
    </header>
    <div class="space-y-6 leading-loose text-justify text-gray-800">
      <p>Kami ingin Anda puas dengan setiap pembelian. Jika produk tidak sesuai, berikut ketentuan retur:</p>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Syarat Pengembalian</h2>
      <ul class="ml-4 text-green-900 list-disc list-inside">
        <li>Barang harus asli dan belum dipakai</li>
        <li>Pengajuan dalam waktu 7 hari setelah penerimaan</li>
        <li>Tanaman mati total atau spesies tidak sesuai</li>
      </ul>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Proses Refund</h2>
      <p>Setelah kami terima dan periksa barang, dana akan dikembalikan dalam 14 hari kerja.</p>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Biaya Pengiriman</h2>
      <p>Biaya pengiriman pengembalian ditanggung pembeli kecuali kesalahan produk dari pihak kami.</p>
      <form method="POST" action="{{ route('policy.accept') }}" class="mt-8 text-center">
        @csrf
        <button type="submit" class="px-8 py-3 font-semibold text-white bg-green-600 rounded-lg shadow hover:bg-green-700">
          Saya Setuju
        </button>
      </form>
    </div>
    <div class="mt-12 text-sm text-center text-gray-500">
      &copy; {{ date('Y') }} Azka Garden. Semua hak dilindungi.
    </div>
  </div>
</section>
@endsection
