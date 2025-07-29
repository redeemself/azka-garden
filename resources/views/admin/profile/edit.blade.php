@extends('layouts.app')

@section('content')
<div class="container max-w-lg p-6 mx-auto">
    <h1 class="mb-6 text-3xl font-bold">Edit Profil Admin</h1>

    @auth('admin')
        @php
            /** @var \App\Models\Admin $admin */
            $admin = auth('admin')->user();
        @endphp

        @if(session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="name" class="block mb-1 font-semibold">Nama</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $admin->name) }}"
                    required
                    class="w-full px-3 py-2 border rounded @error('name') border-red-500 @enderror"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                @enderror
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block mb-1 font-semibold">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username', $admin->username) }}"
                    required
                    class="w-full px-3 py-2 border rounded @error('username') border-red-500 @enderror"
                />
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('username') }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $admin->email) }}"
                    required
                    class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                @enderror
            </div>

            <hr>

            {{-- Password Sekarang --}}
            <div>
                <label for="current_password" class="block mb-1 font-semibold">Password Sekarang</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    placeholder="Isi jika ingin mengganti password"
                    class="w-full px-3 py-2 border rounded @error('current_password') border-red-500 @enderror"
                />
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('current_password') }}</p>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div>
                <label for="password" class="block mb-1 font-semibold">Password Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Kosongkan jika tidak ingin mengganti"
                    class="w-full px-3 py-2 border rounded @error('password') border-red-500 @enderror"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <label for="password_confirmation" class="block mb-1 font-semibold">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Konfirmasi password baru"
                    class="w-full px-3 py-2 border rounded @error('password_confirmation') border-red-500 @enderror"
                />
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="px-6 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700"
            >
                Simpan Perubahan
            </button>
        </form>
    @else
        <div class="p-6 bg-yellow-100 rounded-lg shadow">
            <p>Anda belum login sebagai admin. Silakan <a href="{{ route('admin.login') }}" class="text-blue-600 underline">login</a> terlebih dahulu.</p>
        </div>
    @endauth
</div>
@endsection
