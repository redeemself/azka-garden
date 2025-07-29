@extends('layouts.app')

@section('title', '401 Unauthorized')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-yellow-700">401</h1>
    <p class="mb-8 text-xl text-gray-700">Akses tidak sah. Silakan login terlebih dahulu.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-yellow-600 rounded hover:bg-yellow-700">
        Kembali ke Beranda
    </a>
</div>
@endsection