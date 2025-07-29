@extends('layouts.app')

@section('title', 'Konfirmasi Reset Persetujuan - Azka Garden')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-md px-6 mx-auto text-center bg-white shadow-md rounded-xl">
    <h1 class="mb-6 text-2xl font-bold text-green-700">Reset Persetujuan Kebijakan Privasi</h1>
    <p class="mb-6 text-gray-700">
      Apakah Anda yakin ingin menghapus persetujuan kebijakan privasi Anda? Setelah direset, Anda harus menyetujui kembali kebijakan privasi untuk mengakses fitur situs.
    </p>
    <form method="POST" action="{{ route('policy.reset.post') }}" onsubmit="return confirm('Yakin ingin reset persetujuan?');">
      @csrf
      <button type="submit" class="px-6 py-3 mb-4 font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
        Reset Persetujuan
      </button>
    </form>
    <a href="{{ route('home') }}" class="text-green-600 hover:underline">Batal dan kembali ke Beranda</a>
    <div class="mt-12 text-sm text-center text-gray-500">
      &copy; {{ date('Y') }} Azka Garden. Semua hak dilindungi.
    </div>
  </div>
</section>
@endsection
