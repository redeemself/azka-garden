<script>
function isValidIndoPhone(phone) {
    let cleaned = phone.replace(/\D/g, '');
    if(phone.startsWith('+62')) cleaned = '0' + cleaned.slice(2);
    return /^08\d{8,13}$/.test(cleaned);
}
// Modern Toast
function showToast(msg, type = '') {
    var toast = document.getElementById('toastNotif');
    if (!toast) return;
    toast.textContent = msg;
    toast.className = 'toast-modern visible' + (type ? ' toast-' + type : '');
    setTimeout(function() {
        toast.classList.remove('visible');
    }, 3200);
}

// Modal konfirmasi modern dinamis
function openConfirmModal({id = 'confirmModal', onOk, onCancel} = {}) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('active');
    setTimeout(()=>modal.querySelector('.modal-ok')?.focus(), 110);
    const cleanup = () => {
        modal.classList.remove('active');
        modal.querySelector('.modal-ok')?.removeEventListener('click', okHandler);
        modal.querySelector('.modal-cancel')?.removeEventListener('click', cancelHandler);
        modal.removeEventListener('click', overlayHandler);
    };
    const okHandler = () => { cleanup(); onOk && onOk(); };
    const cancelHandler = () => {
        cleanup();
        window.location.href = '<?php echo e(route('user.profile.index')); ?>';
        if (typeof onCancel === 'function') onCancel();
    };
    const overlayHandler = (e) => { if(e.target === modal) cleanup(); };
    modal.querySelector('.modal-ok')?.addEventListener('click', okHandler);
    modal.querySelector('.modal-cancel')?.addEventListener('click', cancelHandler);
    modal.addEventListener('click', overlayHandler);
    window.closeConfirmModal = cleanup;
}

// Handler form expired orders pakai modal modern
document.addEventListener('DOMContentLoaded', function () {
    const clearForm = document.getElementById('clearExpiredOrdersForm');
    if (clearForm) {
        clearForm.addEventListener('submit', function(e){
            e.preventDefault();
            openConfirmModal({
                id: 'clearConfirmModal',
                onOk: function() {
                    fetch(clearForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        },
                        body: new FormData(clearForm)
                    })
                    .then(res => res.json().catch(()=>null))
                    .then(data => {
                        showToast('Pesanan kadaluarsa/expired berhasil dibersihkan!', '');
                        setTimeout(() => window.location.reload(), 1700);
                    })
                    .catch(() => {
                        showToast('Gagal membersihkan pesanan kadaluarsa.', 'error');
                    });
                },
                onCancel: function() {
                    // sudah di-handle di cancelHandler: balik ke halaman profil
                }
            });
        });
    }
});

// Google Maps Autocomplete event
document.addEventListener("DOMContentLoaded", function () {
    const pac = document.getElementById('full_address');
    if (pac) {
        pac.addEventListener('gmpx-placechange', (event) => {
            const place = event.detail;
            let city = '';
            let zip = '';
            if (place && place.addressComponents) {
                place.addressComponents.forEach((component) => {
                    if (component.types.includes('administrative_area_level_2')) city = component.longText;
                    if (component.types.includes('postal_code')) zip = component.longText;
                });
            }
            if (document.getElementById('city') && city) document.getElementById('city').value = city;
            if (document.getElementById('zip_code') && zip) document.getElementById('zip_code').value = zip;
        });
    }
});

function enableManualAddress() {
    let wrapper = document.getElementById('gmapsAutocompleteWrapper');
    if (!wrapper) return;
    if (!document.getElementById('manual_full_address')) {
        wrapper.innerHTML = `
            <input id="manual_full_address" name="full_address" type="text" required
                class="w-full px-3 py-2 border rounded focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200"
                placeholder="Tulis alamat lengkap secara manual..." />
        `;
        showToast('Mode input manual aktif. Pastikan alamat sudah benar.', 'warning');
    } else {
        wrapper.innerHTML = `
            <gmpx-place-autocomplete
                id="full_address"
                style="width:100%;"
                placeholder="Cari atau ketik alamat lengkap"
                hide-branding="true"
                required
            ></gmpx-place-autocomplete>
        `;
        showToast('Mode autocomplete Google Maps aktif.', '');
    }
}

