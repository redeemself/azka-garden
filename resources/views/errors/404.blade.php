@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-green-700">404</h1>
    <p class="mb-8 text-xl text-gray-700">Halaman yang Anda cari tidak ditemukan.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-green-600 rounded hover:bg-green-700">
        Kembali ke Beranda
    </a>
</div>
@endsection