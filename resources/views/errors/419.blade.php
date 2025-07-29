@extends('layouts.app')

@section('title', '419 Session Expired')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-gray-700">419</h1>
    <p class="mb-8 text-xl text-gray-700">Sesi Anda telah kadaluarsa. Silakan muat ulang halaman.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-gray-600 rounded hover:bg-gray-700">
        Kembali ke Beranda
    </a>
</div>
@endsection