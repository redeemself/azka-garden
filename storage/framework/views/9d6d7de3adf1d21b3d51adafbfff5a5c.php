<?php $__env->startSection('title', 'Login - Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<section
  x-data="{
    heroImages: [
      '<?php echo e(asset('images/hero-1.jpg')); ?>',
      '<?php echo e(asset('images/hero-2.jpg')); ?>',
      '<?php echo e(asset('images/hero-3.jpg')); ?>',
      '<?php echo e(asset('images/hero-4.jpg')); ?>',
      '<?php echo e(asset('images/hero-5.jpg')); ?>',
      '<?php echo e(asset('images/hero-6.jpg')); ?>',
      '<?php echo e(asset('images/hero-7.jpg')); ?>'
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

  
  <div class="absolute inset-0 z-20 pointer-events-none bg-black/60 backdrop-blur-sm"></div>

  
  <div
    class="relative z-30 w-full max-w-md p-6 mx-auto shadow-lg bg-white/30 rounded-xl backdrop-blur-md"
  >
    <h2 class="mb-6 text-2xl font-semibold text-center text-green-700">Login Pengguna Azka Garden</h2>

    
    <?php if(session('success')): ?>
      <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
        <?php echo e(session('success')); ?>

      </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
      <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
        <?php echo e(session('error')); ?>

      </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
      <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
        <ul class="list-disc list-inside">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>

    
    <form
      method="POST"
      action="<?php echo e(route('login.user.submit')); ?>"
      novalidate
    >
      <?php echo csrf_field(); ?>

      
      <div class="mb-4">
        <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          value="<?php echo e(old('email')); ?>"
          required
          autofocus
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        />
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="relative mb-6" x-data="{ show: false }">
        <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
        <input
          id="password"
          name="password"
          :type="show ? 'text' : 'password'"
          required
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <button
        type="submit"
        class="w-full py-2 font-semibold text-white transition bg-green-600 rounded-md hover:bg-green-700"
      >
        Login
      </button>
    </form>

    
    <p class="mt-6 text-sm text-center text-white/90">
      Belum punya akun?
      <a href="<?php echo e(route('register')); ?>" class="font-semibold text-green-400 hover:underline">
        Daftar sekarang
      </a>
    </p>
  </div>

  
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/auth/login.blade.php ENDPATH**/ ?>