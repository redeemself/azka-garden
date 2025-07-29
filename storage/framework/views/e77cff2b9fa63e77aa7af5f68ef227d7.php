<?php $__env->startSection('title', 'Sitemap Website Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="min-h-screen py-12 bg-green-50">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-10">

    <!-- Header -->
    <header class="max-w-3xl mx-auto text-center mb-2">
      <h1 class="mb-4 text-4xl font-extrabold text-green-800 drop-shadow-sm tracking-tight">
        Sitemap Website Azka Garden
      </h1>
      <p class="text-lg leading-relaxed text-green-900/90">
        Klik pada setiap kotak di diagram untuk langsung menuju halaman tersebut!
      </p>
    </header>

    <!-- Visual Sitemap: Sistematis dan Tidak Saling Menghalangi -->
    <div class="bg-white border-2 border-green-200 rounded-xl shadow p-8 mb-10 flex justify-center overflow-x-auto">
      <svg viewBox="0 0 1000 540" width="950" height="440" class="max-w-full h-auto">
        <defs>
          <marker id="arrow" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="6" markerHeight="6" orient="auto">
            <path d="M 0 0 L 10 5 L 0 10 z" fill="#22c55e"/>
          </marker>
          <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#a7f3d0" flood-opacity="0.5"/>
          </filter>
        </defs>
        <!-- Beranda Node -->
        <a xlink:href="<?php echo e(url('/')); ?>">
          <rect x="440" y="40" width="120" height="43" rx="16" fill="#bbf7d0" stroke="#22c55e" stroke-width="2" filter="url(#shadow)"/>
          <text x="500" y="70" font-size="22" font-weight="bold" text-anchor="middle" fill="#166534">Beranda</text>
        </a>
        <!-- Level 1 Nodes -->
        <a xlink:href="<?php echo e(url('/products')); ?>">
          <rect x="180" y="140" width="120" height="38" rx="12" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="240" y="165" font-size="16" text-anchor="middle" fill="#166534">Produk</text>
        </a>
        <a xlink:href="<?php echo e(url('/layanan')); ?>">
          <rect x="440" y="140" width="120" height="38" rx="12" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="500" y="165" font-size="16" text-anchor="middle" fill="#166534">Layanan</text>
        </a>
        <a xlink:href="<?php echo e(url('/blog')); ?>">
          <rect x="700" y="140" width="120" height="38" rx="12" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="760" y="165" font-size="16" text-anchor="middle" fill="#166534">Blog</text>
        </a>
        <!-- Produk children -->
        <a xlink:href="<?php echo e(url('/products/category')); ?>">
          <rect x="120" y="220" width="120" height="32" rx="9" fill="#e0f2fe" stroke="#22c55e" stroke-width="2"/>
          <text x="180" y="242" font-size="13" text-anchor="middle" fill="#166534">Kategori Produk</text>
        </a>
        <a xlink:href="<?php echo e(url('/products/search')); ?>">
          <rect x="240" y="220" width="120" height="32" rx="9" fill="#e0f2fe" stroke="#22c55e" stroke-width="2"/>
          <text x="300" y="242" font-size="13" text-anchor="middle" fill="#166534">Pencarian Produk</text>
        </a>
        <a xlink:href="<?php echo e(url('/faq')); ?>">
          <rect x="120" y="270" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="180" y="292" font-size="13" text-anchor="middle" fill="#166534">FAQ</text>
        </a>
        <a xlink:href="<?php echo e(url('/privacy')); ?>">
          <rect x="240" y="270" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="300" y="292" font-size="13" text-anchor="middle" fill="#166534">Privasi</text>
        </a>
        <a xlink:href="<?php echo e(url('/about')); ?>">
          <rect x="60" y="320" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="120" y="342" font-size="13" text-anchor="middle" fill="#166534">Tentang</text>
        </a>
        <a xlink:href="<?php echo e(url('/accessibility')); ?>">
          <rect x="180" y="320" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="240" y="342" font-size="13" text-anchor="middle" fill="#166534">Accessibility</text>
        </a>
        <a xlink:href="<?php echo e(url('/return-policy')); ?>">
          <rect x="300" y="320" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="360" y="342" font-size="13" text-anchor="middle" fill="#166534">Return Policy</text>
        </a>
        <!-- Layanan children -->
        <a xlink:href="<?php echo e(url('/contact')); ?>">
          <rect x="440" y="220" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="500" y="242" font-size="13" text-anchor="middle" fill="#166534">Kontak</text>
        </a>
        <a xlink:href="<?php echo e(url('/terms')); ?>">
          <rect x="440" y="270" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="500" y="292" font-size="13" text-anchor="middle" fill="#166534">Terms</text>
        </a>
        <a xlink:href="<?php echo e(url('/cookies')); ?>">
          <rect x="440" y="320" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="500" y="342" font-size="13" text-anchor="middle" fill="#166534">Cookies</text>
        </a>
        <!-- Blog children -->
        <a xlink:href="<?php echo e(url('/blog/search')); ?>">
          <rect x="700" y="220" width="120" height="32" rx="9" fill="#e0f2fe" stroke="#22c55e" stroke-width="2"/>
          <text x="760" y="242" font-size="13" text-anchor="middle" fill="#166534">Pencarian Blog</text>
        </a>
        <a xlink:href="<?php echo e(url('/artikel')); ?>">
          <rect x="700" y="270" width="120" height="32" rx="9" fill="#bbf7d0" stroke="#22c55e" stroke-width="2"/>
          <text x="760" y="292" font-size="13" text-anchor="middle" fill="#166534">Artikel</text>
        </a>
        <a xlink:href="<?php echo e(url('/artikel/search')); ?>">
          <rect x="700" y="320" width="120" height="32" rx="9" fill="#e0f2fe" stroke="#22c55e" stroke-width="2"/>
          <text x="760" y="342" font-size="13" text-anchor="middle" fill="#166534">Pencarian Artikel</text>
        </a>
        <a xlink:href="<?php echo e(url('/sitemap.xml')); ?>">
          <rect x="700" y="370" width="120" height="32" rx="9" fill="#e0f2fe" stroke="#22c55e" stroke-width="2"/>
          <text x="760" y="392" font-size="13" text-anchor="middle" fill="#166534">Sitemap XML (SEO)</text>
        </a>
        <!-- Lines: Beranda ke Level 1 -->
        <line x1="500" y1="83" x2="240" y2="140" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="500" y1="83" x2="500" y2="140" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="500" y1="83" x2="760" y2="140" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <!-- Produk ke children -->
        <line x1="240" y1="178" x2="180" y2="220" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="300" y2="220" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="180" y2="270" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="300" y2="270" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="120" y2="320" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="240" y2="320" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="240" y1="178" x2="360" y2="320" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <!-- Layanan ke children -->
        <line x1="500" y1="178" x2="500" y2="220" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="500" y1="178" x2="500" y2="270" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="500" y1="178" x2="500" y2="320" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <!-- Blog ke children -->
        <line x1="760" y1="178" x2="760" y2="220" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="760" y1="178" x2="760" y2="270" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="760" y1="178" x2="760" y2="320" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
        <line x1="760" y1="178" x2="760" y2="370" stroke="#22c55e" stroke-width="2" marker-end="url(#arrow)"/>
      </svg>
    </div>

    <!-- Kotak Pengguna: Guest, Customer, User -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
      <!-- Guest -->
      <div class="bg-white border-2 border-green-200 rounded-xl shadow-sm p-6">
        <h2 class="text-green-800 text-xl font-bold mb-3">Guest</h2>
        <ul class="space-y-2 text-green-800 text-base">
          <li><a href="<?php echo e(url('/login')); ?>" class="hover:text-green-700 transition">Login</a></li>
          <li><a href="<?php echo e(url('/register')); ?>" class="hover:text-green-700 transition">Register</a></li>
          <li><a href="<?php echo e(url('/faq')); ?>" class="hover:text-green-700 transition">FAQ</a></li>
          <li><a href="<?php echo e(url('/privacy')); ?>" class="hover:text-green-700 transition">Privacy</a></li>
          <li><a href="<?php echo e(url('/terms')); ?>" class="hover:text-green-700 transition">Terms</a></li>
          <li><a href="<?php echo e(url('/contact')); ?>" class="hover:text-green-700 transition">Contact</a></li>
        </ul>
      </div>
      <!-- Customer -->
      <div class="bg-white border-2 border-green-200 rounded-xl shadow-sm p-6">
        <h2 class="text-green-800 text-xl font-bold mb-3">Customer</h2>
        <ul class="space-y-2 text-green-800 text-base">
          <li><a href="<?php echo e(url('/profile')); ?>" class="hover:text-green-700 transition">Profile</a></li>
          <li><a href="<?php echo e(url('/profile/orders')); ?>" class="hover:text-green-700 transition">Orders</a></li>
          <li><a href="<?php echo e(url('/profile/payments')); ?>" class="hover:text-green-700 transition">Payments</a></li>
          <li><a href="<?php echo e(url('/profile/shipping')); ?>" class="hover:text-green-700 transition">Shipping</a></li>
          <li><a href="<?php echo e(url('/profile/addresses')); ?>" class="hover:text-green-700 transition">Addresses</a></li>
          <li><a href="<?php echo e(url('/profile/reviews')); ?>" class="hover:text-green-700 transition">Reviews</a></li>
          <li><a href="<?php echo e(url('/cart')); ?>" class="hover:text-green-700 transition">Cart</a></li>
          <li><a href="<?php echo e(url('/checkout')); ?>" class="hover:text-green-700 transition">Checkout</a></li>
        </ul>
      </div>
      <!-- User -->
      <div class="bg-white border-2 border-green-200 rounded-xl shadow-sm p-6">
        <h2 class="text-green-800 text-xl font-bold mb-3">User</h2>
        <ul class="space-y-2 text-green-800 text-base">
          <li><a href="<?php echo e(url('/profile')); ?>" class="hover:text-green-700 transition">Profile</a></li>
          <li><a href="<?php echo e(url('/profile/edit')); ?>" class="hover:text-green-700 transition">Edit Profile</a></li>
          <li><a href="<?php echo e(url('/profile/orders')); ?>" class="hover:text-green-700 transition">Orders</a></li>
          <li><a href="<?php echo e(url('/profile/payments')); ?>" class="hover:text-green-700 transition">Payments</a></li>
          <li><a href="<?php echo e(url('/profile/shipping')); ?>" class="hover:text-green-700 transition">Shipping</a></li>
          <li><a href="<?php echo e(url('/profile/addresses')); ?>" class="hover:text-green-700 transition">Addresses</a></li>
          <li><a href="<?php echo e(url('/profile/reviews')); ?>" class="hover:text-green-700 transition">Reviews</a></li>
        </ul>
      </div>
    </div>

    <!-- Mulai Google Maps dan fitur Ringkasan, Ulasan, Tentang -->
    <div class="bg-white border-2 border-green-200 rounded-xl shadow p-8 mb-10">
        <h2 class="text-2xl font-bold text-green-800 mb-4">Lokasi Toko Azka Garden (Depok)</h2>
        <div class="mb-2 text-green-700 text-base">
            <span class="font-semibold">Alamat:</span>
            Jl. Raya KSU, Tirtajaya, Sukmajaya, Kota Depok, Jawa Barat 16412<br>
            <span class="font-semibold">Telepon/WhatsApp:</span> 0896-3508-6182
        </div>
        <div class="overflow-hidden rounded-xl border mt-4" style="height:350px;">
            <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0281919127647!2d106.829692!3d-6.4122794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ebaf7dd7316d%3A0x91c591170331d44a!2sToko%20Bunga%20Hendrik!5e0!3m2!1sid!2sid!4v1721403469172!5m2!1sid!2sid"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="mt-4">
            <a href="https://www.google.com/maps/place/Toko+Bunga+Hendrik/@-6.4122794,106.829692,17z/data=!4m8!3m7!1s0x2e69ebaf7dd7316d:0x91c591170331d44a!8m2!3d-6.4122794!4d106.829692!9m1!1b1!16s%2Fg%2F11h0475ws5?entry=ttu"
            target="_blank"
            class="inline-block px-5 py-2 mt-2 bg-green-700 text-white rounded hover:bg-green-800 font-bold">
            Buka Google Maps Lengkap (Ringkasan, Ulasan, Tentang)
            </a>
            <div class="mt-4 text-green-700 text-base">
            <ul class="list-disc pl-4">
                <li><b>Ringkasan:</b> Info bisnis, rating, jam buka, kontak.</li>
                <li><b>Ulasan:</b> Lihat & tulis ulasan pelanggan langsung.</li>
                <li><b>Tentang:</b> Detail bisnis & lokasi.</li>
                <li><b>Foto:</b> Kiriman & upload foto pengunjung.</li>
                <li><b>Petunjuk Arah:</b> Rute ke lokasi toko.</li>
                <li><b>Bagikan:</b> Salin/kirim tautan lokasi ke teman.</li>
            </ul>
            <p class="mt-2">
                Semua fitur di atas bisa diakses <b>langsung</b> melalui halaman Google Maps dari tombol di atas.
            </p>
            </div>
        </div>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/sitemap.blade.php ENDPATH**/ ?>