@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-red-700">403</h1>
    <p class="mb-8 text-xl text-gray-700">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-red-600 rounded hover:bg-red-700">
        Kembali ke Beranda
    </a>
</div>
@endsection