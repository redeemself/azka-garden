@extends('layouts.app')

@section('content')
<div class="container p-6 mx-auto">
    <h1 class="mb-6 text-3xl font-bold">Profil Admin Saya</h1>

    @auth('admin')
        @php
            $admin = auth('admin')->user();
        @endphp

        <div class="p-6 mb-6 bg-white rounded-lg shadow">
            <p class="mb-4"><span class="font-semibold">Nama:</span> {{ $admin->name }}</p>
            <p class="mb-4"><span class="font-semibold">Username:</span> {{ $admin->username }}</p>
            <p class="mb-4"><span class="font-semibold">Email:</span> {{ $admin->email }}</p>
            @if($admin->last_login)
                <p class="text-gray-500"><span class="font-semibold">Terakhir Login:</span> {{ $admin->last_login->format('d M Y H:i') }}</p>
            @endif
            <p><span class="font-semibold">Role ID:</span> {{ $admin->role_id }}</p>
            <p><span class="font-semibold">Status ID:</span> {{ $admin->status_id }}</p>
        </div>

        <a href="{{ route('admin.profile.edit') }}"
           class="inline-block px-4 py-2 mb-6 text-white bg-blue-600 rounded hover:bg-blue-700">
            Edit Profil
        </a>
    @else
        <div class="p-6 bg-yellow-100 rounded-lg shadow">
            <p>Anda belum login sebagai admin. Silakan <a href="{{ route('admin.login') }}" class="text-blue-600 underline">login</a> terlebih dahulu.</p>
        </div>
    @endauth
</div>
@endsection
