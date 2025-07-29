<?php $__env->startSection('title', 'Daftar Membership / Langganan Promo - Azka Garden'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col min-h-screen bg-gradient-to-b from-green-50 via-green-100 to-green-50">
  <section class="flex flex-col flex-grow">
    <div class="container max-w-4xl px-4 py-12 mx-auto" style="padding-top:72px;">
      <h1 class="flex items-center gap-3 mb-4 text-3xl font-black text-green-800 md:text-4xl drop-shadow">
        <span class="inline-flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/>
          </svg>
        </span>
        Daftar Membership / Langganan Promo
      </h1>
      <p class="mb-6 text-lg font-medium text-green-800">
        Kode promo berikut aktif untuk akun Anda.<br>
        <span class="text-green-600">Gunakan untuk dapatkan keuntungan spesial!</span>
      </p>

      <?php if(auth()->guard()->guest()): ?>
        <div class="flex flex-col items-center justify-center w-full max-w-xl mx-auto mt-6 mb-12 text-base font-semibold text-center text-green-800 border-2 border-green-200 shadow-xl bg-white/80 rounded-2xl animate-fadeIn">
          <div class="flex items-center gap-2 py-6">
            <svg class="text-green-600 w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
            </svg>
            <span>
              Silakan <a href="<?php echo e(route('login')); ?>" class="font-bold text-green-700 underline">login</a> untuk melihat dan menggunakan kode promo Anda.
            </span>
          </div>
        </div>
      <?php endif; ?>

      <?php if(auth()->guard()->check()): ?>
      <div class="flex flex-col items-center w-full">
        <?php
          $user = auth()->user();
          $userEmail = $user->email ?? null;
          $userContacts = $contacts->where('email', $userEmail);
          $usedPromoCodes = \App\Models\Cart::where('user_id', $user->id)->whereNotNull('promo_code')->pluck('promo_code')->unique()->toArray();
          if (session('promo_code')) {
            $usedPromoCodes[] = session('promo_code');
            $usedPromoCodes = array_unique($usedPromoCodes);
          }
        ?>

        <?php $__empty_1 = true; $__currentLoopData = $userContacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $promoUsed = in_array($contact->promo_code, $usedPromoCodes); ?>
        <div class="w-full my-4 transition-all duration-300 border border-green-200 shadow-2xl bg-white/90 rounded-xl hover:shadow-green-200 hover:border-green-300 backdrop-blur-md" style="min-width:320px;">
          <div class="flex flex-col items-stretch justify-between gap-0 p-6 md:flex-row md:gap-2 md:p-7">
            <div class="flex flex-col flex-1 gap-1">
              <div class="text-lg font-bold leading-tight text-green-900"><?php echo e($contact->name); ?></div>
              <div class="text-sm text-green-800"><?php echo e($contact->email); ?></div>
            </div>
            <div class="flex flex-col gap-2 md:items-center md:justify-center min-w-[160px]">
              <div class="flex items-center gap-2">
                <span class="px-3 py-1 font-mono text-sm font-semibold tracking-wide text-green-700 border border-green-300 rounded-full shadow-sm select-all bg-green-50 promo-code"
                  data-code="<?php echo e($contact->promo_code); ?>"
                  tabindex="0"
                  title="Klik untuk menyalin kode promo">
                  <?php echo e($contact->promo_code); ?>

                </span>
                <span class="ml-2 text-xs font-medium text-green-700 copy-feedback" style="display:none;">Disalin!</span>
              </div>
              <div>
                <?php if($promoUsed): ?>
                  <span class="inline-flex items-center gap-2 px-4 py-1 text-sm font-bold text-white rounded-full shadow-lg select-none bg-gradient-to-r from-green-500 to-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Sudah Dipakai
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-2 px-4 py-1 text-sm font-bold text-green-700 border border-green-400 rounded-full shadow select-none bg-green-50">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    Belum Dipakai
                  </span>
                <?php endif; ?>
              </div>
            </div>
            <div class="flex flex-col gap-1 min-w-[120px] items-end justify-center">
              <span class="block text-xs text-gray-700 md:text-right">
                <?php echo e($contact->created_at ? $contact->created_at->format('d M Y · H:i') : '-'); ?>

              </span>
            </div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="w-full py-10 text-base font-medium text-center text-green-700 border border-green-100 bg-white/80 rounded-xl">
          <svg class="w-10 h-10 mx-auto mb-2 text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path d="M9 9h6v6H9z"/>
          </svg>
          Anda belum memiliki kode promo membership.
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Salin promo dengan klik pada kode
  document.querySelectorAll('.promo-code').forEach(function(span) {
    span.addEventListener('click', function() {
      var code = span.dataset.code || span.textContent.trim();
      var feedback = span.parentNode.querySelector('.copy-feedback');
      function showFeedback() {
        if (feedback) {
          feedback.style.display = 'inline';
          setTimeout(function() { feedback.style.display = 'none'; }, 1500);
        }
      }
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(showFeedback, function() {
          alert('Gagal menyalin kode promo.');
        });
      } else {
        // Fallback untuk non-https atau browser lama
        var textarea = document.createElement('textarea');
        textarea.value = code;
        textarea.style.position = 'fixed'; // Avoid scrolling to bottom
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
          document.execCommand('copy');
          showFeedback();
        } catch (err) {
          alert('Gagal menyalin kode promo.');
        }
        document.body.removeChild(textarea);
      }
    });
    // Optional: tambahkan salin dengan keyboard Enter/Space untuk aksesibilitas
    span.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        span.click();
      }
    });
  });
});
</script>
<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(16px);} to { opacity: 1; transform: translateY(0);} }
.animate-fadeIn { animation: fadeIn 0.5s ease; }
.promo-code { cursor: pointer; transition: background .2s, box-shadow .2s;}
.promo-code:active, .promo-code:focus { background: #bbf7d0; outline: 2px solid #22c55e; box-shadow: 0 0 0 2px #a7f3d0; }
.copy-feedback { transition: opacity .2s; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/membership/index.blade.php ENDPATH**/ ?>