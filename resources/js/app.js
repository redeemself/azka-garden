import '../css/app.css';
import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import flatpickr from 'flatpickr';

window.Alpine = Alpine;
Alpine.start();

window.addEventListener('DOMContentLoaded', () => {
  // === GLOBAL LOADER ===
  const loader = document.getElementById('global-loader');
  let loaderTimeout;
  let loaderHidden = false;

  function hideLoader() {
    if (!loader || loaderHidden) return;
    loaderHidden = true;
    loader.style.transition = 'opacity 0.5s';
    loader.style.opacity = '0';
    setTimeout(() => loader.style.display = 'none', 500);
  }
  function showLoader() {
    if (!loader) return;
    loader.style.display = 'flex';
    loader.style.opacity = '1';
    loaderHidden = false;
  }

  if (loader) {
    showLoader();
    loaderTimeout = setTimeout(hideLoader, 10000);
  }

  const heroImages = [
    '/images/hero-1.jpg',
    '/images/hero-2.jpg',
    '/images/hero-3.jpg',
    '/images/hero-4.jpg',
    '/images/hero-5.jpg',
    '/images/hero-6.jpg',
    '/images/hero-7.jpg',
  ];
  let loadedImg = 0;
  function onImageLoaded() {
    loadedImg++;
    if (loadedImg >= heroImages.length) {
      hideLoader();
      if (loaderTimeout) clearTimeout(loaderTimeout);
    }
  }
  heroImages.forEach(src => {
    const img = new window.Image();
    img.onload = onImageLoaded;
    img.onerror = onImageLoaded;
    img.src = src;
  });
  window.addEventListener('load', hideLoader);

  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
      showLoader();
    });
  });
  document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', e => {
      if (
        link.hostname === window.location.hostname &&
        (!link.target || link.target === '_self')
      ) {
        showLoader();
      }
    });
  });

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Chart.js (if exist)
  const ctx = document.getElementById('salesChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar'],
        datasets: [{ label: 'Sales', data: [30, 50, 40], borderRadius: 6 }]
      }
    });
  }

  // Flatpickr
  flatpickr('.datepicker', { dateFormat: 'Y-m-d' });

  // === Language navigator for navbar (PERBAIKAN PASTI BERGANTI SAAT PANAH DIKLIK) ===
  // List bahasa yang tersedia (label: tampil di UI, code: bisa diarahkan ke route/cookie/opsional)
  const languages = [
    { code: '/id', label: 'Indonesia' },
    { code: '/en', label: 'English' },
    { code: '/jv', label: 'Jawa' },
    { code: '/su', label: 'Sunda' },
    // Tambahkan jika perlu
  ];
  let currentIndex = 0;
  const langDisplay = document.getElementById('lang-display');
  const prevBtn = document.getElementById('lang-prev');
  const nextBtn = document.getElementById('lang-next');

  // PERBAIKAN: Pastikan updateLanguage selalu refresh isi label
  function updateLanguage() {
    if (langDisplay) {
      langDisplay.textContent = languages[currentIndex].label;
      // Optional: tambahkan class aktif
      langDisplay.classList.remove('font-bold', 'text-green-400');
      langDisplay.classList.add('font-bold', 'text-green-400');
    }
  }

  if (langDisplay && prevBtn && nextBtn) {
    prevBtn.addEventListener('click', function(e) {
      e.preventDefault();
      currentIndex = (currentIndex - 1 + languages.length) % languages.length;
      updateLanguage();
    });
    nextBtn.addEventListener('click', function(e) {
      e.preventDefault();
      currentIndex = (currentIndex + 1) % languages.length;
      updateLanguage();
    });
    langDisplay.addEventListener('click', function() {
      // Jika ingin redirect ke halaman bahasa, aktifkan baris ini
      // window.location.href = languages[currentIndex].code;
      // Jika ingin AJAX ke backend untuk set session/cookie, bisa fetch di sini
    });
    // Inisialisasi pertama
    updateLanguage();
  }

  // Language footer carousel (masih ada, bisa dipisah jika navigator di footer)
  const footerLanguages = [
    { code: '/id', label: 'Indonesia' }, { code: '/en', label: 'English' }, { code: '/ar', label: 'العربية' },
    { code: '/zh', label: '中文' }, { code: '/fr', label: 'Français' }, { code: '/de', label: 'Deutsch' },
    { code: '/hi', label: 'हिन्दी' }, { code: '/ja', label: '日本語' }, { code: '/ko', label: '한국어' },
    { code: '/pt', label: 'Português' }, { code: '/ru', label: 'Русский' }, { code: '/es', label: 'Español' },
    { code: '/tr', label: 'Türkçe' }, { code: '/vi', label: 'Tiếng Việt' }, { code: '/th', label: 'ไทย' },
    { code: '/bn', label: 'বাংলা' }, { code: '/ms', label: 'Bahasa Melayu' }, { code: '/it', label: 'Italiano' },
    { code: '/nl', label: 'Nederlands' }, { code: '/pl', label: 'Polski' }, { code: '/sv', label: 'Svenska' },
    { code: '/uk', label: 'Українська' }, { code: '/el', label: 'Ελληνικά' }, { code: '/he', label: 'עברית' },
    { code: '/fa', label: 'فارسی' },
  ];
  let footerCurrentIndex = 0;
  const footerDisplay = document.getElementById('footer-lang-display');
  const footerPrevBtn = document.getElementById('footer-lang-prev');
  const footerNextBtn = document.getElementById('footer-lang-next');
  function updateFooterDisplay() {
    if (footerDisplay) {
      footerDisplay.textContent = footerLanguages[footerCurrentIndex].label;
      footerDisplay.classList.remove('font-bold', 'text-green-400');
      footerDisplay.classList.add('font-bold', 'text-green-400');
    }
  }
  if (footerDisplay && footerPrevBtn && footerNextBtn) {
    footerPrevBtn.addEventListener('click', () => {
      footerCurrentIndex = (footerCurrentIndex - 1 + footerLanguages.length) % footerLanguages.length;
      updateFooterDisplay();
    });
    footerNextBtn.addEventListener('click', () => {
      footerCurrentIndex = (footerCurrentIndex + 1) % footerLanguages.length;
      updateFooterDisplay();
    });
    footerDisplay.addEventListener('click', () => {
      window.location.href = footerLanguages[footerCurrentIndex].code;
    });
    updateFooterDisplay();
  }
});

