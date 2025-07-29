<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    $isGuest = !auth('web')->check();
    $user = auth('web')->user();
    $hasAddress = $user && $user->addresses && $user->addresses->count();
?>

<section
    x-data="{
        heroImages: [
            '<?php echo e(asset('images/hero-1.jpg')); ?>',
            '<?php echo e(asset('images/hero-2.jpg')); ?>',
            '<?php echo e(asset('images/hero-3.jpg')); ?>',
            '<?php echo e(asset('images/hero-4.jpg')); ?>',
            '<?php echo e(asset('images/hero-5.jpg')); ?>',
            '<?php echo e(asset('images/hero-6.jpg')); ?>',
            '<?php echo e(asset('images/hero-7.jpg')); ?>',
        ],
        currentBg: 0,
        prevBg: null,
        transitionDuration: 1000,
        transitioning: false,
        frontLayer: true,
        interval: null,
        steps: [
        {
            title: 'Alur Pengelolaan Akun',
            desc: 'Pengguna membuka halaman login sebagai langkah awal untuk mengakses berbagai fitur pada website. Selanjutnya, pengguna mengisi username dan password pada form yang disediakan, memastikan data yang dimasukkan benar. Sistem melakukan pengecekan terhadap data yang diinput, memastikan kredensial yang diberikan valid. Jika data yang dimasukkan valid, pengguna akan diarahkan ke halaman profil untuk melihat dan mengelola akun. Jika terjadi kesalahan, seperti password salah, sistem akan menampilkan pesan error agar pengguna dapat memperbaikinya. Pengguna dapat melakukan pengelolaan data akun, seperti mengubah profil atau password melalui dashboard yang telah tersedia.'
        },
        {
            title: 'Alur Checkout',
            desc: 'Pengguna mengunjungi website Godhong Cilik untuk memulai proses pembelian produk. Pada halaman beranda, berbagai produk dan layanan yang tersedia akan ditampilkan kepada pengguna. Pengguna memilih kategori produk sesuai dengan kebutuhan, sehingga produk yang relevan dapat ditampilkan. Produk-produk pada kategori yang dipilih akan muncul, lengkap dengan detail harga dan deskripsi. Pengguna dapat mengeklik produk untuk melihat detail lebih lanjut sebelum memutuskan untuk membeli. Setelah memasukkan jumlah produk yang ingin dibeli, pengguna menambahkannya ke keranjang belanja. Setelah selesai memilih produk, pengguna melanjutkan proses pembayaran dengan mengakses halaman checkout. Jika pengguna belum login, sistem akan meminta login untuk melanjutkan proses checkout dan pembayaran. Setelah login, pengguna mengikuti instruksi pembayaran yang diberikan oleh sistem. Jika pembayaran berhasil, sistem akan menampilkan halaman konfirmasi sebagai bukti transaksi.'
        },
        {
            title: 'Alur Pelacakan Pesanan',
            desc: 'Pengguna dapat memilih menu lacak pesanan untuk mengetahui status pesanan yang sudah dilakukan. Sistem akan menampilkan daftar pesanan yang pernah dilakukan oleh pengguna. Pengguna memilih salah satu pesanan untuk melihat detail status pengiriman dan lain-lain. Sistem menampilkan status terkini dari pesanan, seperti sedang diproses, dikirim, atau sudah diterima. Setelah pesanan diterima, pengguna dapat memberikan ulasan mengenai produk dan layanan yang telah didapatkan.'
        }
        ],
        current: 0,
        stepTransitioning: false,
        stepTimeout: null,
        showRegister: false,
        init() {
            this.startInterval();
            this.preloadImages();
        },
        preloadImages() {
            // Preload images for smoother transitions
            this.heroImages.forEach((src) => {
                const img = new Image();
                img.src = src;
            });
        },
        startInterval() {
            this.stopInterval();
            this.interval = setInterval(() => { this.nextBackground() }, 7000);
        },
        stopInterval() {
            if (this.interval) clearInterval(this.interval);
        },
        nextBackground() {
            let nextIdx = (this.currentBg + 1) % this.heroImages.length;
            this.performTransition(nextIdx, 'next');
        },
        prevBackground() {
            let prevIdx = (this.currentBg - 1 + this.heroImages.length) % this.heroImages.length;
            this.performTransition(prevIdx, 'prev');
        },
        performTransition(nextIndex, direction = 'next') {
            if (this.transitioning || nextIndex === this.currentBg) return;
            this.transitioning = true;
            this.prevBg = this.currentBg;
            this.currentBg = nextIndex;
            this.frontLayer = !this.frontLayer;
            setTimeout(() => {
                this.transitioning = false;
            }, this.transitionDuration);
        },
        setBg(idx) {
            this.stopInterval();
            this.performTransition(idx);
            this.startInterval();
        },
        prevStep() {
            if (this.current > 0) {
                this.smoothStepChange(this.current - 1);
            }
        },
        nextStep() {
            if (this.current < this.steps.length - 1) {
                this.smoothStepChange(this.current + 1);
            } else {
                this.showRegister = true;
            }
        },
        smoothStepChange(target) {
            this.stepTransitioning = true;
            clearTimeout(this.stepTimeout);
            this.stepTimeout = setTimeout(() => {
                this.current = target;
                this.stepTransitioning = false;
            }, 350);
        }
    }"
    x-init="init"
    class="relative w-full min-h-screen overflow-hidden"
