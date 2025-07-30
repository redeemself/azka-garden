@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
@php
    use Carbon\Carbon;
    $user = auth()->user();
    $createdAt = isset($user) && isset($user->getAttributes()['created_at']) ? $user->getAttributes()['created_at'] : null;
    
    // Definisi peran
    $userRoles = ['user', 'customer', 'guest'];
    $currentRole = isset($user) && isset($user->roles) && $user->roles->count() > 0 
        ? strtolower($user->roles->first()->name) 
        : 'guest';
    
    // Filter peran admin/developer
    $filteredRoles = isset($allRoles) ? $allRoles->filter(function($role) {
        return !in_array(strtolower($role->name), ['admin', 'developer']);
    }) : collect();
    
    // Alamat
    $addresses = isset($user) && method_exists($user, 'addresses') ? $user->addresses()->get() : collect();
    $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
    
    // Data semu untuk pengguna tanpa informasi
    $mockPhone = '08123456789';
    $mockAddress = [
        'label' => 'Rumah',
        'recipient' => $user ? $user->name : 'Pengguna',
        'phone_number' => $mockPhone,
        'full_address' => 'Jl. Raya KSU No. 42, Tirtajaya',
        'city' => 'Depok',
        'zip_code' => '16412',
        'is_primary' => true
    ];
@endphp

@if(!$hasAddress && $user)
    <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Anda belum mengisi alamat. Silakan lengkapi alamat Anda agar bisa melakukan pembelian.</span>
        </div>
    </div>
