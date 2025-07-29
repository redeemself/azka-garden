@extends('layouts.app')

@section('title', 'Cookie Policy - Azka Garden')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-3xl px-4 mx-auto space-y-10">
    <header class="flex flex-col items-center mb-8">
      <span class="inline-flex items-center gap-2">
        <svg class="w-10 h-10 text-green-500 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
        </svg>
        <h1 class="text-3xl font-extrabold tracking-tight text-green-700 md:text-4xl">Cookie Policy</h1>
      </span>
      <p class="mt-2 text-base font-medium text-center text-gray-600">Penggunaan cookie untuk pengalaman terbaik di Azka Garden.</p>
    </header>
    <div class="space-y-6 leading-loose text-justify text-gray-800">
      <p>Kami menggunakan cookie untuk meningkatkan layanan, analitik, dan personalisasi promosi. Spanduk persetujuan cookie akan muncul pada kunjungan pertama, dan Anda dapat mengatur ulang preferensi melalui browser.</p>
      <ul class="ml-4 text-green-900 list-disc list-inside">
        <li><b>Essential:</b> Fungsi dasar situs (login, keranjang)</li>
        <li><b>Analytical:</b> Statistik kunjungan & performa</li>
        <li><b>Marketing:</b> Penawaran produk sesuai minat Anda</li>
      </ul>
      <p>Penolakan cookie non-esensial tidak mempengaruhi fungsi utama toko.</p>
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
