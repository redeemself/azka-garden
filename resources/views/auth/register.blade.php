@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Azka Garden')

@section('content')
    <section x-data="{
        heroImages: [
            '{{ asset('images/hero-1.jpg') }}',
            '{{ asset('images/hero-2.jpg') }}',
            '{{ asset('images/hero-3.jpg') }}',
            '{{ asset('images/hero-4.jpg') }}',
            '{{ asset('images/hero-5.jpg') }}',
            '{{ asset('images/hero-6.jpg') }}',
            '{{ asset('images/hero-7.jpg') }}'
        ],
        currentBg: 0,
        nextBg: 1,
        frontLayer: true,
        interval: null,
        transitionDuration: 1000,
        transitioning: false,
        debounceTimeout: null,
        showPassword: false,
        showPasswordConfirm: false,
        formErrors: {},

        validateForm() {
            this.formErrors = {};
            let isValid = true;

            // Name validation
            const nameInput = document.getElementById('name');
            if (!nameInput.value.trim()) {
                this.formErrors.name = 'Nama lengkap wajib diisi';
                isValid = false;
            }

            // Email validation
            const emailInput = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim()) {
                this.formErrors.email = 'Email wajib diisi';
                isValid = false;
            } else if (!emailPattern.test(emailInput.value)) {
                this.formErrors.email = 'Format email tidak valid';
                isValid = false;
            }

            // Phone validation
            const phoneInput = document.getElementById('phone');
            const phonePattern = /^[0-9+\-\s()]{10,15}$/;
            if (!phoneInput.value.trim()) {
                this.formErrors.phone = 'Nomor telepon wajib diisi';
                isValid = false;
            } else if (!phonePattern.test(phoneInput.value)) {
                this.formErrors.phone = 'Format nomor telepon tidak valid';
                isValid = false;
            }

            // Password validation
            const passwordInput = document.getElementById('password');
            if (!passwordInput.value) {
                this.formErrors.password = 'Password wajib diisi';
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                this.formErrors.password = 'Password minimal 8 karakter';
                isValid = false;
            }

            // Password confirmation validation
            const passwordConfirmInput = document.getElementById('password_confirmation');
            if (passwordInput.value !== passwordConfirmInput.value) {
                this.formErrors.password_confirmation = 'Konfirmasi password tidak cocok';
                isValid = false;
            }

            // Terms validation
            const termsInput = document.getElementById('terms');
            if (!termsInput.checked) {
                this.formErrors.terms = 'Anda harus menyetujui syarat dan ketentuan';
                isValid = false;
            }

            return isValid;
        },

        init() {
            this.startInterval();

            // Form submission handler
            document.getElementById('register-form').addEventListener('submit', (e) => {
                if (!this.validateForm()) {
                    e.preventDefault();
                }
            });
        },

        startInterval() {
            this.stopInterval();
            this.interval = setInterval(() => this.nextBackground(), 10000);
        },

        stopInterval() {
            if (this.interval) clearInterval(this.interval);
        },

        nextBackground() {
            if (this.transitioning) return;
            this.transitioning = true;
            this.frontLayer = !this.frontLayer;
            this.currentBg = this.nextBg;
            this.nextBg = (this.nextBg + 1) % this.heroImages.length;
            setTimeout(() => this.transitioning = false, this.transitionDuration);
        },

        prevBackground() {
            this.stopInterval();
            this.nextBg = (this.currentBg - 1 + this.heroImages.length) % this.heroImages.length;
            this.nextBackground();
            this.startInterval();
        },

        nextBgManual() {
            this.stopInterval();
            this.nextBackground();
            this.startInterval();
        },

        setBg(idx) {
            if (idx === this.currentBg) return;
            this.stopInterval();
            this.nextBg = idx;
            this.nextBackground();
            this.startInterval();
        },
    }" x-init="init"
        class="relative flex-1 flex flex-col justify-center items-center w-full h-full min-h-[calc(100vh-4rem)] overflow-hidden">

        {{-- Background dan overlay --}}
        <div class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1000ms] ease-cubic-bezier opacity-100 z-10"
            :class="frontLayer ? 'opacity-100' : 'opacity-0'" :style="`background-image: url('${heroImages[currentBg]}')`">
        </div>
        <div class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1000ms] ease-cubic-bezier opacity-0 z-0"
            :class="!frontLayer ? 'opacity-100' : 'opacity-0'" :style="`background-image: url('${heroImages[nextBg]}')`">
        </div>
        <div class="absolute inset-0 z-20 pointer-events-none bg-black/60 backdrop-blur-sm"></div>

        {{-- Form Registrasi --}}
        <div class="relative z-30 w-full max-w-md p-6 mx-auto shadow-lg bg-white/30 rounded-xl backdrop-blur-md">
            <h2 class="mb-6 text-2xl font-semibold text-center text-green-700">Daftar Akun Baru</h2>

            {{-- Flash & Error Messages --}}
            @if (session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="register-form" method="POST" action="{{ route('register.submit') }}" novalidate>
                @csrf

                {{-- Role Pilihan hanya "user" --}}
                <input type="hidden" name="role" value="user">

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label for="name" class="block mb-1 font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror" />
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.name" x-show="formErrors.name"></p>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" />
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.email" x-show="formErrors.email"></p>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone Number (Added) --}}
                <div class="mb-4">
                    <label for="phone" class="block mb-1 font-medium text-gray-700">Nomor Telepon</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                        placeholder="contoh: 081234567890"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror" />
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.phone" x-show="formErrors.phone"></p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="relative mb-4">
                    <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" />
                    <button type="button"
                        class="absolute text-gray-500 right-3 top-9 hover:text-green-700 focus:outline-none"
                        @click="showPassword = !showPassword" aria-label="Toggle password visibility">
                        <template x-if="!showPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z
                                                                                                   M2.458 12C3.732 7.943 7.523 5 12 5
                                                                                                   c4.477 0 8.268 2.943 9.542 7
                                                                                                   -1.274 4.057-5.065 7-9.542 7
                                                                                                   -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="showPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                                                                                                   c-4.477 0-8.268-2.943-9.542-7
                                                                                                   a9.985 9.985 0 012.016-3.436
                                                                                                   M3 3l18 18" />
                            </svg>
                        </template>
                    </button>
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.password" x-show="formErrors.password"></p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="relative mb-4">
                    <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" name="password_confirmation"
                        :type="showPasswordConfirm ? 'text' : 'password'" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                    <button type="button"
                        class="absolute text-gray-500 right-3 top-9 hover:text-green-700 focus:outline-none"
                        @click="showPasswordConfirm = !showPasswordConfirm"
                        aria-label="Toggle konfirmasi password visibility">
                        <template x-if="!showPasswordConfirm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z
                                                                                                   M2.458 12C3.732 7.943 7.523 5 12 5
                                                                                                   c4.477 0 8.268 2.943 9.542 7
                                                                                                   -1.274 4.057-5.065 7-9.542 7
                                                                                                   -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="showPasswordConfirm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                                                                                                   c-4.477 0-8.268-2.943-9.542-7
                                                                                                   a9.985 9.985 0 012.016-3.436
                                                                                                   M3 3l18 18" />
                            </svg>
                        </template>
                    </button>
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.password_confirmation"
                        x-show="formErrors.password_confirmation"></p>
                </div>

                {{-- Terms of Service Checkbox (Added) --}}
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="terms" name="terms"
                            class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" required>
                        <span class="ml-2 text-sm text-gray-700">
                            Saya menyetujui <a href="{{ route('terms') }}"
                                class="font-semibold text-green-600 hover:underline" target="_blank">Syarat dan
                                Ketentuan</a>
                            serta <a href="{{ route('privacy') }}" class="font-semibold text-green-600 hover:underline"
                                target="_blank">Kebijakan Privasi</a>
                        </span>
                    </label>
                    <p class="mt-1 text-sm text-red-600" x-text="formErrors.terms" x-show="formErrors.terms"></p>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                    class="w-full py-2 font-semibold text-white transition bg-green-600 rounded-md hover:bg-green-700">
                    Daftar
                </button>
            </form>

            {{-- Switch to Login --}}
            <p class="mt-6 text-sm text-center text-white/90">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-green-400 hover:underline">
                    Masuk di sini
                </a>
            </p>

            {{-- Link alternatif untuk daftar admin dan dev --}}
            <p class="mt-4 text-sm text-center text-white/70">
                Daftar sebagai
                <a href="{{ route('admin.register') }}"
                    class="font-semibold text-green-400 hover:underline">Administrator</a> |
            <form action="{{ route('auth.register.submit') }}" method="POST">
                @csrf
                <!-- form inputs -->
                <button type="submit">Register</button>
            </form>

            </p>
        </div>

        {{-- Slider Controls --}}
        <div
            class="relative z-30 flex items-center justify-center w-full max-w-md gap-3 px-4 py-2 mx-auto mt-4 rounded-full select-none bg-black/40">
            <button @click="prevBackground" aria-label="Background Sebelumnya"
                class="flex items-center justify-center w-8 h-8 text-white transition rounded-full hover:bg-black/60">&#8592;</button>
            <template x-for="(img, idx) in heroImages" :key="'dot-' + idx">
                <span
                    :class="{
                        'w-5 h-3 rounded-full bg-green-500 cursor-pointer shadow': idx === currentBg,
                        'w-3 h-3 rounded-full bg-white/60 cursor-pointer': idx !== currentBg
                    }"
                    @click="setBg(idx)" :aria-label="`Pilih background ke-` + (idx + 1)"></span>
            </template>
            <button @click="nextBgManual" aria-label="Background Selanjutnya"
                class="flex items-center justify-center w-8 h-8 text-white transition rounded-full hover:bg-black/60">&#8594;</button>
        </div>

        {{-- Developer info - Updated 2025-07-30 05:45:35 by mulyadafa --}}
    </section>
@endsection

@push('scripts')
    <script>
        // Additional validation script for older browsers that don't support Alpine.js
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');

            if (form && !window.Alpine) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Basic validation fallback
                    const password = document.getElementById('password');
                    const confirmPassword = document.getElementById('password_confirmation');

                    if (password.value !== confirmPassword.value) {
                        alert('Password dan konfirmasi password tidak cocok');
                        isValid = false;
                    }

                    const phone = document.getElementById('phone');
                    const phonePattern = /^[0-9+\-\s()]{10,15}$/;
                    if (!phonePattern.test(phone.value)) {
                        alert('Format nomor telepon tidak valid');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush
