<?php $__env->startSection('title', 'Layanan Kami'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-12 bg-green-50">
  <div class="px-4 mx-auto max-w-7xl sm:px-8"> 

    <h1 class="mb-10 text-3xl font-extrabold text-center text-green-800 md:text-4xl">
      Layanan Kami
    </h1>

    
    <div class="relative mx-auto max-w-7xl"> 
      <div class="absolute inset-y-0 left-0 w-1 border-l-2 border-green-200 rounded-full pointer-events-none"></div>
      <div class="absolute inset-y-0 right-0 w-1 border-r-2 border-green-200 rounded-full pointer-events-none"></div>

      <div class="grid grid-cols-1 gap-8 px-4 pt-4 pb-4 md:grid-cols-3 lg:grid-cols-3 sm:px-8">
        
        <div class="flex flex-col items-center p-8 text-center bg-white border border-green-200 shadow rounded-xl">
          <span class="inline-block p-4 mb-4 text-2xl bg-green-100 rounded-full">🌳</span>
          <h2 class="mb-2 text-xl font-bold text-green-800">Jasa Bikin Taman</h2>
          <p class="text-green-700">Pembuatan taman profesional</p>
        </div>
        <div class="flex flex-col items-center p-8 text-center bg-white border border-green-200 shadow rounded-xl">
          <span class="inline-block p-4 mb-4 text-2xl bg-green-100 rounded-full">🪴</span>
          <h2 class="mb-2 text-xl font-bold text-green-800">Jasa Landscape</h2>
          <p class="text-green-700">Penataan landscape rumah</p>
        </div>
        <div class="flex flex-col items-center p-8 text-center bg-white border border-green-200 shadow rounded-xl">
          <span class="inline-block p-4 mb-4 text-2xl bg-green-100 rounded-full">🐟</span>
          <h2 class="mb-2 text-xl font-bold text-green-800">Jasa Kolam Ikan</h2>
          <p class="text-green-700">Desain kolam ikan minimalis</p>
        </div>
        <div class="flex flex-col items-center p-8 text-center bg-white border border-green-200 shadow rounded-xl">
          <span class="inline-block p-4 mb-4 text-2xl bg-green-100 rounded-full">🏡</span>
          <h2 class="mb-2 text-xl font-bold text-green-800">Renovasi Taman</h2>
          <p class="text-green-700">Renovasi dan perbaikan taman</p>
        </div>
        <div class="flex flex-col items-center p-8 text-center bg-white border border-green-200 shadow rounded-xl">
          <span class="inline-block p-4 mb-4 text-2xl bg-green-100 rounded-full">📐</span>
          <h2 class="mb-2 text-xl font-bold text-green-800">Konsultasi Desain</h2>
          <p class="text-green-700">Konsultasi desain taman</p>
        </div>
      </div>
    </div>

    
    <div class="flex justify-center mt-16">
      <div class="flex flex-col items-center w-full max-w-md p-8 border border-green-100 shadow-lg bg-white/80 rounded-xl">
        <h2 class="mb-2 text-xl font-bold text-center text-green-800">Butuh Konsultasi Desain atau Penawaran Khusus?</h2>
        <p class="mb-6 text-base text-center text-green-700">Hubungi tim Azka Garden untuk konsultasi gratis, penawaran khusus, atau pertanyaan lainnya seputar layanan kami.</p>
        <div class="flex justify-center w-full gap-3">
          <a href="https://wa.me/6289635086182" target="_blank" class="flex items-center justify-center flex-1 gap-2 px-0 py-0">
            <button class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-white transition-all bg-green-600 rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
              <!-- SVG WhatsApp -->
              WhatsApp
            </button>
          </a>
          <a href="<?php echo e(route('contact')); ?>" class="flex items-center justify-center flex-1 gap-2 px-0 py-0">
            <button class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-green-700 transition-all bg-green-100 border border-green-300 rounded-lg shadow hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400">
              <!-- SVG Kontak -->
              Kontak
            </button>
          </a>
        </div>
      </div>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/services/index.blade.php ENDPATH**/ ?>