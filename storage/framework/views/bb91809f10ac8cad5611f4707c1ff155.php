<?php $__env->startSection('content'); ?>
<?php
    $promo_code = session('promo_code') ?? '';
?>
<div class="container px-2 mx-auto md:px-6 lg:px-8">

    
    <?php if(isset($banners) && count($banners)): ?>
        <div class="mb-10">
            <!-- ... banner code sama seperti sebelumnya ... -->
        </div>
    <?php endif; ?>

    
    <div class="mb-6">
        <?php if($promo_code): ?>
            <div class="flex items-center justify-center gap-2 px-4 py-2 mb-4 font-bold text-center text-green-800 bg-green-100 rounded-lg">
                <span>Promo sudah aktif:</span>
                <span class="px-2 py-1 font-mono text-green-900 bg-green-200 rounded"><?php echo e($promo_code); ?></span>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('promo.activate')); ?>" class="flex flex-col items-center gap-2 md:flex-row">
                <?php echo csrf_field(); ?>
                <input type="text" name="promo_code" value="<?php echo e(old('promo_code', $promo_code)); ?>" placeholder="Masukkan kode promo" required class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 md:w-auto" />
                <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 md:w-auto">Aktifkan</button>
                <?php if(session('success')): ?>
                    <span class="ml-2 text-green-700"><?php echo e(session('success')); ?></span>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <span class="ml-2 text-red-700"><?php echo e(session('error')); ?></span>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>

    
    <h1 class="mb-6 text-3xl font-bold">Produk Kami</h1>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Promo logic
                $final_price = $product->price;
                $promo_active = false;
                $promo_label = '';
                $promo_diskon = 0;
                if(isset($active_promo) && $active_promo && isset($active_promo->discount_type) && isset($active_promo->discount_value)) {
                    if($active_promo->discount_type === 'percent') {
                        $promo_diskon = round($product->price * ($active_promo->discount_value/100));
                        $promo_label = $active_promo->discount_value . '%';
                    } else {
                        $promo_diskon = $active_promo->discount_value;
                        $promo_label = 'Rp ' . number_format($promo_diskon, 0, ',', '.');
                    }
                    $final_price = max(0, $product->price - $promo_diskon);
                    $promo_active = true;
                }
            ?>
            <div class="flex flex-col overflow-hidden transition bg-white border rounded-lg shadow hover:shadow-lg">
                <img src="<?php echo e(asset('storage/' . ($product->image_path ?? 'placeholder.png'))); ?>" alt="<?php echo e($product->name); ?>"
                    class="object-cover w-full h-48">
                <div class="flex flex-col flex-1 p-4">
                    <h2 class="mb-1 text-xl font-semibold"><?php echo e($product->name); ?></h2>
                    <p class="mb-2 text-gray-600"><?php echo e($product->description); ?></p>
                    <div class="flex flex-col mb-2">
                        <?php if($promo_active && $final_price < $product->price): ?>
                            <span class="text-sm text-gray-500 line-through">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                            <span class="text-lg font-bold text-green-700">
                                Rp <?php echo e(number_format($final_price, 0, ',', '.')); ?>

                            </span>
                            <span class="px-2 py-1 mt-1 text-xs text-green-700 bg-green-100 rounded">
                                Diskon: <?php echo e($promo_code); ?> (<?php echo e($promo_label); ?>)
                            </span>
                        <?php else: ?>
                            <span class="mb-2 text-lg font-bold text-gray-700">
                                Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex flex-row gap-2 mt-4">
                        <a href="<?php echo e(route('user.products.show', $product)); ?>"
                            class="w-1/2 px-4 py-2 text-center text-white transition bg-green-500 rounded shadow hover:bg-green-600">
                            Lihat Detail
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                            <form method="POST" action="<?php echo e(route('user.cart.add')); ?>" class="w-1/2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                <input type="hidden" name="promo_code" value="<?php echo e($promo_code); ?>">
                                <button type="submit" class="w-full px-4 py-2 text-white transition bg-green-600 rounded shadow hover:bg-green-700">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        <?php else: ?>
                            <span
                                class="w-1/2 px-4 py-2 text-center text-gray-600 bg-gray-200 rounded cursor-not-allowed select-none"
                                title="Login untuk membeli">
                                Login untuk beli
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="col-span-3 text-center text-gray-500">Belum ada produk tersedia.</p>
        <?php endif; ?>
    </div>

    
    <div class="mt-10 mb-8">
        <!-- ... newsletter code sama seperti sebelumnya ... -->
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/user/products/index.blade.php ENDPATH**/ ?>