>
    <div class="absolute inset-0 z-0 pointer-events-none">
        <template x-if="transitioning">
            <div
                class="absolute inset-0 bg-center bg-cover will-change-transform"
                :style="`background-image: url('${heroImages[prevBg ?? 0]}'); opacity: 1; scale: ${frontLayer ? 1.05 : 1}; filter: blur(0.5px); transition: opacity 1s, transform 1s;`"
                x-bind:style="frontLayer
                    ? `background-image: url('${heroImages[prevBg ?? 0]}'); opacity: 0; scale: 1.1; filter: blur(1px); transition: opacity 1s, transform 1s;`
                    : `background-image: url('${heroImages[prevBg ?? 0]}'); opacity: 0; scale: 1.05; filter: blur(1px); transition: opacity 1s, transform 1s;`"
            ></div>
        </template>
        <div
            class="absolute inset-0 bg-center bg-cover will-change-transform"
            :style="`background-image: url('${heroImages[currentBg]}'); opacity: 1; scale: ${frontLayer ? 1 : 1.05}; filter: blur(0px); transition: opacity 1s, transform 1s;`"
        ></div>
        <div class="absolute inset-0 z-10 bg-gradient-to-br from-green-900/70 via-black/60 to-green-800/40"></div>
    </div>

    
    <?php if(auth()->guard('web')->guest()): ?>
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
        style="backdrop-filter: blur(3px);"
    >
        <div class="relative flex flex-col items-center w-full max-w-xs p-6 bg-white border border-blue-200 shadow-2xl rounded-xl">
            <button
                @click="show = false"
                class="absolute px-2 text-xl font-bold text-gray-400 top-2 right-2 hover:text-gray-600"
                aria-label="Tutup"
            >&times;</button>
            <div class="flex flex-col items-center">
                <div class="flex items-center justify-center w-16 h-16 mb-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 7a4 4 0 110-8 4 4 0 010 8z" /></svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-blue-700">Daftar Akun Baru</h3>
                <div class="mb-5 text-sm text-center text-gray-700">Anda belum login. Daftarkan akun baru untuk pengalaman belanja lebih mudah dan cepat di Azka Garden.</div>
                <a href="<?php echo e(route('register')); ?>"
                   class="w-full px-4 py-2 mb-1 font-bold text-center text-white transition bg-blue-500 rounded-lg shadow hover:bg-blue-600">
                    Registrasi Sekarang
                </a>
                <a href="<?php echo e(route('login')); ?>"
                   class="w-full px-4 py-2 mt-1 font-semibold text-center text-blue-700 transition bg-white border border-blue-300 rounded-lg shadow hover:bg-blue-50">
                    Sudah punya akun? Login
                </a>
                <button @click="show = false" class="w-full px-4 py-2 mt-2 font-semibold text-center text-gray-800 transition bg-gray-100 border border-gray-200 rounded-lg shadow hover:bg-gray-200">Tutup</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(auth()->guard('web')->check()): ?>
        <?php if(!$hasAddress): ?>
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
            style="backdrop-filter: blur(3px);"
        >
            <div class="relative flex flex-col items-center w-full max-w-xs p-6 bg-white border border-yellow-200 shadow-2xl rounded-xl">
                <button
                    @click="show = false"
                    class="absolute px-2 text-xl font-bold text-gray-400 top-2 right-2 hover:text-gray-600"
                    aria-label="Tutup"
                >&times;</button>
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-16 h-16 mb-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-yellow-700">Konfirmasi</h3>
                    <div class="mb-5 text-sm text-center text-gray-700">
                        Mohon lengkapi alamat rumah Anda terlebih dahulu!
                    </div>
                    <a href="<?php echo e(route('user.profile.index')); ?>"
                       class="w-full px-4 py-2 mb-1 font-bold text-center text-white transition bg-yellow-400 rounded-lg shadow hover:bg-yellow-500">
                        Isi Alamat Sekarang
                    </a>
                    <div class="mt-2 text-xs text-gray-600">Setelah alamat terisi, silakan pilih produk yang Anda inginkan dengan baik dan benar.</div>
                    <button @click="show = false" class="w-full px-4 py-2 mt-3 font-semibold text-center text-gray-800 transition bg-gray-100 border border-gray-200 rounded-lg shadow hover:bg-gray-200">Tutup</button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="relative z-30">
        <div class="container px-3 py-8 mx-auto md:px-8 lg:px-16">
            <div class="flex flex-col items-center px-4 mb-12 text-center">
                <h1 class="mb-2 text-5xl font-extrabold tracking-tight text-white md:text-6xl drop-shadow-lg">
                    Selamat Datang,
                    <span class="text-green-300">
                        <?php if(auth()->guard('web')->check()): ?>
                            <?php echo e(auth('web')->user()->name); ?>

                        <?php else: ?>
                            Guest
                        <?php endif; ?>
                    </span>!
                </h1>
                <p class="mb-6 text-xl md:text-2xl text-white/80 drop-shadow">
                    Temukan tanaman & layanan lanskap terbaik hanya di <span class="font-bold text-green-200">Azka Garden</span>.
                </p>
                
                <!-- Tokopedia & WhatsApp Buttons (New) -->
                <div class="flex flex-col gap-4 mb-4 sm:flex-row">
                    <!-- WhatsApp Button -->
                    <a href="https://wa.me/6289635086182" target="_blank" rel="noopener" 
                       class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-green-500 to-green-600 rounded-xl hover:from-green-600 hover:to-green-700 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span class="absolute flex w-4 h-4 -top-2 -right-2">
                                    <span class="absolute inline-flex w-full h-full bg-white rounded-full opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex w-4 h-4 bg-white rounded-full"></span>
                                </span>
                            </div>
                            <span class="font-semibold">WhatsApp: 0896-3508-6182</span>
                        </div>
                    </a>
                    
                    <!-- Tokopedia Button -->
                    <a href="https://www.tokopedia.com/hendrikfloris" target="_blank" rel="noopener"
                       class="flex items-center justify-center px-6 py-3 text-green-700 transition-all duration-300 transform bg-white shadow-lg rounded-xl hover:bg-green-50 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="#42B549">
                                <path d="M12 1.25C6.072 1.25 1.25 6.072 1.25 12S6.072 22.75 12 22.75 22.75 17.928 22.75 12 17.928 1.25 12 1.25zm0 1.5c5.109 0 9.25 4.141 9.25 9.25s-4.141 9.25-9.25 9.25S2.75 17.109 2.75 12 6.891 2.75 12 2.75z"/>
                                <path d="M8.797 15.333c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.937.744-.937 1.658s.42 1.658.937 1.658.938-.744.938-1.658-.421-1.658-.938-1.658zm6.406 4.816c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.938.744-.938 1.658s.421 1.658.938 1.658.937-.744.937-1.658-.42-1.658-.937-1.658z"/>
                            </svg>
                            <span class="font-semibold">Toko Bunga Hendrik</span>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="flex justify-center w-full mb-8">
                <div class="grid w-full grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3" style="max-width:1400px;">
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <template x-for="(step, idx) in steps" :key="idx">
                            <div
                                x-show="current === idx && !stepTransitioning"
                                class="flex flex-col items-center px-12 py-10 border shadow-2xl bg-white/30 backdrop-blur-xl rounded-2xl border-white/10"
                                style="min-width:350px; max-width:100%; margin:0 auto; transition: opacity .35s, transform .35s; will-change:transform,opacity;"
                                x-transition:enter="transition ease-out duration-350"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                            >
                                <h3 class="mb-1 text-lg font-semibold md:text-xl lg:text-2xl text-primary drop-shadow"
                                    x-text="(current+1) + '. ' + step.title"></h3>
                                <p class="mb-1 text-sm text-center md:text-base text-white/90"
                                x-text="step.desc"></p>
                                <div class="flex items-center justify-center gap-2 mt-1 mb-1">
                                    <template x-for="(s, i) in steps" :key="'dot-'+i">
                                        <span :class="{
                                            'w-4 h-2 rounded-full bg-green-500 shadow transition-all duration-300': i === current,
                                            'w-2 h-2 rounded-full bg-white/40': i !== current
                                        }"></span>
                                    </template>
                                </div>
                                <div class="flex justify-center gap-2 mt-1">
                                    <button
                                        class="px-3 py-1 text-sm font-medium text-gray-800 transition rounded-lg shadow bg-white/40 hover:bg-white/70 disabled:opacity-40 disabled:pointer-events-none"
                                        x-on:click="prevStep"
                                        x-bind:disabled="current === 0"
                                    >
                                        Sebelumnya
                                    </button>
                                    <button
                                        class="px-5 py-1 text-sm font-semibold text-white transition-all transform rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:scale-105"
                                        x-on:click="nextStep"
                                        x-text=" current < steps.length - 1 ? 'Paham' : 'Mulai Sekarang' "
                                    ></button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            
            <div
                x-show="showRegister"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70"
                style="display: none;"
            >
                <div class="w-full max-w-lg p-8 bg-white shadow-xl rounded-xl">
                    <h2 class="mb-4 text-2xl font-bold text-green-700">🎉 Selamat Datang di Azka Garden!</h2>
                    <p class="mb-4 text-lg text-center text-gray-700">
                        Silahkan pilih produk sesuka hatimu <span class="text-2xl">😊</span><br>
                        <span class="font-semibold text-green-700">Kami siap melayani dengan hangat!</span>
                    </p>
                    <a href="<?php echo e(route('products.index')); ?>" class="block px-4 py-2 font-semibold text-center text-green-700 transition rounded hover:underline hover:bg-green-100 bg-green-50">
                        Menuju Halaman Produk
                    </a>
                    <button @click="showRegister = false" class="px-3 py-1 mt-4 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">Tutup</button>
                </div>
            </div>

            
            <h2 class="mb-6 text-3xl font-bold text-white/95 drop-shadow-lg">Produk Terbaru</h2>
            <div class="grid grid-cols-1 gap-8 mb-12 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__empty_1 = true; $__currentLoopData = $newestProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex flex-col overflow-hidden transition bg-white/90 border border-green-200 rounded-2xl shadow-lg hover:shadow-2xl h-full backdrop-blur-lg hover:-translate-y-1 hover:scale-[1.02] duration-300">
                        <img src="<?php echo e(asset($product->image_url)); ?>"
                            alt="<?php echo e($product->name); ?>"
                            class="object-cover w-full h-40 border-b border-green-100 rounded-t-2xl"
                            loading="lazy">
                        <div class="flex flex-col flex-1 p-6">
                            <h2 class="mb-2 text-2xl font-semibold text-green-800"><?php echo e($product->name); ?></h2>
                            <p class="mb-3 text-gray-700"><?php echo e($product->description); ?></p>
                            <span class="mb-3 text-lg font-bold text-green-700">
                                Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                            </span>
                            <div class="flex flex-row gap-2 mt-auto">
                                <a href="<?php echo e(route('user.products.show', $product->id)); ?>"
                                    class="w-full px-5 py-2.5 text-center text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700 font-semibold hover:scale-105 duration-200">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-span-3 text-center text-white/90">Belum ada produk terbaru.</p>
                <?php endif; ?>
            </div>
            <div class="mb-12 text-right">
                <a href="<?php echo e(route('products.index')); ?>" class="px-5 py-2.5 text-white bg-green-700 rounded-xl hover:bg-green-800 shadow font-semibold hover:scale-105 duration-200">Lihat Semua Produk</a>
            </div>

            
            <h2 class="mb-6 text-3xl font-bold text-white/95 drop-shadow-lg">Rekomendasi Untukmu</h2>
            <div class="grid grid-cols-1 gap-8 mb-12 sm:grid-cols-2 lg:grid-cols-3">
                <?php
                    $rekomendasiPerluDibeli = \App\Models\Product::where('stock', '>', 0)
                        ->inRandomOrder()
                        ->limit(3)
                        ->get();
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $rekomendasiPerluDibeli; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex flex-col overflow-hidden transition bg-white/90 border border-green-200 rounded-2xl shadow-lg hover:shadow-2xl h-full backdrop-blur-lg hover:-translate-y-1 hover:scale-[1.02] duration-300">
                        <img src="<?php echo e(asset($product->image_url)); ?>"
                            alt="<?php echo e($product->name); ?>"
                            class="object-cover w-full h-40 border-b border-green-100 rounded-t-2xl"
                            loading="lazy">
                        <div class="flex flex-col flex-1 p-6">
                            <h2 class="mb-2 text-2xl font-semibold text-green-800"><?php echo e($product->name); ?></h2>
                            <p class="mb-3 text-gray-700"><?php echo e($product->description); ?></p>
                            <span class="mb-3 text-lg font-bold text-green-700">
                                Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                            </span>
                            <div class="flex flex-row gap-2 mt-auto">
                                <a href="<?php echo e(route('user.products.show', $product->id)); ?>"
                                    class="w-full px-5 py-2.5 text-center text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700 font-semibold hover:scale-105 duration-200">
                                    Lihat Detail
                                </a>
                                <?php if($isGuest): ?>
                                    <a href="<?php echo e(route('login')); ?>"
                                        class="w-full px-5 py-2.5 text-center text-green-700 bg-green-100 border border-green-200 rounded-lg shadow hover:bg-green-200 font-semibold hover:scale-105 duration-200"
                                        title="Silakan login untuk membeli produk!">
                                        Masuk
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('user.cart.add', $product->id)); ?>"
                                        class="w-full px-5 py-2.5 text-center text-white bg-green-800 rounded-lg shadow hover:bg-green-900 font-semibold hover:scale-105 duration-200">
                                        Beli Sekarang
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-span-3 text-center text-white/90">Belum ada rekomendasi produk yang perlu dibeli.</p>
                <?php endif; ?>
            </div>
            <div class="mb-12 text-right">
                <a href="<?php echo e(route('products.index')); ?>" class="px-5 py-2.5 text-white bg-green-700 rounded-xl hover:bg-green-800 shadow font-semibold hover:scale-105 duration-200">Lihat Semua Produk</a>
            </div>

            
            <h2 class="mb-6 text-3xl font-bold text-white/95 drop-shadow-lg">Produk Paling Disukai</h2>
            <div class="grid grid-cols-1 gap-8 mb-12 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__empty_1 = true; $__currentLoopData = $mostLikedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex flex-col overflow-hidden transition bg-white/90 border border-green-200 rounded-2xl shadow-lg hover:shadow-2xl h-full backdrop-blur-lg hover:-translate-y-1 hover:scale-[1.02] duration-300">
                        <img src="<?php echo e(asset($product->image_url)); ?>"
                            alt="<?php echo e($product->name); ?>"
                            class="object-cover w-full h-40 border-b border-green-100 rounded-t-2xl"
                            loading="lazy">
                        <div class="flex flex-col flex-1 p-6">
                            <h2 class="mb-2 text-2xl font-semibold text-green-800"><?php echo e($product->name); ?></h2>
                            <p class="mb-3 text-gray-700"><?php echo e($product->description); ?></p>
                            <span class="mb-3 text-lg font-bold text-green-700">
                                Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                            </span>
                            <div class="flex flex-row gap-2 mt-auto">
                                <a href="<?php echo e(route('user.products.show', $product->id)); ?>"
                                    class="w-full px-5 py-2.5 text-center text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700 font-semibold hover:scale-105 duration-200">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-span-3 text-center text-white/90">Belum ada produk favorit.</p>
                <?php endif; ?>
            </div>
            
            
            <div class="p-6 mb-12 shadow-lg rounded-2xl bg-gradient-to-br from-white/90 to-white/80 backdrop-blur-md">
                <div class="flex flex-col items-center gap-8 lg:flex-row">
                    <div class="w-full lg:w-1/2">
                        <h2 class="mb-4 text-2xl font-bold text-green-800 md:text-3xl">Kunjungi Kami di Marketplace</h2>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            Dapatkan kemudahan berbelanja tanaman dari Azka Garden melalui platform marketplace terpercaya. Kami menawarkan koleksi tanaman berkualitas, perlengkapan berkebun, dan layanan pengiriman ke seluruh Indonesia.
                        </p>
                        
                        <div class="flex flex-col gap-4 mb-6 sm:flex-row">
                            <!-- Tokopedia -->
                            <a href="https://www.tokopedia.com/hendrikfloris" target="_blank" rel="noopener" 
                                class="flex items-center justify-between flex-1 px-6 py-4 transition-all duration-300 bg-white border border-green-100 shadow rounded-xl hover:shadow-md group">
                                <div class="flex items-center gap-4">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="#42B549">
                                        <path d="M12 1.25C6.072 1.25 1.25 6.072 1.25 12S6.072 22.75 12 22.75 22.75 17.928 22.75 12 17.928 1.25 12 1.25zm0 1.5c5.109 0 9.25 4.141 9.25 9.25s-4.141 9.25-9.25 9.25S2.75 17.109 2.75 12 6.891 2.75 12 2.75z"/>
                                        <path d="M8.797 15.333c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.937.744-.937 1.658s.42 1.658.937 1.658.938-.744.938-1.658-.421-1.658-.938-1.658zm6.406 4.816c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.938.744-.938 1.658s.421 1.658.938 1.658.937-.744.937-1.658-.42-1.658-.937-1.658z"/>
                                    </svg>
                                    <div>
                                        <h3 class="font-bold text-gray-800">Tokopedia</h3>
                                        <p class="text-sm text-gray-600">Toko Bunga Hendrik</p>
                                    </div>
                                </div>
                                <div class="transition-all duration-300 transform translate-x-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://wa.me/6289635086182" target="_blank" rel="noopener" 
                                class="flex items-center justify-between flex-1 px-6 py-4 transition-all duration-300 bg-white border border-green-100 shadow rounded-xl hover:shadow-md group">
                                <div class="flex items-center gap-4">
                                    <svg class="w-8 h-8" fill="#25D366" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <div>
                                        <h3 class="font-bold text-gray-800">WhatsApp</h3>
                                        <p class="text-sm text-gray-600">0896-3508-6182</p>
                                    </div>
                                </div>
                                <div class="transition-all duration-300 transform translate-x-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                        
                        <div class="p-4 border border-green-100 rounded-lg bg-green-50">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 mt-0.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-green-800">Info Layanan</h4>
                                    <p class="text-sm text-gray-700">Buka 24/7 untuk layanan online. Pengiriman ke seluruh Indonesia. Konsultasi gratis untuk pemilihan tanaman terbaik sesuai kebutuhan Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center w-full lg:w-1/2">
                        <div class="relative p-1 overflow-hidden bg-white shadow-xl rounded-2xl">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-green-700/30 rounded-2xl"></div>
                            <img 
                                src="<?php echo e(asset('images/tokopedia-showcase.jpg')); ?>" 
                                alt="Toko Bunga Hendrik di Tokopedia" 
                                class="relative z-10 w-full h-auto max-w-md rounded-xl"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='<?php echo e(asset('images/placeholder-store.jpg')); ?>';"
                            >
                            <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo e(asset('images/tokopedia-icon.png')); ?>" alt="Tokopedia" class="w-8 h-8">
                                    <div class="text-white">
                                        <div class="text-lg font-bold">Toko Bunga Hendrik</div>
                                        <div class="text-sm opacity-90">Official Store</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-right">
                <a href="<?php echo e(route('products.index')); ?>" class="px-5 py-2.5 text-white bg-green-700 rounded-xl hover:bg-green-800 shadow font-semibold hover:scale-105 duration-200">Lihat Semua Produk</a>
            </div>
        </div>
    </div>

    <div class="absolute left-0 right-0 z-40 flex flex-col items-center select-none" style="bottom: 10px;">
        <div class="flex items-center justify-center gap-3 px-4 py-2 rounded-full shadow-lg bg-black/30">
            <button
                class="flex items-center justify-center w-10 h-10 text-lg text-white transition rounded-full shadow bg-black/50 hover:bg-black/70"
                @mouseenter="stopInterval" @mouseleave="startInterval"
                @click="prevBackground"
                aria-label="Background Sebelumnya"
                style="user-select:none;"
            >
                &#8592;
            </button>
            <div class="flex justify-center gap-3">
                <template x-for="(img, idx) in heroImages" :key="'herodot'+idx">
                    <span
                        :class="{
                            'w-5 h-3 rounded-full bg-green-500 shadow-lg cursor-pointer ring-2 ring-green-400 ring-offset-2': idx === currentBg,
                            'w-3 h-3 rounded-full bg-white/60 cursor-pointer': idx !== currentBg
                        }"
                        @click="setBg(idx)"
                        style="user-select:none;transition:all 0.4s cubic-bezier(.4,0,.2,1);"
                    ></span>
                </template>
            </div>
            <button
                class="flex items-center justify-center w-10 h-10 text-lg text-white transition rounded-full shadow bg-black/50 hover:bg-black/70"
                @mouseenter="stopInterval" @mouseleave="startInterval"
                @click="nextBackground"
                aria-label="Background Selanjutnya"
                style="user-select:none;"
            >
                &#8594;
            </button>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/home.blade.php ENDPATH**/ ?>