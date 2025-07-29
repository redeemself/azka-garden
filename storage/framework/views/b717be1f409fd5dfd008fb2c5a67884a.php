

<?php $__env->startSection('title', '500 Server Error'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-blue-700">500</h1>
    <p class="mb-8 text-xl text-gray-700">Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
    <a href="<?php echo e(url('/')); ?>" class="inline-block px-6 py-3 text-white bg-blue-600 rounded hover:bg-blue-700">
        Kembali ke Beranda
    </a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/errors/500.blade.php ENDPATH**/ ?>