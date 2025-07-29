<?php $__env->startSection('title', 'Daftar Artikel Lengkap Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<section class="min-h-screen py-12 bg-green-50">
  <div class="max-w-6xl px-4 mx-auto space-y-12 sm:px-6">

    <header class="max-w-3xl mx-auto text-center">
      <h1 class="mb-4 text-4xl font-extrabold text-green-800 drop-shadow-sm">
        Daftar Lengkap Artikel Azka Garden
      </h1>
      <p class="text-lg leading-relaxed text-green-900/90">
        Koleksi artikel lengkap tanaman hias, bibit buah, media tanam, dan panduan perawatan yang dikompilasi dari sumber terpercaya.
      </p>
    </header>

    
    <form method="GET" action="<?php echo e(route('artikel.index')); ?>" class="max-w-md mx-auto mb-8">
      <div class="flex">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="w-full px-4 py-2 border border-green-300 rounded-l focus:outline-none focus:border-green-500" placeholder="Cari artikel tanaman hias, bibit buah ...">
        <button type="submit" class="px-4 py-2 font-semibold text-white bg-green-700 rounded-r hover:bg-green-800">Cari</button>
      </div>
    </form>

    
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <article class="p-6 break-words transition-shadow duration-300 bg-white border border-green-200 shadow-sm rounded-xl hover:shadow-lg">
        <h2 class="mb-2 text-xl font-semibold text-green-700 transition-colors hover:text-green-900">
          <a href="<?php echo e($post['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Baca artikel: <?php echo e($post['title'] ?? 'Artikel'); ?>">
            <?php echo e($post['title'] ?? 'Judul Tidak Tersedia'); ?>

          </a>
        </h2>
        <p class="mb-4 text-justify text-gray-700">
            <?php echo e(isset($post['excerpt']) ? $post['excerpt'] : 'Ringkasan belum tersedia.'); ?>

        </p>
        <?php if(!empty($post['date'])): ?>
        <time datetime="<?php echo e(\Carbon\Carbon::parse($post['date'])->format('Y-m-d')); ?>" class="text-sm text-green-500">
          <?php echo e(\Carbon\Carbon::parse($post['date'])->translatedFormat('d F Y')); ?>

        </time>
        <?php endif; ?>
      </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-span-2 p-6 text-center text-green-700 bg-white border border-green-200 rounded-xl">
        Tidak ditemukan artikel yang relevan dengan pencarian <b><?php echo e(request('search')); ?></b>.
      </div>
      <?php endif; ?>
    </div>

    
    <div class="max-w-6xl mx-auto">
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2">
        <a href="https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw" target="_blank"
           class="flex flex-col justify-center h-full p-5 transition bg-white border border-green-200 shadow rounded-xl hover:shadow-lg hover:border-green-400 group">
          <div class="mb-2 text-lg font-semibold text-green-700 group-hover:text-green-800">
            Kanal YouTube Azka Garden Indonesia
          </div>
          <div class="text-sm text-gray-700">
            Video koleksi, update stok, dan tips Azka Garden.
          </div>
        </a>
        <a href="https://www.instagram.com/azka_garden/" target="_blank"
           class="flex flex-col justify-center h-full p-5 transition bg-white border border-green-200 shadow rounded-xl hover:shadow-lg hover:border-green-400 group">
          <div class="mb-2 text-lg font-semibold text-green-700 group-hover:text-green-800">
            Instagram @azka_garden
          </div>
          <div class="text-sm text-gray-700">
            Katalog, promosi, dan update stok Azka Garden.
          </div>
        </a>
        <a href="https://semuabis.com" target="_blank"
           class="flex flex-col justify-center h-full p-5 transition bg-white border border-green-200 shadow rounded-xl hover:shadow-lg hover:border-green-400 group">
          <div class="mb-2 text-lg font-semibold text-green-700 group-hover:text-green-800">
            Profil Toko Bunga Hendrik / Azka Garden
          </div>
          <div class="text-sm text-gray-700">
            Profil bisnis, alamat, dan katalog Azka Garden.
          </div>
        </a>
        <a href="https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA" target="_blank"
           class="flex flex-col justify-center h-full p-5 transition bg-white border border-green-200 shadow rounded-xl hover:shadow-lg hover:border-green-400 group">
          <div class="mb-2 text-lg font-semibold text-green-700 group-hover:text-green-800">
            Google Maps Toko Bunga Hendrik
          </div>
          <div class="text-sm text-gray-700">
            Lokasi, review pelanggan, dan dokumentasi Azka Garden.
          </div>
        </a>
      </div>
    </div>

    
    <div class="flex flex-col items-center max-w-6xl gap-4 mx-auto mt-8 sm:flex-row sm:justify-center">
        <a href="<?php echo e(route('blog.index')); ?>"
            class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-center text-green-800 transition-all bg-green-100 rounded-lg shadow sm:w-auto hover:text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 sm:text-lg"
            aria-label="Ke Blog Azka Garden">
            <!-- Simbol Blog -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 5v14h14V5H5zm2 2h10v10H7V7zm2 2v6m2-6v6m2-6v6" />
            </svg>
            Ke Blog Azka Garden
        </a>
        <a href="<?php echo e(route('faq')); ?>"
            class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-center text-green-800 transition-all bg-green-100 rounded-lg shadow sm:w-auto hover:text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 sm:text-lg"
            aria-label="FAQ">
            <!-- Simbol FAQ -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8c0-4.418 4.03-8 9-8s9 3.582 9 8zm-9 4h.01" />
            </svg>
            FAQ
        </a>
    </div>

    
    <div class="max-w-6xl mx-auto">
      <div class="p-4 mb-4 overflow-x-auto bg-white border-2 border-green-300 shadow-none rounded-xl sm:p-6" style="box-shadow: 0 0 0 2px #4ade80;">
        <div class="max-w-full break-words">
          <?php echo $__env->make('artikel._referensi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      </div>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/artikel/index.blade.php ENDPATH**/ ?>