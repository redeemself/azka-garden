@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Pemberitahuan')

@section('content')
<section class="py-12 main-content-offset bg-gradient-to-tr from-green-50 via-white to-green-100">
  <div class="container max-w-xl px-4 mx-auto space-y-6 text-center">
    <h1 class="text-3xl font-extrabold text-red-600">Pemberitahuan Kebijakan Privasi</h1>
    <p class="text-lg">
      Sebelum melanjutkan, Anda harus menyetujui kebijakan privasi Azka Garden.<br>
      Silakan baca dan setujui kebijakan privasi agar dapat menikmati semua fitur situs.
    </p>
    <div class="space-x-4">
      <a href="{{ route('policy.privacy') }}" class="text-blue-600 underline hover:text-blue-800">
        Baca Kebijakan Privasi
      </a>
    </div>
    <form action="{{ route('policy.accept') }}" method="POST" class="mt-6">
      @csrf
      <button type="submit" class="px-6 py-3 font-semibold text-white transition bg-green-600 rounded-lg hover:bg-green-700">
        Saya Setuju
      </button>
    </form>
    <div class="mt-12 text-sm text-center text-gray-500">
      &copy; {{ date('Y') }} Azka Garden. Semua hak dilindungi.
    </div>
  </div>
</section>
@endsection
