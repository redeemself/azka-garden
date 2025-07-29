@extends('layouts.app')

@section('title', 'Accessibility Statement - Azka Garden')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-3xl px-4 mx-auto space-y-10">
    <header class="flex flex-col items-center mb-8">
      <span class="inline-flex items-center gap-2">
        <svg class="w-10 h-10 text-green-500 drop-shadow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/>
        </svg>
        <h1 class="text-3xl font-extrabold tracking-tight text-green-700 md:text-4xl">Accessibility Statement</h1>
      </span>
      <p class="mt-2 text-base font-medium text-center text-gray-600">Komitmen akses web Azka Garden untuk semua pengguna.</p>
    </header>
    <div class="space-y-5 leading-loose text-justify text-gray-800">
      <p>Azka Garden berkomitmen membuat situs web dapat diakses oleh semua pengguna, termasuk penyandang disabilitas. Kami secara aktif menerapkan <b>WCAG 2.1 Level AA</b> untuk tata letak, warna, navigasi, dan media digital.</p>
      <ul class="ml-4 text-green-900 list-disc list-inside">
        <li>Teks alt pada gambar & ikon navigasi</li>
        <li>Kontras warna optimal, mode terang/gelap</li>
        <li>Keyboard navigation & skip-to-content</li>
        <li>Resizable text & layout responsif</li>
        <li>Formulir kontak ramah screen reader</li>
      </ul>
      <p>Jika Anda menemukan masalah aksesibilitas atau memerlukan bantuan fitur, hubungi <a href="mailto:accessibility@azkagarden.com" class="text-green-700 underline hover:text-green-900">accessibility@azkagarden.com</a>. Kami akan menanggapi permintaan dalam 7 hari kerja.</p>
    </div>
    <div class="mt-12 text-sm text-center text-gray-500">
      &copy; {{ date('Y') }} Azka Garden. Semua hak dilindungi.
    </div>
  </div>
</section>
@endsection