@endif

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
.profile-header {
  display: flex;
  align-items: center;
  width: 100%;
  margin-bottom: 2rem;
}
.profile-avatar-container {
  flex-shrink: 0;
  margin-right: 2rem;
}
.profile-info-container {
  flex: 1;
}
.profile-avatar {
  width: 130px;
  height: 130px;
  box-shadow: 0 2px 14px 0 rgba(72,219,151,0.18);
  object-fit: cover;
  border-radius: 50%;
  border: 3.5px solid #34d399;
  background: #f3f4f6;
  transition: border .18s;
}
.profile-info-group {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
  min-width: 0;
}
.user-details {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.user-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #065f46;
  margin: 0;
  line-height: 1.2;
}
.user-email {
  font-size: 1rem;
  color: #059669;
  margin: 0;
}
.roles-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
}
.profile-section-title {
  font-size: 1.22rem;
  font-weight: 700;
  color: #047857;
  margin-top: 2.2rem;
  margin-bottom: 0.7rem;
  letter-spacing: 0.01em;
}
.profile-details {
  margin-top: 15px;
}
.profile-detail-item {
  display: flex;
  margin-bottom: 8px;
}
.profile-detail-label {
  font-weight: 600;
  color: #065f46;
  width: 150px;
  flex-shrink: 0;
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
.role-badge {
  display: inline-flex;
  align-items: center;
  font-size: 0.9rem;
  padding: 5px 12px;
  border-radius: 9999px;
  font-weight: 600;
  margin-right: 6px;
}
.role-user {
  background-color: #dcfce7;
  color: #166534;
  border: 1px solid #a7f3d0;
}
.role-customer {
  background-color: #d1fae5;
  color: #047857;
  border: 1px solid #99f6e4;
}
.role-guest {
  background-color: #f8fafc;
  color: #475569;
  border: 1px solid #e2e8f0;
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

/* Updated action buttons styles */
.actions-container {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 20px;
  flex-wrap: wrap;
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
  padding: 0.75rem 1.25rem;
  border-radius: 0.5rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
}

.profile-form-btn.save:hover {
  background: linear-gradient(90deg,#15803d 60%,#047857 100%);
  box-shadow: 0 4px 12px 0 rgba(16,185,129,.14);
  transform: translateY(-1px);
}

/* Address button specific styling */
.address-btn {
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
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
.address-section {
  margin-top: 30px;
}
.address-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
.address-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #065f46;
  margin: 0;
}
.addresses-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 16px;
}
.address-card {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 0.75rem;
  padding: 1rem;
  position: relative;
  height: 100%;
}
.address-actions {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  display: flex;
  gap: 0.5rem;
}
.address-badge {
  display: inline-block;
  padding: 0.15rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-right: 0.5rem;
}
.address-badge-primary {
  background-color: #10b981;
  color: white;
}
.address-badge-secondary {
  background-color: #d1fae5;
  color: #047857;
}
.address-details {
  margin-top: 10px;
}
.address-recipient {
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 4px;
}
.address-phone {
  color: #4b5563;
  font-size: 0.95rem;
  margin-bottom: 6px;
}
.address-full {
  color: #4b5563;
  font-size: 0.95rem;
  margin-bottom: 4px;
}
.address-city-zip {
  color: #4b5563;
  font-size: 0.95rem;
}
.view-all-addresses-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #047857;
  color: white;
  font-weight: 600;
  padding: 0.625rem 1.25rem;
  border-radius: 0.5rem;
  text-decoration: none;
  transition: all 0.2s ease;
  border: none;
  cursor: pointer;
  gap: 0.5rem;
}
.view-all-addresses-btn:hover {
  background: #065f46;
  transform: translateY(-1px);
}
::-webkit-input-placeholder { color: #a7a7a7; }
::-moz-placeholder { color: #a7a7a7; }
:-ms-input-placeholder { color: #a7a7a7; }
::placeholder { color: #a7a7a7; }

@media (max-width: 1200px) {
  .profile-main-container { max-width: 98vw; }
  .profile-card, .edit-profile-card, .alamat-form-card { padding: 1.5rem 1.2rem; }
}
@media (max-width: 800px) {
  .profile-main-container { padding-left: 15px; padding-right: 15px; }
  .profile-header { flex-direction: column; align-items: center; text-align: center; }
  .profile-avatar-container { margin-right: 0; margin-bottom: 1.5rem; }
  .profile-avatar { width: 120px; height: 120px; }
  .roles-container { justify-content: center; }
  .profile-detail-item { flex-direction: column; margin-bottom: 15px; }
  .profile-detail-label { width: 100%; margin-bottom: 5px; }
  
  /* Make buttons stack vertically on mobile */
  .actions-container { 
    flex-direction: column; 
    align-items: stretch;
  }
  
  .profile-form-btn.save,
  .address-btn {
    justify-content: center;
    text-align: center;
  }
  
  .addresses-container { grid-template-columns: 1fr; }
  .edit-profile-card, .alamat-form-card { padding: 1.5rem 1rem; margin-top: 1.5rem; margin-bottom: 1rem; }
}
@media (max-width: 480px) {
  .profile-avatar { width: 100px; height: 100px; }
  .user-name { font-size: 1.3rem; }
  .user-email { font-size: 0.9rem; }
  .profile-card, .edit-profile-card, .alamat-form-card { padding: 1.2rem 0.8rem; }
  .helper { font-size: 0.76em; }
  .role-badge { font-size: 0.8rem; padding: 4px 10px; }
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
.modal-title { color: #065f46; font-weight: bold; font-size: 1.2em; margin-bottom: 1em; display: flex; align-items: center; justify-content: center;}
.modal-title svg { margin-right: 0.6em; }
.modal-actions { margin-top: 1.5em; display: flex; gap: 1.2em; justify-content: center; }
.modal-actions button { padding: 0.5em 1.3em; border-radius: 8px; border: none; font-weight: bold; font-size: 1em; }
.modal-ok { background: #10b981; color: white;}
.modal-ok:hover { background: #059669; }
.modal-cancel { background: #f3f4f6; color: #374151;}
.modal-cancel:hover { background: #e5e7eb; }
.warning-phone { color: #e53935; font-size: 1em; margin-top: 0.5em; display:none; }

/* Modal wajib isi nomor telepon */
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
  color: #065f46;
  margin-bottom: 0.7em;
}
#phoneRequiredModal .modal-msg {
  color: #1f2937;
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
  background: linear-gradient(90deg,#10b981 0%,#059669 100%);
  box-shadow: 0 2px 8px rgba(16,185,129,0.2);
}
#phoneRequiredModal .modal-btn-ok:hover { background: linear-gradient(90deg,#059669 0%,#047857 100%);}
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

        @auth
          {{-- Card Profil --}}
          <div class="profile-card">
            {{-- Profile Header with Avatar and Info --}}
            <div class="profile-header">
              <div class="profile-avatar-container">
                <img src="{{ $user && $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : (optional($user->avatar)->url ?? asset('images/default-user.png')) }}"
                     alt="Avatar" class="profile-avatar" />
              </div>
              
              <div class="profile-info-container">
                <div class="user-details">
                  <h2 class="user-name">{{ $user ? $user->name : 'Tamu' }}</h2>
                  <p class="user-email">{{ $user ? $user->email : 'guest@example.com' }}</p>
                
                  {{-- Bagian Role/Peran --}}
                  <div class="roles-container">
                    @if($user && $user->roles && $user->roles->count() > 0)
                      @foreach($user->roles->filter(function($r){ return !in_array(strtolower($r->name), ['admin', 'developer']); }) as $role)
                        <span class="role-badge {{ strtolower($role->name) == 'customer' ? 'role-customer' : (strtolower($role->name) == 'user' ? 'role-user' : 'role-guest') }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          {{ ucfirst($role->name) }}
                        </span>
                      @endforeach
                    @else
                      {{-- Tambahkan role default jika tidak ada --}}
                      <span class="role-badge role-user">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        User
                      </span>
                      <span class="role-badge role-customer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Customer
                      </span>
                    @endif
                  </div>
                </div>
                
                {{-- Profile Details --}}
                <div class="profile-details">
                  <div class="profile-detail-item">
                    <div class="profile-detail-label">Tanggal Daftar:</div>
                    <div>{{ $createdAt ? Carbon::parse($createdAt)->format('d M Y') : '30 Jul 2025' }}</div>
                  </div>
                  <div class="profile-detail-item">
                    <div class="profile-detail-label">Nomor HP:</div>
                    <div>{{ $user && !empty($user->phone) ? $user->phone : $mockPhone }}</div>
                  </div>
                  
                  @if($user && !empty($user->plain_password))
                    <div class="profile-password-row">
                      <label>Password Lama:</label>
                      <input type="password" value="{{ $user->plain_password }}" id="plainPasswordProfile" readonly />
                      <button type="button" id="togglePlainPasswordProfile" tabindex="-1" aria-label="Lihat Password">
                        <svg id="plainPasswordEyeProfile" xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <span class="helper">(jangan bagikan ke siapapun)</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            
            {{-- Alamat section --}}
            <div class="address-section">
              <div class="address-header">
                <h3>Alamat Saya</h3>
              </div>
              
              <div class="addresses-container">
                @if($addresses && $addresses->count())
                  @foreach($addresses->take(2) as $address)
                    <div class="address-card">
                      <div class="address-badges">
                        <span class="address-badge address-badge-secondary">{{ $address->label ?? 'Alamat' }}</span>
                        @if($address->is_primary)
                          <span class="address-badge address-badge-primary">Utama</span>
                        @endif
                      </div>
                      <div class="address-details">
                        <div class="address-recipient">{{ $address->recipient ?? ($user ? $user->name : 'Pengguna') }}</div>
                        <div class="address-phone">{{ $address->phone_number ?? $mockPhone }}</div>
                        <div class="address-full">{{ $address->full_address ?? $mockAddress['full_address'] }}</div>
                        <div class="address-city-zip">{{ $address->city ?? $mockAddress['city'] }}, {{ $address->zip_code ?? $mockAddress['zip_code'] }}</div>
                      </div>
                    </div>
                  @endforeach
                @else
                  {{-- Tampilkan alamat semu jika tidak ada alamat --}}
                  <div class="address-card">
                    <div class="address-badges">
                      <span class="address-badge address-badge-secondary">{{ $mockAddress['label'] }}</span>
                      <span class="address-badge address-badge-primary">Utama</span>
                    </div>
                    <div class="address-details">
                      <div class="address-recipient">{{ $mockAddress['recipient'] }}</div>
                      <div class="address-phone">{{ $mockAddress['phone_number'] }}</div>
                      <div class="address-full">{{ $mockAddress['full_address'] }}</div>
                      <div class="address-city-zip">{{ $mockAddress['city'] }}, {{ $mockAddress['zip_code'] }}</div>
                      <p class="mt-2 text-xs text-gray-500">(Alamat contoh - Harap tambahkan alamat asli Anda)</p>
                    </div>
                  </div>
                @endif
              </div>
              
              @if($addresses && $addresses->count() > 2)
                <div class="mt-3 text-center">
                  <span class="text-sm text-gray-600">Menampilkan 2 dari {{ $addresses->count() }} alamat</span>
                </div>
              @endif
            </div>
            
            <!-- Updated buttons area - all three in one container with same styling -->
            <div class="actions-container">
              <!-- Address Button -->
              <a href="{{ route('user.address.index') }}" class="address-btn profile-form-btn save">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Lihat Semua Alamat
              </a>
              
              <!-- Edit Profile Button -->
              <button id="editProfileBtn" type="button" class="profile-form-btn save">
                Edit Profil
              </button>
              
              <!-- Clear Expired Orders Form/Button -->
              <form id="clearExpiredOrdersForm" action="{{ route('user.orders.clear_expired') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" id="clearExpiredOrdersBtn" class="profile-form-btn save">
                  Bersihkan Pesanan Kadaluarsa
                </button>
              </form>
            </div>
          </div>

          @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
              {{ session('success') }}
            </div>
          @endif

          {{-- Form Edit (hidden by default, muncul dengan tombol) --}}
          <div id="editProfileForm" class="hidden edit-profile-card">
            <h2 class="mb-4 text-xl font-semibold text-green-800">Edit Profil</h2>
            <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div>
                <label for="name" class="block mb-1 profile-form-label">Nama</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value="{{ old('name', $user ? $user->name : '') }}"
                  class="w-full px-3 py-2 border rounded profile-form-input {{ $errors->has('name') ? 'border-red-500' : 'border-green-200' }}"
                  required
                >
                @error('name')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="email" class="block mb-1 profile-form-label">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="{{ old('email', $user ? $user->email : '') }}"
                  class="w-full px-3 py-2 border rounded profile-form-input {{ $errors->has('email') ? 'border-red-500' : 'border-green-200' }}"
                  required
                >
                @error('email')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
                  value="{{ old('phone', $user && !empty($user->phone) ? $user->phone : $mockPhone) }}"
                  class="w-full px-3 py-2 border rounded profile-form-input {{ $errors->has('phone') ? 'border-red-500' : 'border-green-200' }}"
                  required
                >
                @error('phone')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="profile_image" class="block mb-1 profile-form-label">Foto Profil</label>
                <input
                  type="file"
                  id="profile_image"
                  name="profile_image"
                  accept="image/*"
                  class="w-full px-3 py-2 border rounded profile-form-input {{ $errors->has('profile_image') ? 'border-red-500' : 'border-green-200' }}"
                >
                @error('profile_image')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                @if($user && $user->profile_photo_path)
                  <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto Profil" class="object-cover w-16 h-16 mt-2 border rounded-full">
                @endif
              </div>

              <div>
                <label class="block mb-1 profile-form-label">Peran (Role)</label>
                <div class="flex flex-wrap gap-2">
                  @foreach($filteredRoles as $role)
                    <label class="inline-flex items-center">
                      <input
                        type="checkbox"
                        name="roles[]"
                        value="{{ $role->id }}"
                        @if($user && collect(old('roles', $user->roles->pluck('id')->toArray()))->contains($role->id)) checked @endif
                        class="text-green-600 border-gray-300 rounded shadow-sm focus:ring-green-500"
                      >
                      <span class="ml-2">{{ $role->name }}</span>
                    </label>
                  @endforeach
                </div>
                @error('roles')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Pilih hingga tiga peran (user, customer, guest).</p>
              </div>

              <div>
                <label for="current_password" class="block mb-1 profile-form-label">Password Sekarang <span class="text-xs text-gray-500">(isi jika ingin ganti password)</span></label>
                <div class="relative">
                  <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="w-full px-3 py-2 border rounded pr-10 profile-form-input {{ $errors->has('current_password') ? 'border-red-500' : 'border-green-200' }}"
                  >
                  <button type="button" onclick="togglePassword('current_password', this)" class="input-eye-btn" aria-label="Tampilkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
                @error('current_password')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="password" class="block mb-1 profile-form-label">Password Baru</label>
                <div class="relative">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-3 py-2 border rounded pr-10 profile-form-input {{ $errors->has('password') ? 'border-red-500' : 'border-green-200' }}"
                  >
                  <button type="button" onclick="togglePassword('password', this)" class="input-eye-btn" aria-label="Tampilkan Password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
                @error('password')
                  <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
          
          {{-- Form tambah alamat --}}
          <form action="{{ route('user.address.store') }}" method="POST" class="alamat-form-card" id="addressForm">
            @csrf
            <h2 class="mb-4 text-xl font-semibold text-green-800">Tambah Alamat Baru</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="block mb-1 font-semibold text-green-900">Label Alamat</label>
                <input name="label" required maxlength="20" class="w-full px-3 py-2 border rounded" placeholder="Rumah, Kantor, dll"/>
              </div>
              <div>
                <label class="block mb-1 font-semibold text-green-900">Penerima</label>
                <input name="recipient" required maxlength="50" class="w-full px-3 py-2 border rounded" value="{{ $user ? $user->name : '' }}" placeholder="Nama penerima"/>
              </div>
            </div>
            <div class="mt-4">
              <label class="block mb-1 font-semibold text-green-900">Nomor HP</label>
              <input name="phone_number" required maxlength="20" inputmode="numeric" pattern="^(08|\+628)[0-9]{8,13}$" value="{{ $user && !empty($user->phone) ? $user->phone : '' }}" placeholder="081234567890" class="w-full px-3 py-2 border rounded" />
              @error('phone_number')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div id="addressInputGroup" class="mt-4">
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
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
              <div>
                <label class="block mb-1 font-semibold text-green-900">Kota</label>
                <input name="city" id="city" required maxlength="50" class="w-full px-3 py-2 border rounded" placeholder="Nama kota" />
              </div>
              <div>
                <label class="block mb-1 font-semibold text-green-900">Kode Pos</label>
                <input name="zip_code" id="zip_code" required maxlength="10" class="w-full px-3 py-2 border rounded" placeholder="Kode pos" />
              </div>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center">
                  <input type="checkbox" name="is_primary" value="1" class="text-green-600 rounded" />
                  <span class="ml-2">Jadikan alamat utama</span>
                </label>
            </div>
            <div class="flex justify-between mt-6">
              <a href="{{ route('user.address.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-800 bg-green-100 border border-green-200 rounded hover:bg-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Daftar Alamat
              </a>
              <button type="submit" class="px-4 py-2 text-white transition duration-150 bg-green-600 rounded hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Simpan Alamat
              </button>
            </div>
          </form>

          <div id="toastNotif" class="toast-modern"></div>

          <!-- Modal Konfirmasi -->
          <div id="clearConfirmModal" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/30 transition-all duration-300 opacity-0 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-[95vw] max-w-xs md:max-w-md p-6 relative scale-95 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" class="fill-green-50"></circle>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
                <span class="text-lg font-semibold text-green-800">Konfirmasi</span>
              </div>
              <div class="mb-5 text-base leading-relaxed text-gray-800">
                Hapus semua pesanan yang dibatalkan, kadaluarsa, dan waktu habis?
              </div>
              <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="px-4 py-2 font-semibold text-gray-700 transition bg-gray-100 rounded-lg modal-cancel hover:bg-gray-200">Batal</button>
                <button type="button" class="px-4 py-2 font-bold text-white transition bg-green-600 rounded-lg shadow modal-ok hover:bg-green-700">OK</button>
              </div>
              <button type="button" class="absolute text-gray-400 top-2 right-3 hover:text-gray-700" onclick="closeConfirmModal()">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          {{-- Azka Garden Info Card --}}
          <div class="profile-card">
            <div class="profile-header">
              <div class="profile-avatar-container">
                <img src="/images/azka-garden.jpg" alt="Azka Garden" class="profile-avatar" />
              </div>
              <div class="profile-info-container">
                <div class="user-details">
                  <h2 class="user-name">Azka Garden</h2>
                  <p class="user-email">Kios Tanaman Hias & Bibit Buah – Milik keluarga Pak Hendrik</p>
                  
                  <div class="flex flex-wrap gap-2 mt-3">
                    <span class="profile-badge">Toko 24 Jam</span>
                    <span class="profile-badge">Whatsapp: <a href="https://wa.me/6289635086182" target="_blank">0896-3508-6182</a></span>
                    <span class="profile-badge">Lokasi: Depok</span>
                  </div>
                </div>
                
                <div class="profile-details">
                  <div class="profile-detail-item">
                    <div class="profile-detail-label">Alamat:</div>
                    <div>Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412</div>
                  </div>
                  <div class="profile-detail-item">
                    <div class="profile-detail-label">Plus Code:</div>
                    <div>HRQH+3VP</div>
                  </div>
                  <div class="profile-detail-item">
                    <div class="profile-detail-label">Google Maps:</div>
                    <div>
                      <a href="https://www.google.com/maps/place/Toko+Bunga+Hendrik/@-6.4122794,106.829692,17z/data=!3m1!4b1!4m5!3m4!1s0x2e69ebaf7dd7316d:0x91c591170331d44a!8m2!3d-6.4122794!4d106.829692" 
                        target="_blank" class="underline text-emerald-600">Lihat Lokasi</a>
                    </div>
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
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2">
                  <circle cx="12" cy="12" r="10" fill="#ecfdf5"/>
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

          @include('User.profile.script')
        @endauth

        @guest
          <div class="p-6 border-2 border-green-200 rounded-lg shadow-sm bg-green-50">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-bold text-green-800">Anda belum login</h3>
                <p class="text-green-700">Silakan <a href="{{ route('login') }}" class="font-semibold text-green-600 underline">login</a> atau <a href="{{ route('register') }}" class="font-semibold text-green-600 underline">daftar</a> untuk melihat profil dan mengakses fitur lengkap.</p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3">
              <div class="p-4 bg-white border border-green-100 rounded-lg">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <h4 class="mb-1 text-base font-semibold text-center text-green-800">User</h4>
                <p class="text-sm text-center text-gray-600">Akses dasar ke platform untuk menjelajahi produk dan informasi tanaman tanpa kemampuan transaksi.</p>
              </div>
              
              <div class="p-4 bg-white border border-green-100 rounded-lg">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
                <h4 class="mb-1 text-base font-semibold text-center text-green-800">Customer</h4>
                <p class="text-sm text-center text-gray-600">Kemampuan penuh untuk berbelanja, melakukan transaksi, dan pelacakan riwayat pembelian.</p>
              </div>
              
              <div class="p-4 bg-white border border-green-100 rounded-lg">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h4 class="mb-1 text-base font-semibold text-center text-green-800">Guest</h4>
                <p class="text-sm text-center text-gray-600">Akses terbatas untuk melihat produk tanpa kemampuan untuk menyimpan preferensi atau melakukan transaksi.</p>
              </div>
            </div>
            
            <div class="p-4 mt-6 text-center bg-green-100 rounded-lg">
              <p class="text-green-800">Tanggal: {{ date('Y-m-d H:i:s') }} | Pengguna: {{ 'mulyadafa' }}</p>
            </div>
          </div>
        @endguest
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Edit Profile Toggle
  const editProfileBtn = document.getElementById('editProfileBtn');
  const editProfileForm = document.getElementById('editProfileForm');
  const cancelEditBtn = document.getElementById('cancelEditBtn');
  
  if (editProfileBtn && editProfileForm && cancelEditBtn) {
    editProfileBtn.addEventListener('click', function() {
      editProfileForm.classList.remove('hidden');
    });
    
    cancelEditBtn.addEventListener('click', function() {
      editProfileForm.classList.add('hidden');
    });
  }
  
  // Password Toggle
  function togglePassword(id, button) {
    const passwordInput = document.getElementById(id);
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Update eye icon
    const icon = button.querySelector('svg');
    if (type === 'text') {
      icon.innerHTML = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
      `;
    } else {
      icon.innerHTML = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      `;
    }
  }
  
  // Expose the function globally
  window.togglePassword = togglePassword;
  
  // Toggle Plain Password if element exists
  const togglePlainPasswordBtn = document.getElementById('togglePlainPasswordProfile');
  if (togglePlainPasswordBtn) {
    togglePlainPasswordBtn.addEventListener('click', function() {
      const plainPassword = document.getElementById('plainPasswordProfile');
      const type = plainPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      plainPassword.setAttribute('type', type);
      
      // Update eye icon
      const eyeIcon = document.getElementById('plainPasswordEyeProfile');
      if (type === 'text') {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
      } else {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
      }
    });
  }
  
  // Clear Expired Orders Confirmation
  const clearForm = document.getElementById('clearExpiredOrdersForm');
  const clearBtn = document.getElementById('clearExpiredOrdersBtn');
  const confirmModal = document.getElementById('clearConfirmModal');
  
  if (clearForm && clearBtn && confirmModal) {
    clearBtn.addEventListener('click', function(e) {
      e.preventDefault();
      confirmModal.classList.add('active');
    });
    
    // Confirm button
    const confirmBtn = confirmModal.querySelector('.modal-ok');
    if (confirmBtn) {
      confirmBtn.addEventListener('click', function() {
        clearForm.submit();
      });
    }
    
    // Cancel button
    const cancelBtn = confirmModal.querySelector('.modal-cancel');
    if (cancelBtn) {
      cancelBtn.addEventListener('click', function() {
        closeConfirmModal();
      });
    }
  }
  
  // Close confirmation modal
  window.closeConfirmModal = function() {
    const confirmModal = document.getElementById('clearConfirmModal');
    if (confirmModal) {
      confirmModal.classList.remove('active');
    }
  }
  
  // Google Maps integration for address
  function getMyLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          
          // Reverse geocode to get address
          fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=YOUR_API_KEY`)
            .then(response => response.json())
            .then(data => {
              if (data.results && data.results.length > 0) {
                const addressComponents = data.results[0].address_components;
                const formattedAddress = data.results[0].formatted_address;
                
                // Set address in form
                document.getElementById('full_address').value = formattedAddress;
                
                // Extract city and zip code
                let city = '';
                let zipCode = '';
                
                addressComponents.forEach(component => {
                  if (component.types.includes('administrative_area_level_2')) {
                    city = component.long_name;
                  } else if (component.types.includes('postal_code')) {
                    zipCode = component.long_name;
                  }
                });
                
                if (city) document.getElementById('city').value = city;
                if (zipCode) document.getElementById('zip_code').value = zipCode;
              }
            })
            .catch(error => {
              console.error('Error fetching address:', error);
              showToast('Gagal mendapatkan alamat dari lokasi Anda', 'error');
            });
        },
        function(error) {
          console.error('Geolocation error:', error);
          let errorMsg = 'Gagal mendapatkan lokasi Anda';
          
          switch(error.code) {
            case error.PERMISSION_DENIED:
              errorMsg = 'Akses lokasi ditolak oleh pengguna';
              break;
            case error.POSITION_UNAVAILABLE:
              errorMsg = 'Informasi lokasi tidak tersedia';
              break;
            case error.TIMEOUT:
              errorMsg = 'Permintaan lokasi habis waktu';
              break;
          }
          
          showToast(errorMsg, 'error');
        }
      );
    } else {
      showToast('Browser Anda tidak mendukung geolokasi', 'error');
    }
  }
  
  function enableManualAddress() {
    const wrapper = document.getElementById('gmapsAutocompleteWrapper');
    if (wrapper) {
      wrapper.innerHTML = '<textarea id="full_address" name="full_address" class="w-full px-3 py-2 border rounded" rows="3" required placeholder="Masukkan alamat lengkap secara manual"></textarea>';
    }
  }
  
  // Make functions available globally
  window.getMyLocation = getMyLocation;
  window.enableManualAddress = enableManualAddress;
  
  // Toast notifications
  function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotif');
    if (toast) {
      toast.textContent = message;
      toast.className = 'toast-modern';
      
      if (type === 'error') {
        toast.classList.add('toast-error');
      } else if (type === 'warning') {
        toast.classList.add('toast-warning');
      }
      
      toast.classList.add('visible');
      
      setTimeout(() => {
        toast.classList.remove('visible');
      }, 5000);
    }
  }
  
  window.showToast = showToast;
  
  // Phone number requirement modal
  const phoneRequiredOverlay = document.getElementById('phoneRequiredOverlay');
  const phoneInput = document.getElementById('phone');
  
  window.focusPhoneInput = function() {
    if (phoneRequiredOverlay) {
      phoneRequiredOverlay.classList.remove('active');
    }
    
    // Open edit form if not open
    if (editProfileForm && editProfileForm.classList.contains('hidden')) {
      editProfileForm.classList.remove('hidden');
    }
    
    // Focus on phone input
    if (phoneInput) {
      phoneInput.focus();
    }
  }
  
  // Check if phone is missing and show modal
  const userPhone = '{{ $user && !empty($user->phone) ? $user->phone : "" }}';
  if (!userPhone && phoneRequiredOverlay) {
    setTimeout(() => {
      phoneRequiredOverlay.classList.add('active');
    }, 1000);
  }
});
</script>
@endsection