@extends('layouts.app')

@section('title', 'Terms of Service - Azka Garden')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-3xl px-4 mx-auto space-y-10">
    <header class="flex flex-col items-center mb-8">
      <span class="inline-flex items-center gap-2">
        <svg class="w-10 h-10 text-green-500 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
        </svg>
        <h1 class="text-3xl font-extrabold tracking-tight text-green-700 md:text-4xl">Terms of Service</h1>
      </span>
      <p class="mt-2 text-base font-medium text-center text-gray-600">Syarat dan ketentuan layanan Azka Garden.</p>
    </header>
    <div class="space-y-6 leading-loose text-justify text-gray-800">
      <h2 class="text-lg font-semibold text-green-700">Penggunaan Layanan</h2>
      <p>Pengguna wajib memasukkan data akurat, tidak menyalahgunakan atau merusak sistem, dan tidak melakukan aktivitas ilegal (spam, scraping, hacking).</p>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Hak Kekayaan Intelektual</h2>
      <p>Semua konten di situs ini dilindungi hak cipta Azka Garden. Dilarang menyalin tanpa izin tertulis.</p>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Batasan Tanggung Jawab</h2>
      <p>Azka Garden tidak bertanggung jawab atas kerugian tidak langsung, termasuk kehilangan estetika atau kerusakan properti lain.</p>
      <h2 class="mt-4 text-lg font-semibold text-green-700">Perubahan Syarat</h2>
      <p>Setiap revisi diumumkan minimal 7 hari sebelum berlaku, dan kelanjutan penggunaan dianggap persetujuan atas syarat baru.</p>
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
