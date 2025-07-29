

<?php $__env->startSection('title', '403 Forbidden'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-24 mx-auto text-center">
    <h1 class="mb-4 text-6xl font-bold text-red-700">403</h1>
    <p class="mb-8 text-xl text-gray-700">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="<?php echo e(url('/')); ?>" class="inline-block px-6 py-3 text-white bg-red-600 rounded hover:bg-red-700">
        Kembali ke Beranda
    </a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/errors/403.blade.php ENDPATH**/ ?>