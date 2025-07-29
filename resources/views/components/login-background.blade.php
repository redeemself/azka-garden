{{-- resources/views/components/login-background.blade.php --}}
<section
  x-data="{
    heroImages: [
      '{{ asset('images/hero-1.jpg') }}',
      '{{ asset('images/hero-2.jpg') }}',
      '{{ asset('images/hero-3.jpg') }}',
      '{{ asset('images/hero-4.jpg') }}',
      '{{ asset('images/hero-5.jpg') }}',
      '{{ asset('images/hero-6.jpg') }}',
      '{{ asset('images/hero-7.jpg') }}',
    ],
    currentBg: 0,
    nextBg: 1,
    frontLayer: true,
    transitioning: false,
    interval: null,
    transitionDuration: 1000,

    startInterval() {
      this.stopInterval();
      this.interval = setInterval(() => this.nextBackground(), 10000);
    },
    stopInterval() {
      clearInterval(this.interval);
      this.interval = null;
    },
    performTransition(to) {
      if (this.transitioning || to === this.currentBg) return;
      this.transitioning = true;
      this.nextBg = to;
      this.frontLayer = !this.frontLayer;
      setTimeout(() => {
        this.currentBg = to;
        this.transitioning = false;
      }, this.transitionDuration);
    },
    nextBackground() {
      this.performTransition((this.currentBg + 1) % this.heroImages.length);
    },
    prevBackground() {
      this.performTransition((this.currentBg - 1 + this.heroImages.length) % this.heroImages.length);
    },
    init() {
      this.startInterval();
    }
  }"
  x-init="init"
  class="relative flex flex-col justify-center items-center w-full min-h-[calc(100vh-4rem)] overflow-hidden"
>
  {{-- Lapis BG depan --}}
  <div
    class="absolute inset-0 transition-opacity bg-center bg-cover"
    :style="`background-image:url('${heroImages[currentBg]}')`"
    :class="frontLayer ? 'opacity-100 z-10' : 'opacity-0 z-0'"
    style="transition:opacity 1000ms;"
  ></div>

  {{-- Lapis BG belakang --}}
  <div
    class="absolute inset-0 transition-opacity bg-center bg-cover"
    :style="`background-image:url('${heroImages[nextBg]}')`"
    :class="!frontLayer ? 'opacity-100 z-10' : 'opacity-0 z-0'"
    style="transition:opacity 1000ms;"
  ></div>

  {{-- Overlay gelap --}}
  <div class="absolute inset-0 bg-black/30 backdrop-blur-sm z-5 pointer-events-none"></div>

  {{-- Slot untuk konten form (login/register card) --}}
  <div class="relative z-20 w-full max-w-md p-6 mx-auto shadow-lg bg-white/30 rounded-xl backdrop-blur-md">
    {{ $slot }}
  </div>

  {{-- Kontrol manual --}}
  <div class="absolute bottom-10 flex items-center space-x-2 z-20">
    <button @click="prevBackground" class="px-3 py-1 text-white bg-blue-600 rounded">&larr;</button>
    <template x-for="(_, idx) in heroImages" :key="idx">
      <button
        @click="performTransition(idx)"
        :class="idx===currentBg ? 'bg-blue-500' : 'bg-white/40'"
        class="w-4 h-2 rounded-full transition-colors"
      ></button>
    </template>
    <button @click="nextBackground" class="px-3 py-1 text-white bg-blue-600 rounded">&rarr;</button>
  </div>
</section>
