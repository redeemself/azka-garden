<?php $__env->startSection('title', 'FAQ & Katalog Tanaman Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<section class="w-full min-h-screen overflow-x-hidden bg-gradient-to-br from-green-50 via-white to-green-100">
    <div class="box-border w-full max-w-6xl px-2 mx-auto md:max-w-6xl md:px-8">
        <!-- Judul Halaman -->
        <h2 class="flex items-center gap-3 mb-8 text-3xl font-extrabold text-left text-green-700 md:text-4xl">
            <svg class="text-green-500 w-9 h-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
            </svg>
            FAQ & Katalog Tanaman Azka Garden
        </h2>

        <!-- Profil & Ringkasan Usaha -->
        <article class="p-6 mb-8 border border-green-200 shadow bg-white/95 rounded-2xl">
            <h3 class="flex items-center gap-2 mb-3 text-xl font-bold text-green-800 md:text-2xl">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
                </svg>
                Profil & Ringkasan Usaha
            </h3>
            <div class="space-y-2 text-base leading-relaxed text-gray-700 md:text-lg">
                <p>
                    Azka Garden adalah usaha hortikultura keluarga Pak Hendrik di Depok, Jawa Barat, dengan kanal edukasi di YouTube, Instagram, Facebook, dan TikTok. Kios utama di <b>HRQH+3VP, Jalan Raya KSU, Kelurahan Tirtajaya, Sukmajaya, Depok</b>, buka 24 jam dengan WhatsApp <a href="tel:+6289635086182" class="font-semibold text-green-700 underline hover:text-green-900">0896-3508-6182</a>.
                </p>
                <p>
                    Produk utama: Philodendron, Caladium, pucuk merah, bibit buah, aglonema, sansevieria, monstera, peace lily, tanaman air, dan nursery Depok–Parung. Jasa: taman, kolam ikan, konsultasi lanskap. Katalog selalu update di YouTube & Instagram. Tersedia pot, pupuk, media tanam, perlengkapan taman.
                </p>
                <p>
                    Brand Azka Garden digunakan di berbagai kota Indonesia. Keaslian dapat diverifikasi melalui kanal resmi di bagian referensi.
                </p>
            </div>
        </article>

        <!-- FAQ Manual: Include dari faq_list.blade.php -->
        <article class="p-6 mb-8 border border-green-200 shadow bg-green-50 rounded-2xl">
            <h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-green-700 md:text-2xl">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/>
                </svg>
                Pertanyaan Umum (FAQ)
            </h3>
            <?php echo $__env->make('components.faq_list', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </article>

        <!-- Katalog Tanaman Hias Azka Garden -->
        <article class="p-6 mb-8 border border-green-200 shadow bg-white/95 rounded-2xl">
            <h3 class="flex items-center gap-2 mb-3 text-xl font-bold text-green-800 md:text-2xl">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="3" />
                    <circle cx="8.5" cy="12" r="2.5" />
                    <path d="M21 19l-5.5-5.5a2 2 0 00-2.8 0L3 19"/>
                </svg>
                Daftar Lengkap Tanaman Hias, Bibit Buah, Kolam, Pot & Media Tanam
            </h3>
            <p class="mb-2 text-base text-gray-700 md:text-lg">
                Katalog tanaman hias, bibit buah, tanaman kolam, pot, dan media tanam yang dijual/promosikan Azka Garden. Lengkap dengan link artikel perawatan & referensi.
            </p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                <div class="col-span-1">
                    <ul class="pl-5 space-y-2 text-base text-green-900 list-disc md:text-lg">
                        <li><strong>Philodendron</strong> <a href="https://www.planteria.id/tips-merawat-philodendron-dan-monstera/" target="_blank" class="inline-flex items-center gap-1 text-green-600 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>Tips Merawat</a></li>
                        <li><strong>Caladium (Keladi)</strong> <a href="https://www.cnnindonesia.com/gaya-hidup/20201106090411-277-566576/cara-mudah-merawat-caladium-monstera-dan-aglonema" target="_blank" class="inline-flex items-center gap-1 text-green-600 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>Tips Merawat</a></li>
                        <li><strong>Pucuk Merah (Syzygium oleina)</strong> <a href="https://www.99.co/id/panduan/tanaman-pucuk-merah/" target="_blank" class="inline-flex items-center gap-1 text-green-600 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>Profil & Perawatan</a></li>
                        <li><strong>Monstera</strong> <a href="https://www.kelaskita.com/artikel/mengenal-monstera-salah-satu-jenis-aroid" target="_blank" class="inline-flex items-center gap-1 text-green-600 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>Mengenal & Merawat</a></li>
                        <li><strong>Spider Plant</strong> <a href="https://www.99.co/id/panduan/tanaman-spider-plant/" target="_blank" class="inline-flex items-center gap-1 text-green-600 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>Cara Merawat</a></li>
                        <!-- dst ... -->
                    </ul>
                </div>
            </div>
            <p class="flex items-center gap-2 mt-3 text-sm text-gray-600">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                <strong>Catatan:</strong> Daftar di atas didasarkan pada katalog lapak, video, dan dokumen digital Azka Garden. Stok dan jenis tanaman dapat berubah sewaktu-waktu. Untuk detail/foto, cek katalog Instagram, YouTube, atau hubungi WA resmi.
            </p>
        </article>

        <!-- Tombol Artikel, Blog, dan Kontak - Konsisten warna, simbol, dan responsif -->
        <div class="flex flex-col items-center max-w-6xl gap-4 mx-auto mb-8 sm:flex-row sm:justify-center">
            <a href="<?php echo e(route('artikel.index')); ?>"
                class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-center text-green-800 transition-all bg-green-100 rounded-lg shadow sm:w-auto hover:text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 sm:text-lg"
                aria-label="Lihat halaman artikel Azka Garden">
                <!-- Simbol Artikel -->
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 4v16M8 4v16M4 8h16"/>
                </svg>
                Artikel
            </a>
            <a href="<?php echo e(route('blog.index')); ?>"
                class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-center text-green-800 transition-all bg-green-100 rounded-lg shadow sm:w-auto hover:text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 sm:text-lg"
                aria-label="Lihat halaman blog Azka Garden">
                <!-- Simbol Blog -->
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Blog
            </a>
            <a href="<?php echo e(route('contact')); ?>"
                class="flex items-center justify-center w-full gap-2 px-6 py-3 text-base font-bold text-center text-green-800 transition-all bg-green-100 rounded-lg shadow sm:w-auto hover:text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 sm:text-lg"
                aria-label="Hubungi Azka Garden via Kontak">
                <!-- Simbol Kontak -->
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-4 0a5 5 0 1 1-10 0 5 5 0 0 1 10 0z"/>
                </svg>
                Kontak
            </a>
        </div>

        <!-- Referensi & Kanal Resmi -->
        <article class="p-6 mb-8 border border-green-200 shadow bg-green-50 rounded-2xl">
            <h3 class="flex items-center gap-2 mb-3 text-xl font-bold text-green-700 md:text-2xl">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/>
                </svg>
                Kontak & Marketplace
            </h3>
            <ul class="space-y-4 text-base text-green-900 md:text-lg">
                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <strong>WhatsApp:</strong> <a href="https://wa.me/6289635086182" target="_blank" class="underline hover:text-green-600">0896-3508-6182</a>
                    <span class="ml-1 text-sm text-green-700">(Layanan 24/7)</span>
                </li>
                
                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1.25C6.072 1.25 1.25 6.072 1.25 12S6.072 22.75 12 22.75 22.75 17.928 22.75 12 17.928 1.25 12 1.25zm0 1.5c5.109 0 9.25 4.141 9.25 9.25s-4.141 9.25-9.25 9.25S2.75 17.109 2.75 12 6.891 2.75 12 2.75z"/>
                        <path d="M8.797 15.333c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.937.744-.937 1.658s.42 1.658.937 1.658.938-.744.938-1.658-.421-1.658-.938-1.658zm6.406 4.816c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.938.744-.938 1.658s.421 1.658.938 1.658.937-.744.937-1.658-.42-1.658-.937-1.658z"/>
                    </svg>
                    <strong>Tokopedia:</strong> <a href="https://www.tokopedia.com/hendrikfloris" target="_blank" class="underline hover:text-green-600">Toko Bunga Hendrik</a>
                </li>

                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 3.582 8 8 8s8-3.582 8-8c0-5.523-4.477-10-8-10z"/>
                    </svg>
                    <strong>Google Maps:</strong> <a href="https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA" target="_blank" class="underline hover:text-green-600">Toko Bunga Hendrik - Depok</a>
                </li>

                <li class="p-4 mt-2 rounded-lg bg-white/70">
                    <h4 class="mb-2 font-semibold text-green-800">Informasi Kontak:</h4>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span><strong>Jam Operasional:</strong> Buka 24 jam</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><strong>Alamat:</strong> Jl. Raya KSU, Depok</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span><strong>Email:</strong> hendrikfloris@gmail.com</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span><strong>Pengiriman:</strong> Seluruh Indonesia</span>
                        </div>
                    </div>
                </li>
            </ul>
        </article>

        <!-- Galeri -->
        <?php echo $__env->make('components.gallery', ['images' => $galleryImages], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/faq.blade.php ENDPATH**/ ?>