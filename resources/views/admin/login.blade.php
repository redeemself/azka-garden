{{-- resources/views/admin/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login Admin - Azka Garden')

@section('content')
<section
  x-data="{
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

    startInterval() {
      this.stopInterval();
      this.interval = setInterval(() => this.nextBackground(), 10000);
    },

    stopInterval() {
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
    },

    performTransition(nextIndex) {
      if (this.transitioning || nextIndex === this.currentBg) return;
      this.transitioning = true;
      this.nextBg = nextIndex;
      this.frontLayer = !this.frontLayer;

      requestAnimationFrame(() => {
        setTimeout(() => {
          this.currentBg = nextIndex;
          this.transitioning = false;
        }, this.transitionDuration);
      });
    },

    changeBackground(toIndex) {
      if (this.debounceTimeout) return;
      this.debounceTimeout = setTimeout(() => this.debounceTimeout = null, this.transitionDuration);

      this.stopInterval();
      this.performTransition(toIndex);
      this.startInterval();
    },

    nextBackground() {
      this.changeBackground((this.currentBg + 1) % this.heroImages.length);
    },

    prevBackground() {
      this.changeBackground((this.currentBg - 1 + this.heroImages.length) % this.heroImages.length);
    },

    nextBgManual() {
      this.stopInterval();
      this.nextBackground();
      this.startInterval();
    },

    setBg(idx) {
      if (this.transitioning || idx === this.currentBg || this.debounceTimeout) return;
      this.stopInterval();
      this.performTransition(idx);
      this.startInterval();
    },

    init() {
      this.startInterval();
    }
  }"
  x-init="init"
  class="relative flex-1 flex flex-col justify-center items-center w-full h-full min-h-[calc(100vh-4rem)] overflow-hidden"
>

  {{-- Background Layers --}}
  <div
    class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1000ms] ease-cubic-bezier opacity-100 z-10"
    :class="frontLayer ? 'opacity-100' : 'opacity-0'"
    :style="`background-image: url('${heroImages[currentBg]}'); background-color: transparent; transform: translateZ(0); will-change: opacity, transform`"
  ></div>

  <div
    class="absolute inset-0 bg-center bg-cover transition-opacity duration-[1000ms] ease-cubic-bezier opacity-0 z-0"
    :class="!frontLayer ? 'opacity-100' : 'opacity-0'"
    :style="`background-image: url('${heroImages[nextBg]}'); background-color: transparent; transform: translateZ(0); will-change: opacity, transform`"
  ></div>

  {{-- Dark overlay --}}
  <div class="absolute inset-0 z-20 pointer-events-none bg-black/60 backdrop-blur-sm"></div>

  {{-- Login Card --}}
  <div
    class="relative z-30 w-full max-w-md p-6 mx-auto shadow-lg bg-white/30 rounded-xl backdrop-blur-md"
  >
    <h2 class="mb-6 text-2xl font-semibold text-center text-green-700">Login Admin Azka Garden</h2>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
        {{ session('error') }}
      </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
      <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Login Form --}}
    <form
      method="POST"
      action="{{ route('admin.login.submit') }}"
      novalidate
    >
      @csrf

      {{-- Email --}}
      <div class="mb-4">
        <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          value="{{ old('email') }}"
          required
          autofocus
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror"
        />
        @error('email')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      <div class="relative mb-6" x-data="{ show: false }">
        <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
        <input
          id="password"
          name="password"
          :type="show ? 'text' : 'password'"
          required
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror"
        />
        <button
          type="button"
          class="absolute text-gray-500 right-3 top-9 hover:text-green-700 focus:outline-none"
          @click="show = !show"
          aria-label="Toggle password visibility"
        >
          <template x-if="!show">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z
                   M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.477 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.065 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </template>
          <template x-if="show">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19
                   c-4.477 0-8.268-2.943-9.542-7
                   a9.985 9.985 0 012.016-3.436
                   M3 3l18 18" />
            </svg>
          </template>
        </button>
        @error('password')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <button
        type="submit"
        class="w-full py-2 font-semibold text-white transition bg-green-600 rounded-md hover:bg-green-700"
      >
        Login
      </button>
    </form>

    {{-- Switch to Register --}}
    <p class="mt-6 text-sm text-center text-white/90">
      Belum punya akun admin?
      <a href="{{ route('admin.register') }}" class="font-semibold text-green-400 hover:underline">
        Daftar sekarang
      </a>
    </p>
  </div>

  {{-- Slider Controls --}}
  <div class="relative z-30 flex items-center justify-center w-full max-w-md gap-3 px-4 py-2 mx-auto mt-4 rounded-full select-none bg-black/40">
    <button
      @click="prevBackground"
      aria-label="Background Sebelumnya"
      class="flex items-center justify-center w-8 h-8 text-white transition rounded-full hover:bg-black/60"
    >
      &#8592;
    </button>

    <template x-for="(img, idx) in heroImages" :key="'dot-'+idx">
      <span
        :class="{
          'w-5 h-3 rounded-full bg-green-500 cursor-pointer shadow': idx === currentBg,
          'w-3 h-3 rounded-full bg-white/60 cursor-pointer': idx !== currentBg
        }"
        @click="setBg(idx)"
        :aria-label="`Pilih background ke-` + (idx + 1)"
      ></span>
    </template>

    <button
      @click="nextBgManual"
      aria-label="Background Selanjutnya"
      class="flex items-center justify-center w-8 h-8 text-white transition rounded-full hover:bg-black/60"
    >
      &#8594;
    </button>
  </div>
</section>
@endsection
