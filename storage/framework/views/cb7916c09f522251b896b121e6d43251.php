<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    $user = auth()->user();
    $createdAt = isset($user) && isset($user->getAttributes()['created_at']) ? $user->getAttributes()['created_at'] : null;
    $filteredRoles = isset($allRoles) ? $allRoles->filter(function($role) {
        return !in_array(strtolower($role->name), ['admin', 'developer']);
    }) : collect();
    $addresses = isset($user) && method_exists($user, 'addresses') ? $user->addresses()->get() : collect();
    $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
?>

<?php if(!$hasAddress): ?>
    <div class="alert alert-warning">
        Anda belum mengisi alamat rumah! Silakan lengkapi alamat Anda agar bisa melakukan pembelian.
    </div>
<?php endif; ?>

<style>
body {
  background: linear-gradient(135deg, #eaf8f0 0%, #f0fdf4 100%);
  min-height: 100vh;
  font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
}
.profile-main-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 32px 20px 60px 20px;
}
.profile-card {
  box-shadow: 0 4px 24px 0 rgba(72, 219, 151, 0.10), 0 1.5px 8px 0 rgba(72,219,151,0.08);
  border-radius: 22px;
  border: 2px solid #a7f3d0;
  background: linear-gradient(120deg, #fff 80%, #d1fae5 100%);
  transition: box-shadow 0.27s cubic-bezier(.4,0,.2,1), border 0.2s;
  padding: 2.7rem 2.1rem;
  width: 100%;
  position: relative;
  overflow: hidden;
}
.profile-card:hover {
  box-shadow: 0 10px 32px 0 rgba(72,219,151,0.16), 0 2px 14px 0 rgba(72,219,151,0.09);
  border-color: #34d399;
}
.profile-row {
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
  gap: 44px;
  width: 100%;
}
.profile-avatar {
  width: 118px;
  height: 118px;
  box-shadow: 0 2px 14px 0 rgba(72,219,151,0.18);
  object-fit: cover;
  border-radius: 50%;
  border: 3.5px solid #34d399;
  background: #f3f4f6;
  margin-right: 0;
  flex-shrink: 0;
  transition: border .18s;
}
.profile-info-group {
  display: flex;
  flex-direction: column;
  gap: 11px;
  flex: 1;
  min-width: 0;
}
.profile-section-title {
  font-size: 1.22rem;
  font-weight: 700;
  color: #047857;
  margin-top: 2.2rem;
  margin-bottom: 0.7rem;
  letter-spacing: 0.01em;
}
.profile-link-list {
  list-style: none;
  padding: 0;
}
.profile-link-list li {
  margin-bottom: 0.45rem;
}
.profile-link-list a {
  color: #14b8a6;
  font-weight: 500;
  text-decoration: underline;
  transition: color .17s;
}
.profile-link-list a:hover {
  color: #059669;
  text-decoration-thickness: 2px;
}
.info-paragraph {
  text-indent: 1.5cm;
  margin-bottom: 1em;
  margin-top: 0.5em;
  line-height: 1.7;
  color: #065f46;
  font-size: 1.05rem;
}
.profile-link-list-clean {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding-left: 1.5cm;
}
.profile-link-list-clean .label-social {
  display: inline-block;
  min-width: 110px;
  font-weight: 600;
  color: #047857;
}
.profile-link-list-clean a {
  color: #14b8a6;
  font-weight: 500;
  text-decoration: underline;
  transition: color .17s;
}
.profile-link-list-clean a:hover {
  color: #059669;
  text-decoration-thickness: 2px;
}
.profile-desc-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin: 7px 0 7px 0;
  padding: 0 0 0 1.5cm;
}
.profile-desc-list > div {
  position: relative;
  padding-left: 0;
  color: #065f46;
  font-size: 1.01rem;
  margin: 0 0 2px 0;
}
.profile-badge {
  display: inline-block;
  background: linear-gradient(90deg,#bbf7d0 60%,#a7f3d0 100%);
  color: #047857;
  border-radius: 7px;
  font-size: 0.98rem;
  padding: 4px 15px;
  font-weight: 700;
  margin-right: 8px;
  box-shadow: 0 1px 6px 0 rgba(72, 219, 151, 0.08);
}
.profile-password-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 7px 0;
  flex-wrap: wrap;
}
.profile-password-row label {
  min-width: 92px;
  font-weight: 600;
  color: #065f46;
}
.profile-password-row input[type=password], .profile-password-row input[type=text] {
  font-size: 1rem;
  letter-spacing: 1.2px;
  background: transparent;
  border: none;
  width: auto;
  min-width: 90px;
  color: #065f46;
  font-weight: 500;
  padding: 2px 2px;
}
.profile-password-row button {
  background: transparent;
  border: none;
  padding: 0 3px;
  cursor: pointer;
  vertical-align: middle;
  display: flex;
  align-items: center;
  line-height: 1;
  margin-right: 2px;
}
.profile-password-row .helper {
  font-size: 0.87em;
  color: #9ca3af;
  margin-left: 0;
  min-width: 120px;
  flex: 1 1 100%;
  margin-top: 2px;
}
.edit-profile-card, .alamat-form-card {
  box-shadow: 0 3px 18px 0 rgba(72, 219, 151, 0.10);
  border-radius: 18px;
  border: 2px solid #a7f3d0;
  background: #fff;
  padding: 2.1rem 2.1rem;
  margin-top: 2.2rem;
  width: 100%;
}
.alamat-form-card { margin-bottom: 0.9rem; }
.profile-form-label {
  font-weight: 700;
  color: #065f46;
  letter-spacing: 0.01em;
}
.profile-form-input {
  transition: border 0.2s, box-shadow 0.2s;
}
.profile-form-input:focus {
  border-color: #34d399 !important;
  box-shadow: 0 0 0 2px #bbf7d0;
  outline: none;
}
.profile-form-btn {
  border-radius: 9px;
  font-weight: bold;
  transition: background 0.2s, color 0.2s, box-shadow .17s;
  box-shadow: 0 2px 8px rgba(16,185,129,.07);
}
.profile-form-btn.save {
  background: linear-gradient(90deg,#16a34a 60%,#059669 100%);
  color: #fff;
}
.profile-form-btn.save:hover {
  background: linear-gradient(90deg,#15803d 60%,#047857 100%);
  box-shadow: 0 4px 12px 0 rgba(16,185,129,.14);
}
.profile-form-btn.cancel {
  background: #f3f4f6;
  color: #065f46;
}
.profile-form-btn.cancel:hover {
  background: #e5e7eb;
}
.input-eye-btn {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  padding: 0;
  cursor: pointer;
  color: #6b7280;
  display: flex;
  align-items: center;
  z-index: 10;
}
::-webkit-input-placeholder { color: #a7a7a7; }
::-moz-placeholder { color: #a7a7a7; }
:-ms-input-placeholder { color: #a7a7a7; }
::placeholder { color: #a7a7a7; }
@media (max-width: 1200px) {
  .profile-main-container { max-width: 98vw; }
  .profile-card, .edit-profile-card, .alamat-form-card { padding: 1.2rem 0.9rem !important; }
  .profile-row { gap: 20px; }
  .profile-avatar { width: 90px; height: 90px; }
}
@media (max-width: 800px) {
  .profile-main-container { padding-left: 7px; padding-right: 7px; }
  .profile-row { flex-direction: column; gap: 12px; align-items: center; }
  .profile-avatar { width: 70px; height: 70px; }
  .edit-profile-card, .alamat-form-card { margin-top: 0.7rem !important; margin-bottom: 0.7rem !important; }
}
@media (max-width: 480px) {
  .profile-info-group .text-2xl { font-size: 1.15rem; }
  .profile-info-group .text-sm { font-size: 0.93rem; }
  .profile-card, .edit-profile-card, .alamat-form-card { padding: 0.77rem 0.23rem !important; }
  .helper { font-size: 0.76em; }
}
.toast-modern {
  position: fixed;
  left: 50%;
  top: 3.5rem;
  transform: translateX(-50%);
  z-index: 9999;
  min-width: 250px;
  max-width: 90vw;
  background: linear-gradient(90deg,#16a34a 60%,#059669 100%);
  color: #fff;
  padding: 1.1rem 1.7rem;
  border-radius: 1.2rem;
  box-shadow: 0 8px 32px rgba(72,219,151,0.16);
  font-weight: 500;
  font-size: 1.08rem;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s cubic-bezier(.4,0,.2,1), top 0.4s cubic-bezier(.4,0,.2,1);
}
.toast-modern.visible { opacity: 1; pointer-events: auto; top: 4.7rem;}
.toast-warning { background: linear-gradient(90deg,#f59e42 60%,#fbbf24 100%); color: #fff;}
.toast-error { background: linear-gradient(90deg,#ef4444 60%,#f87171 100%); color: #fff;}
#clearConfirmModal { transition: opacity 0.22s, background 0.22s, pointer-events 0.22s;}
#clearConfirmModal.active { opacity:1; pointer-events:auto;}
#clearConfirmModal .scale-95 { transform:scale(0.95);}
#clearConfirmModal.active .scale-95 { transform:scale(1);}

/* Tambahan warning modal konfirmasi keluar */
.modal-bg {
  display: none;
  position: fixed;
  z-index: 99999;
  inset: 0;
  background: #0005;
  align-items: center;
  justify-content: center;
}
.modal-bg.active { display: flex; }
.modal-confirm {
  background: #fff;
  border-radius: 14px;
  padding: 2rem 2.1rem 1.5rem 2.1rem;
  min-width: 300px;
  box-shadow: 0 8px 36px #0003;
  text-align: center;
  animation: popin 0.18s;
}
@keyframes popin { from { transform: scale(0.94); opacity: 0.5; } to { transform: scale(1); opacity:1; } }
.modal-title { color: #d39a11; font-weight: bold; font-size: 1.2em; margin-bottom: 1em; display: flex; align-items: center; justify-content: center;}
.modal-title svg { margin-right: 0.6em; }
.modal-actions { margin-top: 1.5em; display: flex; gap: 1.2em; justify-content: center; }
.modal-actions button { padding: 0.5em 1.3em; border-radius: 8px; border: none; font-weight: bold; font-size: 1em; }
.modal-ok { background: #ffd600; color: #35370a;}
.modal-cancel { background: #f3f4f6; color: #555;}
.warning-phone { color: #e53935; font-size: 1em; margin-top: 0.5em; display:none; }

/* Modal wajib isi nomor telepon, mirip desain browser (gambar 2) */
#phoneRequiredOverlay {
  display: none;
  position: fixed;
  z-index: 99999;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.10);
  align-items: flex-start;
  justify-content: center;
}
#phoneRequiredOverlay.active { display: flex; }
#phoneRequiredModal {
  margin-top: 44px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 32px #0002;
  min-width: 340px;
  padding: 32px 32px 22px 32px;
  text-align: center;
  border: 1.5px solid #a7f3d0;
  animation: popin 0.16s;
}
@keyframes popin { from { transform: scale(0.95); opacity: 0.7; } to { transform: scale(1); opacity:1; } }
#phoneRequiredModal .modal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.7em;
}
#phoneRequiredModal .modal-msg {
  color: #444;
  font-size: 1.07em;
  margin-bottom: 1.8em;
}
#phoneRequiredModal .modal-actions {
  display: flex;
  gap: 14px;
  justify-content: flex-end;
}
#phoneRequiredModal .modal-btn {
  border: none;
  border-radius: 7px;
  font-weight: 600;
  font-size: 1em;
  padding: 0.5em 1.6em;
  cursor: pointer;
  transition: background 0.13s;
}
#phoneRequiredModal .modal-btn-ok {
  color: #fff;
  background: linear-gradient(90deg,#6366f1 0%,#4f46e5 100%);
  box-shadow: 0 2px 8px #6366f133;
}
#phoneRequiredModal .modal-btn-ok:hover { background: linear-gradient(90deg,#4f46e5 0%,#6366f1 100%);}
#phoneRequiredModal .modal-btn-cancel {
  background: #f3f4f6;
  color: #444;
}
#phoneRequiredModal .modal-btn-cancel:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
#phoneRequiredModal .modal-title svg {
  vertical-align: middle;
  margin-right: 0.4em;
}
</style>

<section class="py-12 bg-green-50">
  <div class="profile-main-container">
    <h1 class="mb-10 text-3xl font-extrabold text-center text-green-800 md:text-4xl">
      Profil Saya
    </h1>
    <div class="relative w-full mb-8">
      <div class="absolute inset-y-0 left-0 w-1 border-l-2 border-green-200 rounded-full pointer-events-none"></div>
      <div class="absolute inset-y-0 right-0 w-1 border-r-2 border-green-200 rounded-full pointer-events-none"></div>
      <div class="grid grid-cols-1 gap-8 px-1 pt-4 pb-4 sm:px-8">

        <?php if(auth()->guard()->check()): ?>
          
          <div class="profile-card">
            <div class="mb-5 profile-row">
              <img src="<?php echo e($user && $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : (optional($user->avatar)->url ?? asset('images/default-user.png'))); ?>"
                   alt="Avatar" class="profile-avatar" />
              <div class="profile-info-group">
                <div>
                  <div class="text-2xl font-bold text-green-900 break-words"><?php echo e($user ? $user->name : ''); ?></div>
                  <div class="text-sm text-green-700 break-words"><?php echo e($user ? $user->email : ''); ?></div>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <?php $__currentLoopData = $user && $user->roles ? $user->roles->filter(function($r){ return !in_array(strtolower(data_get($r, 'name', '')), ['admin','developer']); }) : []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-900 bg-green-200 rounded-full shadow"><?php echo e(data_get($role, 'name', '')); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(($user && $user->roles ? $user->roles->filter(function($r){ return !in_array(strtolower(data_get($r, 'name', '')), ['admin','developer']); })->count() == 0 : empty($user->roles))): ?>
                        <span class="inline-block px-3 py-1 text-xs text-gray-400 bg-gray-100 rounded-full">Tanpa Peran</span>
                    <?php endif; ?>
                </div>
                <div class="mt-4 text-sm text-green-900">
                  <div><span class="font-semibold">Tanggal Daftar:</span> <?php echo e($createdAt ? Carbon::parse($createdAt)->format('d M Y') : '-'); ?></div>
                  <?php if($user && !empty($user->phone)): ?>
                    <div><span class="font-semibold">Nomor HP:</span> <?php echo e($user->phone); ?></div>
                  <?php endif; ?>
                  <?php if($user && !empty($user->plain_password)): ?>
                    <div class="mt-2 profile-password-row">
                      <label>Password Lama:</label>
                      <input type="password" value="<?php echo e($user->plain_password); ?>" id="plainPasswordProfile" readonly />
                      <button type="button" id="togglePlainPasswordProfile" tabindex="-1" aria-label="Lihat Password">
                        <svg id="plainPasswordEyeProfile" xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <span class="helper">(jangan bagikan ke siapapun)</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if($addresses && $addresses->count()): ?>
                  <div class="mt-4">
                    <div class="mb-1 font-semibold text-green-800">Alamat Saya:</div>
                    <ul class="space-y-2">
                      <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex flex-col p-3 border border-green-100 rounded-lg bg-green-50">
                          <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-200 text-green-800"><?php echo e($address->label ?? '-'); ?></span>
                            <?php if($address->is_primary): ?>
                              <span class="px-2 py-0.5 text-xs rounded-full bg-green-600 text-white font-semibold">Utama</span>
                            <?php endif; ?>
                          </div>
                          <div class="text-sm text-green-900">
                            <span class="font-semibold">Penerima:</span> <?php echo e($address->recipient ?? '-'); ?><br>
                            <span class="font-semibold">No. HP:</span> <?php echo e($address->phone_number ?? '-'); ?><br>
                            <span class="font-semibold">Alamat:</span> <?php echo e($address->full_address ?? '-'); ?><br>
                            <span class="font-semibold">Kota:</span> <?php echo e($address->city ?? '-'); ?>,
                            <span class="font-semibold">Kode Pos:</span> <?php echo e($address->zip_code ?? '-'); ?>

                          </div>
                        </li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="flex justify-end gap-2 mt-4 text-right">
              <button id="editProfileBtn" type="button"
                class="px-4 py-2 profile-form-btn save">
                Edit Profil
              </button>
                <form id="clearExpiredOrdersForm" action="<?php echo e(route('user.orders.clear_expired')); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" id="clearExpiredOrdersBtn"
                    class="px-4 py-2 text-yellow-800 bg-yellow-100 border border-yellow-300 profile-form-btn cancel hover:bg-yellow-200">
                    Bersihkan Pesanan Kadaluarsa
                    </button>
                </form>
            </div>
          </div>

          <?php if(session('success')): ?>
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
              <?php echo e(session('success')); ?>

            </div>
          <?php endif; ?>

          
          <div id="editProfileForm" class="hidden edit-profile-card">
            <h2 class="mb-4 text-xl font-semibold text-green-800">Edit Profil</h2>
            <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" class="space-y-5" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>

              <div>
                <label for="name" class="block mb-1 profile-form-label">Nama</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value="<?php echo e(old('name', $user ? $user->name : '')); ?>"
                  class="w-full px-3 py-2 border rounded profile-form-input <?php echo e($errors->has('name') ? 'border-red-500' : 'border-green-200'); ?>"
                  required
                >
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div>
                <label for="email" class="block mb-1 profile-form-label">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="<?php echo e(old('email', $user ? $user->email : '')); ?>"
                  class="w-full px-3 py-2 border rounded profile-form-input <?php echo e($errors->has('email') ? 'border-red-500' : 'border-green-200'); ?>"
                  required
                >
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div>
                <label for="phone" class="block mb-1 profile-form-label">Nomor HP</label>
                <input
                  type="text"
                  id="phone"
                  name="phone"
                  inputmode="numeric"
                  pattern="^(08|\+628)[0-9]{8,13}$"
                  placeholder="Contoh: 081234567890"
                  value="<?php echo e(old('phone', $user ? $user->phone : '')); ?>"
                  class="w-full px-3 py-2 border rounded profile-form-input <?php echo e($errors->has('phone') ? 'border-red-500' : 'border-green-200'); ?>"
                  required
                >
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div>
                <label for="profile_image" class="block mb-1 profile-form-label">Foto Profil</label>
                <input
                  type="file"
                  id="profile_image"
                  name="profile_image"
                  accept="image/*"
                  class="w-full px-3 py-2 border rounded profile-form-input <?php echo e($errors->has('profile_image') ? 'border-red-500' : 'border-green-200'); ?>"
                >
                <?php $__errorArgs = ['profile_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($user && $user->profile_photo_path): ?>
                  <img src="<?php echo e(asset('storage/' . $user->profile_photo_path)); ?>" alt="Foto Profil" class="object-cover w-16 h-16 mt-2 border rounded-full">
                <?php endif; ?>
              </div>

              <div>
                <label class="block mb-1 profile-form-label">Peran (Role)</label>
                <div class="flex flex-wrap gap-2">
                  <?php $__currentLoopData = $filteredRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center">
                      <input
                        type="checkbox"
                        name="roles[]"
                        value="<?php echo e($role->id); ?>"
                        <?php if($user && collect(old('roles', $user->roles->pluck('id')->toArray()))->contains($role->id)): ?> checked <?php endif; ?>
                        class="text-green-600 border-gray-300 rounded shadow-sm focus:ring-green-500"
                      >
                      <span class="ml-2"><?php echo e($role->name); ?></span>
                    </label>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="mt-1 text-xs text-gray-500">Pilih hingga tiga peran.</p>
              </div>

              <div>
                <label for="current_password" class="block mb-1 profile-form-label">Password Sekarang <span class="text-xs text-gray-500">(isi jika ingin ganti password)</span></label>
                <div class="relative">
                  <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="w-full px-3 py-2 border rounded pr-10 profile-form-input <?php echo e($errors->has('current_password') ? 'border-red-500' : 'border-green-200'); ?>"
                  >
                  <button type="button" onclick="togglePassword('current_password', this)" class="input-eye-btn" aria-label="Tampilkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div>
                <label for="password" class="block mb-1 profile-form-label">Password Baru</label>
                <div class="relative">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-3 py-2 border rounded pr-10 profile-form-input <?php echo e($errors->has('password') ? 'border-red-500' : 'border-green-200'); ?>"
                  >
                  <button type="button" onclick="togglePassword('password', this)" class="input-eye-btn" aria-label="Tampilkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div>
                <label for="password_confirmation" class="block mb-1 profile-form-label">Konfirmasi Password Baru</label>
                <div class="relative">
                  <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-3 py-2 pr-10 border border-green-200 rounded profile-form-input"
                  >
                  <button type="button" onclick="togglePassword('password_confirmation', this)" class="input-eye-btn" aria-label="Tampilkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </div>

              <div class="flex justify-end gap-2">
                <button
                  type="submit"
                  class="px-4 py-2 profile-form-btn save"
                >
                  Simpan Perubahan
                </button>
                <button type="button" id="cancelEditBtn" class="px-4 py-2 profile-form-btn cancel">
                  Batal
                </button>
              </div>
            </form>
          </div>
          <form action="<?php echo e(route('user.address.store')); ?>" method="POST" class="alamat-form-card" id="addressForm">
            <?php echo csrf_field(); ?>
            <h2 class="mb-2 text-lg font-semibold text-green-800">Tambah Alamat Baru</h2>
            <div>
                <label class="block mb-1 font-semibold text-green-900">Label Alamat</label>
                <input name="label" required maxlength="20" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block mb-1 font-semibold text-green-900">Penerima</label>
                <input name="recipient" required maxlength="50" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block mb-1 font-semibold text-green-900">Nomor HP</label>
                <input name="phone_number" required maxlength="20" inputmode="numeric" pattern="^(08|\+628)[0-9]{8,13}$" placeholder="081234567890" class="w-full px-3 py-2 border rounded" />
                <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div id="addressInputGroup">
                <label class="block mb-1 font-semibold text-green-900">Alamat Lengkap</label>
                <div id="gmapsAutocompleteWrapper">
                  <gmpx-place-autocomplete
                    id="full_address"
                    style="width:100%;"
                    placeholder="Cari atau ketik alamat lengkap"
                    hide-branding="true"
                    required
                  ></gmpx-place-autocomplete>
                </div>
                <div class="flex gap-2 mt-1">
                  <button type="button" onclick="getMyLocation()" class="px-2 py-1 text-xs transition duration-150 bg-green-100 rounded hover:bg-green-200">Ambil dari Lokasi Saya</button>
                  <button type="button" onclick="enableManualAddress()" id="manualAddressBtn" class="px-2 py-1 text-xs transition duration-150 bg-yellow-100 rounded hover:bg-yellow-200">Input Manual</button>
                </div>
                <small class="text-xs text-gray-500">Ketik manual, gunakan autocomplete Google Maps, atau klik tombol lokasi.</small>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-green-900">Kota</label>
                <input name="city" id="city" required maxlength="50" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block mb-1 font-semibold text-green-900">Kode Pos</label>
                <input name="zip_code" id="zip_code" required maxlength="10" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="is_primary" value="1" class="text-green-600 rounded" />
                  <span class="ml-2">Jadikan alamat utama</span>
                </label>
            </div>
            <button type="submit" class="px-4 py-2 text-white transition duration-150 bg-green-600 rounded hover:bg-green-700">Simpan Alamat</button>
          </form>

          <div id="toastNotif" class="toast-modern"></div>

          <!-- Modal Konfirmasi Modern -->
          <div id="clearConfirmModal" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/30 transition-all duration-300 opacity-0 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-[95vw] max-w-xs md:max-w-md p-6 relative scale-95 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <svg class="text-yellow-400 w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" class="fill-yellow-50"></circle>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
                <span class="text-lg font-semibold text-yellow-700">Konfirmasi</span>
              </div>
              <div class="mb-5 text-base leading-relaxed text-gray-800">
                Hapus semua pesanan yang dibatalkan, kadaluarsa, dan waktu habis?
              </div>
              <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="px-4 py-2 font-semibold text-gray-700 transition bg-gray-100 rounded-lg modal-cancel hover:bg-gray-200">Batal</button>
                <button type="button" class="px-4 py-2 font-bold text-yellow-900 transition bg-yellow-400 rounded-lg shadow modal-ok hover:bg-yellow-500">OK</button>
              </div>
              <button type="button" class="absolute text-gray-400 top-2 right-3 hover:text-gray-700" onclick="closeConfirmModal()">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

        <div class="profile-card">
        <div class="mb-5 profile-row">
            <img src="/images/azka-garden.jpg" alt="Azka Garden" class="profile-avatar" />
            <div class="profile-info-group">
            <div>
                <div class="text-2xl font-bold text-green-900 break-words">Azka Garden</div>
                <div class="text-sm text-green-700 break-words">Kios Tanaman Hias & Bibit Buah – Milik keluarga Pak Hendrik</div>
            </div>
            <div class="flex flex-wrap gap-2 mt-2">
                <span class="profile-badge">Toko 24 Jam</span>
                <span class="profile-badge">Whatsapp: <a href="https://wa.me/6289635086182" target="_blank">0896-3508-6182</a></span>
                <span class="profile-badge">Lokasi: Depok</span>
            </div>
            <div class="mt-4 text-sm text-green-900">
                <div><span class="font-semibold">Alamat:</span> Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412</div>
                <div><span class="font-semibold">Plus Code:</span> HRQH+3VP</div>
                <div><span class="font-semibold">Google Maps:</span>
                <a href="https://www.google.com/maps/place/Toko+Bunga+Hendrik/@-6.4122794,106.829692,17z/data=!3m1!4b1!4m5!3m4!1s0x2e69ebaf7dd7316d:0x91c591170331d44a!8m2!3d-6.4122794!4d106.829692" target="_blank" class="underline text-emerald-600">Lihat Lokasi</a>
                </div>
                <div class="mt-2">
                <span class="font-semibold">Tercatat di Semuabis sebagai nursery penyedia:</span>
                <div class="profile-desc-list">
                    <div>Bibit buah dan tanaman hias</div>
                    <div>Pot, pupuk, media tanam siap pakai</div>
                    <div>Perlengkapan taman lainnya</div>
                </div>
                <span class="font-semibold">Profil Bisnis:</span>
                <a href="https://semuabis.com/toko-bunga-hendrik-0896-3508-6182" target="_blank" class="underline text-emerald-600">Lihat di Semuabis</a>
                </div>
            </div>
            </div>
        </div>

        <div class="profile-section-title">Tentang Azka Garden</div>
        <div class="info-paragraph">
            Azka Garden adalah kios tanaman hias dan bibit buah milik keluarga Pak Hendrik yang berlokasi di Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412 (Plus Code: HRQH+3VP). Toko buka 24 jam dengan nomor WhatsApp aktif <b>0896-3508-6182</b> dan tercatat di Semuabis sebagai nursery penyedia bibit, pot, pupuk, dan perlengkapan taman.
        </div>
        <div class="info-paragraph">
            Azka Garden menawarkan tanaman populer seperti <b>Philodendron</b>, <b>Caladium</b>, <b>Pucuk Merah</b>, <b>Aglonema</b>, <b>Sansevieria</b>, <b>Monstera</b>, bibit buah (mangga, jambu, jeruk, alpukat, durian, dll.), tanaman air, bonsai, serta media tanam siap pakai dan perlengkapan taman lainnya. Mereka menggabungkan pelayanan personal dengan kehadiran digital untuk memberikan pengalaman belanja tanaman terbaik bagi pelanggan di Depok dan sekitarnya.
        </div>

        <div class="profile-section-title">Kontak & Marketplace</div>
        <div class="profile-link-list-clean">
            <div>
                <span class="label-social" style="color:#25D366;">&#x1F4AC; WhatsApp</span>
                <a href="https://wa.me/6289635086182" target="_blank">0896-3508-6182</a>
            </div>
            <div>
                <span class="label-social" style="color:#42B549;">&#x1F6CD;&#xFE0F; Tokopedia</span>
                <a href="https://www.tokopedia.com/hendrikfloris" target="_blank">Toko Bunga Hendrik</a>
            </div>
        </div>

        <div class="profile-section-title">Produk & Layanan Populer</div>
        <div class="profile-desc-list">
            <div>Philodendron, Caladium, Pucuk Merah, Aglonema, Sansevieria, Monstera</div>
            <div>Bonsai & tanaman air</div>
            <div>Bibit buah: mangga, jambu, jeruk, alpukat, durian, dll.</div>
            <div>Pot, pupuk, media tanam, perlengkapan taman</div>
            <div>Konsultasi dan bantuan pemilihan tanaman via WhatsApp</div>
        </div>
        </div>


        <!-- Modal WAJIB ISI NOMOR HP -->
        <div id="phoneRequiredOverlay">
        <div id="phoneRequiredModal">
            <div class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2">
                    <circle cx="12" cy="12" r="10" fill="#EEF2FF"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
                Nomor Telepon Wajib Diisi
            </div>
            <div class="modal-msg">
                Anda harus mengisi <b>nomor telepon</b> yang valid untuk melanjutkan.<br>
                Halaman ini terkunci hingga Anda melengkapi nomor telepon Anda.
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-ok" onclick="focusPhoneInput()" id="modalPhoneOkBtn">Isi Sekarang</button>
            </div>
        </div>
        </div>

        <?php echo $__env->make('User.profile.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <?php if(auth()->guard()->guest()): ?>
          <div class="p-6 border border-green-200 rounded-lg shadow bg-green-50">
            <p class="text-green-700">Anda belum login. Silakan <a href="<?php echo e(route('login')); ?>" class="text-green-600 underline">login</a> untuk melihat profil Anda.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\azka-garden\resources\views/user/profile/index.blade.php ENDPATH**/ ?>