// === ALPINE.JS GLOBAL SLIDER SUPER SMOOTH ===
window.sliderBg = function () {
  return {
    heroImages: [
      '/images/hero-1.jpg',
      '/images/hero-2.jpg',
      '/images/hero-3.jpg',
      '/images/hero-4.jpg',
      '/images/hero-5.jpg',
      '/images/hero-6.jpg',
      '/images/hero-7.jpg',
    ],
    currentBg: 0,
    nextBg: 1,
    transitioning: false,
    interval: null,
    transitionDuration: 800,

    steps: [
      { title: 'Masuk atau Daftar',  desc: 'Login dengan akun Anda atau buat baru untuk mulai berbelanja.' },
      { title: 'Jelajahi Produk',     desc: 'Pilih dari katalog tanaman dan layanan lanskap kami.' },
      { title: 'Tambah ke Keranjang', desc: 'Tentukan jumlah, lalu masukkan ke keranjang belanja.' },
      { title: 'Checkout & Bayar',    desc: 'Selesaikan pembayaran menggunakan metode pilihan Anda.' },
      { title: 'Lacak Pengiriman',    desc: 'Cek status dan estimasi tiba di dashboard akun Anda.' }
    ],
    current: 0,

    startInterval() {
      this.stopInterval();
      this.interval = setInterval(() => {
        this.nextBackground();
      }, 10000);
    },
    stopInterval() {
      if (this.interval) clearInterval(this.interval);
      this.interval = null;
    },
    nextBackground() {
      let nextIndex = (this.currentBg + 1) % this.heroImages.length;
      this.setBg(nextIndex);
    },
    prevBackground() {
      let prevIndex = (this.currentBg - 1 + this.heroImages.length) % this.heroImages.length;
      this.setBg(prevIndex);
    },
    setBg(idx) {
      if (idx === this.currentBg || this.transitioning) return;
      this.nextBg = idx;
      this.transitioning = true;
    },
    finishTransition() {
      this.currentBg = this.nextBg;
      this.transitioning = false;
      this.startInterval();
    },
    nextBgManual() {
      this.nextBackground();
    },
    nextStep() {
      if (this.current < this.steps.length - 1) {
        this.current++;
      } else if (typeof window.routeProductsIndex !== 'undefined') {
        window.location.href = window.routeProductsIndex;
      }
    },
    prevStep() {
      if (this.current > 0) this.current--;
    },
    init() {
      this.startInterval();
    }
  }
}
