@extends('layouts.app')

@section('title', '429 Too Many Requests')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-pink-700">429</h1>
    <p class="mb-8 text-xl text-gray-700">Terlalu banyak permintaan ke server. Silakan coba beberapa saat lagi.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-pink-600 rounded hover:bg-pink-700">
        Kembali ke Beranda
    </a>
</div>
@endsection