function getMyLocation() {
    if (navigator.geolocation) {
        showToast('Mengambil lokasi Anda...', '');
        navigator.geolocation.getCurrentPosition(function(position) {
            var latlng = position.coords.latitude + "," + position.coords.longitude;
            fetch('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latlng + '&key=AIzaSyCTUfem9YaXy7FPguX6wa26V4lRuYOgF4w')
                .then(response => response.json())
                .then(data => {
                    if(data.results && data.results[0]) {
                        let addr = data.results[0].formatted_address;
                        let city = "";
                        let zip = "";
                        data.results[0].address_components.forEach(function(component) {
                            if(component.types.includes('administrative_area_level_2')) city = component.long_name;
                            if(component.types.includes('postal_code')) zip = component.long_name;
                        });

                        let manualInput = document.getElementById('manual_full_address');
                        let autocomplete = document.getElementById('full_address');
                        if(manualInput) manualInput.value = addr;
                        if(autocomplete) autocomplete.value = addr;

                        if(document.getElementById('city') && city) document.getElementById('city').value = city;
                        if(document.getElementById('zip_code') && zip) document.getElementById('zip_code').value = zip;

                        showToast('Alamat berhasil diisi dari lokasi Anda.', '');
                    } else {
                        showToast('Gagal mendapatkan alamat dari lokasi.', 'error');
                    }
                });
        }, function() {
            showToast('Tidak dapat mengambil lokasi Anda. Pastikan izin lokasi aktif.', 'error');
        });
    } else {
        showToast('Geolocation tidak didukung browser Anda.', 'error');
    }
}

// Toggle plain password
document.getElementById('togglePlainPasswordProfile')?.addEventListener('click', function(){
    var input = document.getElementById('plainPasswordProfile');
    var eye = document.getElementById('plainPasswordEyeProfile');
    if (input.type === "password") {
        input.type = "text";
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.964 9.964 0 012.042-3.368m3.087-2.472A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.986 9.986 0 01-4.293 5.507M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
    } else {
        input.type = "password";
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268-2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    }
});
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    if (input.type === "password") {
        input.type = "text";
        btn.querySelector('svg').classList.add('text-green-600');
    } else {
        input.type = "password";
        btn.querySelector('svg').classList.remove('text-green-600');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var editBtn = document.getElementById('editProfileBtn');
    var editForm = document.getElementById('editProfileForm');
    var cancelBtn = document.getElementById('cancelEditBtn');
    if(editBtn && editForm) {
        editBtn.addEventListener('click', function() {
            editForm.classList.remove('hidden');
            window.scrollTo({top: editForm.offsetTop-100, behavior:'smooth'});
        });
    }
    if(cancelBtn && editForm) {
        cancelBtn.addEventListener('click', function() {
            editForm.classList.add('hidden');
        });
    }
});

/* ----------- WAJIB ISI NOMOR TELEPON, CEGAH SEGALA LEAVE ---------- */
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('phone');
    var editForm = document.getElementById('editProfileRealForm');
    var warningTelepon = document.getElementById('warningTelepon');
    var overlay = document.getElementById('phoneRequiredOverlay');
    var okBtn = document.getElementById('modalPhoneOkBtn');

    // Fungsi cek dan enforce modal, hanya muncul jika user AKAN keluar/pindah halaman dan belum valid
    function mustFillPhone() {
        return !isValidIndoPhone(phoneInput ? phoneInput.value : '');
    }

    // Tombol "Isi Sekarang": reload halaman (agar user masuk ke halaman profile fresh)
    window.focusPhoneInput = function() {
        // Modal tetap hilang dan halaman di-reload
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
        window.location.reload();
    };

    // Real time warning di form edit
    if(editForm && phoneInput){
        editForm.addEventListener('input', function() {
            let phone = phoneInput.value;
            let valid = isValidIndoPhone(phone);
            warningTelepon && (warningTelepon.style.display = valid ? 'none' : '');
        });
        editForm.addEventListener('submit', function(e){
            let phone = phoneInput.value;
            if(!isValidIndoPhone(phone)){
                warningTelepon && (warningTelepon.style.display = '');
                e.preventDefault();
                phoneInput.focus();
                phoneInput.scrollIntoView({behavior:'smooth', block:'center'});
                overlay && overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                return false;
            }
        });
    }

    // Cegah keluar/pindah halaman jika belum isi telepon valid
    window.addEventListener("beforeunload", function(e) {
        if(mustFillPhone()){
            overlay && overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (okBtn) okBtn.focus();
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

    document.addEventListener('click', function(e){
        let t = e.target;
        while (t && t.tagName !== 'A' && t !== document.body) t = t.parentElement;
        if (t && t.tagName === 'A') {
            if (mustFillPhone()) {
                overlay && overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (okBtn) okBtn.focus();
                e.preventDefault();
                return false;
            }
        }
    }, true);

    (function(history){
        var pushState = history.pushState;
        history.pushState = function(){
            if(mustFillPhone()) {
                overlay && overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (okBtn) okBtn.focus();
                return false;
            }
            return pushState.apply(history, arguments);
        };
    })(window.history);

    document.addEventListener('keydown', function(e){
        if(overlay && overlay.classList.contains('active')){
            if(['Tab','Shift','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','F12'].indexOf(e.key) >= 0) return;
            if(document.activeElement === phoneInput) return;
            e.preventDefault();
            return false;
        }
    }, true);
});
</script>
<?php /**PATH C:\laragon\www\azka-garden\resources\views/User/profile/script.blade.php ENDPATH**/ ?>