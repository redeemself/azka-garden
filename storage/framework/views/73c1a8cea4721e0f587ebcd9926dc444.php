<?php $__env->startSection('title', 'Blog - Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-12 bg-green-50" x-data="{ showAll: false }">
  <div class="max-w-6xl px-4 mx-auto space-y-8 overflow-hidden sm:px-6">

    
    <h1 class="flex items-center gap-3 text-3xl font-extrabold text-green-700 md:text-4xl">
      <svg class="text-green-500 w-9 h-9 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path stroke-linejoin="round" stroke-linecap="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v8a2 2 0 01-2 2z" />
      </svg>
      Blog Azka Garden
    </h1>

    
    <p class="text-lg leading-relaxed text-gray-700 break-words max-w-prose">
      Selamat datang di halaman blog resmi Azka Garden. Temukan beragam artikel, tips, tutorial, dan cerita inspiratif tentang tanaman hias, budidaya hortikultura, penataan taman, serta aktivitas terbaru kami. Konten kami disajikan untuk membantu Anda berkebun dengan mudah, baik bagi pemula maupun yang sudah berpengalaman.
    </p>

    
    <form action="<?php echo e(route('blog.search')); ?>" method="GET" class="flex w-full max-w-md gap-2 mb-8" role="search" aria-label="Form pencarian artikel">
      <input
        type="text"
        name="q"
        value="<?php echo e(old('q', $query ?? '')); ?>"
        placeholder="Cari artikel..."
        class="flex-grow min-w-0 px-4 py-2 border border-green-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-green-400"
        aria-label="Kata kunci pencarian"
      />
      <select name="source" aria-label="Pilih sumber pencarian" class="min-w-0 px-3 border-t border-b border-green-300 focus:outline-none">
        <option value="web" <?php echo e((isset($source) && $source === 'web') ? 'selected' : ''); ?>>Web</option>
        <option value="scholar" <?php echo e((isset($source) && $source === 'scholar') ? 'selected' : ''); ?>>Scholar</option>
      </select>
      <button
        type="submit"
        class="px-4 py-2 font-semibold text-white bg-green-600 rounded-r-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400"
        aria-label="Tombol cari artikel"
      >
        Cari
      </button>
    </form>

    
    <section aria-label="Kata kunci pencarian relevan" class="max-w-6xl px-4 mx-auto mb-12">
      <h2 class="pb-2 mb-6 text-xl font-semibold text-green-800 border-b-2 border-green-300">Kata Kunci Relevan untuk Pencarian</h2>
      <div class="space-y-2 text-sm font-medium text-green-900 select-none">
        <div class="px-2 py-1 text-center transition rounded cursor-pointer hover:bg-green-100">
          Azka, Garden, Hortikultura, Pak, Hendrik, Kios, Tanaman, Depok, Jawa, Barat,
        </div>
        <div class="px-2 py-1 text-center transition rounded cursor-pointer hover:bg-green-100">
          Kanal, Edukasi, Daring, Usaha, Jasa, Lanskap, Penjualan, Philodendron, Caladium, Media,
        </div>
        <div class="px-2 py-1 text-center transition rounded cursor-pointer hover:bg-green-100">
          Tanam, Organik, Pupuk, Renovasi, Taman, Kolam, YouTube, Facebook, Instagram, Lokasi,
        </div>
        <div class="px-2 py-1 text-center transition rounded cursor-pointer hover:bg-green-100">
          HRQH+3VP, Jalan, Raya, Jam, Buka, Nursery, Penyedia, Bibit, Perlengkapan, Variasi,
        </div>
        <div class="px-2 py-1 text-center transition rounded cursor-pointer hover:bg-green-100">
          Yogyakarta, Pekanbaru, Batam, Sinergi, Bisnis, Luring, Daring, Produk, Pagar, Hidup,
          Pucuk, Merah, Tutorial, Budidaya, Konsultasi, WhatsApp, Channel, Subscriber, Referensi
        </div>
      </div>
    </section>

    
    <div class="max-w-6xl px-4 mx-auto">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e($post['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer"
            class="flex flex-col justify-center h-full p-5 transition bg-white border border-green-200 shadow rounded-xl hover:shadow-lg hover:border-green-400 group">
            <div class="mb-2 text-lg font-semibold text-green-700 break-words group-hover:text-green-800">
            <?php echo e($post['title'] ?? 'Judul Tidak Tersedia'); ?>

            </div>
            <div class="mb-2 text-sm text-gray-700 break-words">
            <?php echo e($post['excerpt'] ?? ''); ?>

            </div>
            <?php if(!empty($post['date'])): ?>
            <time datetime="<?php echo e(\Carbon\Carbon::parse($post['date'])->format('Y-m-d')); ?>" class="mt-auto text-sm text-green-500"><?php echo e($post['date']); ?></time>
            <?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="col-span-2 text-center text-gray-600">Tidak ada artikel ditemukan.</p>
        <?php endif; ?>
    </div>

  
  <div class="flex flex-wrap justify-center gap-4 mt-8">
    <a href="<?php echo e(route('artikel.index')); ?>"
       class="inline-flex items-center px-6 py-3 min-w-[140px] text-center font-bold text-green-800 bg-green-100 rounded-lg shadow hover:text-green-700 hover:bg-green-200 transition-all focus:outline-none focus:ring-2 focus:ring-green-400 text-lg gap-2"
       aria-label="Lihat semua artikel">
      <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 4v16M8 4v16M4 8h16"/></svg>
      Artikel
    </a>
    <a href="<?php echo e(route('faq')); ?>"
       class="inline-flex items-center px-6 py-3 min-w-[140px] text-center font-bold text-green-800 bg-green-100 rounded-lg shadow hover:text-green-700 hover:bg-green-200 transition-all focus:outline-none focus:ring-2 focus:ring-green-400 text-lg gap-2"
       aria-label="FAQ">
      <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
      FAQ
    </a>
  </div>
</div>

    <section class="max-w-6xl p-4 mx-auto mt-16 overflow-x-auto bg-white border-2 border-green-300 shadow-none rounded-xl sm:p-6" style="box-shadow: 0 0 0 2px #4ade80;">
    <h3 class="pb-2 mb-6 text-2xl font-semibold text-green-700 border-b border-green-300">
        Referensi & Sumber Lengkap Azka Garden
    </h3>
    <ol class="max-w-full pl-6 space-y-3 text-sm leading-relaxed text-green-900 break-words">
        <!-- Profil, Lokasi, Media Sosial -->
        <li><span class="mr-2 font-semibold text-green-700">[1]</span>
        <a href="https://semuabis.com" target="_blank" class="underline hover:text-green-600">
            Profil Toko Bunga Hendrik / Azka Garden di semuabis.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[2]</span>
        Lokasi kios tanaman di Depok, HRQH+3VP Jalan Raya KSU
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[3]</span>
        Kanal YouTube Azka Garden Indonesia:
        <a href="https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw" target="_blank" class="underline hover:text-green-600">
            youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[4]</span>
        Instagram resmi @azka_garden:
        <a href="https://www.instagram.com/azka_garden/" target="_blank" class="underline hover:text-green-600">
            instagram.com/azka_garden
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[5]</span>
        Google Maps Toko Bunga Hendrik:
        <a href="https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA" target="_blank" class="underline hover:text-green-600">
            maps.app.goo.gl/j5AuLF1AZ3VVgovcA
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[6]</span>
        Observasi lapangan, spanduk fisik, katalog jalan raya KSU, dan review pelanggan
        </li>

        <!-- Artikel dan Panduan Tanaman Hias -->
        <li><span class="mr-2 font-semibold text-green-700">[7]</span>
        Cara Mudah Merawat Caladium, Monstera, dan Aglonema —
        <a href="https://www.cnnindonesia.com/gaya-hidup/20201106090411-277-566576/cara-mudah-merawat-caladium-monstera-dan-aglonema" target="_blank" class="underline hover:text-green-600">
            cnnindonesia.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[8]</span>
        Mengenal Monstera, Salah Satu Jenis Aroid Paling Populer —
        <a href="https://www.kelaskita.com/artikel/mengenal-monstera-salah-satu-jenis-aroid" target="_blank" class="underline hover:text-green-600">
            kelaskita.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[9]</span>
        Tips Merawat Philodendron dan Monstera agar Daunnya Cantik —
        <a href="https://www.planteria.id/tips-merawat-philodendron-dan-monstera/" target="_blank" class="underline hover:text-green-600">
            planteria.id
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[10]</span>
        Jual Bibit Alpukat: Jenis, Harga, dan Tips Tabulampot —
        <a href="https://www.bibitbuahku.com/jual-bibit-alpukat.htm" target="_blank" class="underline hover:text-green-600">
            bibitbuahku.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[11]</span>
        Cara Menanam Mangga Alpukat Agar Cepat Berbuah —
        <a href="https://taniuntung.com/cara-menanam-mangga-alpukat/" target="_blank" class="underline hover:text-green-600">
            taniuntung.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[12]</span>
        Daftar Harga Bibit Buah Siap Tanam (Update Terbaru) —
        <a href="https://tetanam.com/daftar-harga-bibit-buah-siap-tanam-update-terbaru/" target="_blank" class="underline hover:text-green-600">
            tetanam.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[13]</span>
        13 Rekomendasi Tanaman Hias Daun Cantik & Populer di Indonesia —
        <a href="https://www.intiland.com/id/blog/rekomendasi-tanaman-hias-daun-cantik/" target="_blank" class="underline hover:text-green-600">
            intiland.com
        </a>
        </li>

        <!-- Artikel & Panduan Lainnya -->
        <li><span class="mr-2 font-semibold text-green-700">[14]</span>
        5 Tips Merawat Tanaman Hias Dolar atau ZZ Plant —
        <a href="https://www.haibunda.com/moms-life/20220805114902-76-280780/5-tips-merawat-tanaman-hias-dolar-atau-zz-plant-bagus-diletakkan-di-dalam-ruangan" target="_blank" class="underline hover:text-green-600">
            haibunda.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[15]</span>
        Cara Menanam dan Merawat Pakis —
        <a href="https://gdm.id/cara-menanam-pakis/" target="_blank" class="underline hover:text-green-600">
            gdm.id
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[16]</span>
        Tips Merawat Bonsai Beringin —
        <a href="https://gdm.id/cara-merawat-bonsai-beringin/" target="_blank" class="underline hover:text-green-600">
            gdm.id
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[17]</span>
        Panduan Memilih Pot Tanaman Hias —
        <a href="https://www.99.co/id/panduan/tips-memilih-pot-tanaman-hias/" target="_blank" class="underline hover:text-green-600">
            99.co
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[18]</span>
        Tips Memilih Pot untuk Tanaman Hias (Kompas.com) —
        <a href="https://www.kompas.com/homey/read/2021/08/19/070000176/tips-memilih-pot-untuk-tanaman-hias" target="_blank" class="underline hover:text-green-600">
            kompas.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[19]</span>
        Tips Memilih dan Merawat Batu Taman —
        <a href="https://www.casaindonesia.com/article/read/4/2020/1902/Tips-Pilih-Batu-Taman-dan-Perawatannya" target="_blank" class="underline hover:text-green-600">
            casaindonesia.com
        </a>
        </li>

        <!-- Marketplace dan Lainnya -->
        <li><span class="mr-2 font-semibold text-green-700">[20]</span>
        Jenis Tanaman Keladi Dragon Scale dan Harganya —
        <a href="https://artikel.rumah123.com/jenis-tanaman-keladi-paling-diminati-dan-harganya-75996" target="_blank" class="underline hover:text-green-600">
            rumah123.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[21]</span>
        Jamani Dolar (ZZ Plant) —
        <a href="https://www.haibunda.com/moms-life/20220805114902-76-280780/5-tips-merawat-tanaman-hias-dolar-atau-zz-plant-bagus-diletakkan-di-dalam-ruangan" target="_blank" class="underline hover:text-green-600">
            haibunda.com
        </a>
        </li>
        <li><span class="mr-2 font-semibold text-green-700">[22]</span>
        Jamani Cobra —
        <a href="https://indonesian.alibaba.com/product-detail/jamani-cobra-tanaman-hias-1600189364088.html" target="_blank" class="underline hover:text-green-600">
            alibaba.com
        </a>
        </li>
    </ol>
    </section>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/blog/index.blade.php ENDPATH**/ ?>