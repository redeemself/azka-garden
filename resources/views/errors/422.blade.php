@extends('layouts.app')

@section('title', '422 Unprocessable Entity')

@section('content')
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-orange-700">422</h1>
    <p class="mb-8 text-xl text-gray-700">Data yang Anda masukkan tidak valid atau gagal diproses.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 text-white bg-orange-600 rounded hover:bg-orange-700">
        Kembali ke Beranda
    </a>
</div>
@endsection