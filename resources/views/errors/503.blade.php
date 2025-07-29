@extends('layouts.app')

@section('title', '503 Service Unavailable')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-purple-700">503</h1>
    <p class="mb-8 text-xl text-gray-700">Server sedang dalam perawatan atau tidak tersedia.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-purple-600 rounded hover:bg-purple-700">
        Kembali ke Beranda
    </a>
</div>
@endsection