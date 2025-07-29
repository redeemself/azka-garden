@extends('layouts.app')

@section('title', '500 Server Error')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-blue-700">500</h1>
    <p class="mb-8 text-xl text-gray-700">Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-blue-600 rounded hover:bg-blue-700">
        Kembali ke Beranda
    </a>
</div>
@endsection