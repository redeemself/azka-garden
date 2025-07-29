@extends('layouts.app')

@section('title', 'Dashboard Admin - Azka Garden')

@section('content')
<div class="container p-6 mx-auto">
    <h1 class="mb-4 text-2xl font-bold">Dashboard Admin</h1>
    <p>Selamat datang, {{ Auth::guard('admin')->user()->name }}!</p>
    {{-- Tambahkan statistik atau konten dashboard lainnya di sini --}}
</div>
@endsection
