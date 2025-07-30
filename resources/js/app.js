/**
 * Main JavaScript application file
 *
 * @updated 2025-07-30 05:58:57 by mulyadafa
 * @fixed Completely removed all module imports to prevent resolution errors
 */

// CSS can be imported with a relative path, this is fine
import '../css/app.css';

// Initialize the application when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    console.log('App.js initialized - DOM fully loaded');
    console.log('Libraries availability check:', {
        lodash: typeof window._ !== 'undefined',
        axios: typeof window.axios !== 'undefined',
        chart: typeof window.Chart !== 'undefined',
        flatpickr: typeof window.flatpickr !== 'undefined',
        alpine: typeof window.Alpine !== 'undefined',
    });

    // Setup CSRF token for AJAX requests
    if (window.axios) {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }
    }

    // Register global event handlers
    setupGlobalEventHandlers();

    // Init UI components
    initializeUI();
});

// Setup global event handlers
function setupGlobalEventHandlers() {
    // === GLOBAL LOADER ===
    const loader = document.getElementById('global-loader');
    let loaderTimeout;
    let loaderHidden = false;

    function hideLoader() {
        if (!loader || loaderHidden) return;
        loaderHidden = true;
        loader.style.transition = 'opacity 0.5s';
        loader.style.opacity = '0';
        setTimeout(() => (loader.style.display = 'none'), 500);
    }

    function showLoader() {
        if (!loader) return;
        loader.style.display = 'flex';
        loader.style.opacity = '1';
        loaderHidden = false;
    }

    // Make loader functions globally available
    window.showLoader = showLoader;
    window.hideLoader = hideLoader;

    if (loader) {
        showLoader();
        loaderTimeout = setTimeout(hideLoader, 10000);
    }

    // Load hero images
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

    heroImages.forEach((src) => {
        const img = new Image();
        img.onload = onImageLoaded;
        img.onerror = onImageLoaded;
        img.src = src;
    });

    window.addEventListener('load', hideLoader);

    // Show loader for navigation
    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            showLoader();
        });
    });

    document.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', (e) => {
            if (
                link.hostname === window.location.hostname &&
                (!link.target || link.target === '_self') &&
                !link.getAttribute('href').startsWith('#')
            ) {
                showLoader();
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
}

// Initialize UI components
function initializeUI() {
    // Init Chart.js - use window.Chart instead of imported Chart
    const ctx = document.getElementById('salesChart');
    if (ctx && window.Chart) {
        new window.Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar'],
                datasets: [{ label: 'Sales', data: [30, 50, 40], borderRadius: 6 }],
            },
        });
    }

    // Init Flatpickr - use window.flatpickr instead of imported flatpickr
    if (window.flatpickr) {
        window.flatpickr('.datepicker', { dateFormat: 'Y-m-d' });
    }

    // Initialize language selectors
    initLanguageSelectors();
}

// Language selectors functionality
function initLanguageSelectors() {
    // Navbar language selector
    const languages = [
        { code: '/id', label: 'Indonesia' },
        { code: '/en', label: 'English' },
        { code: '/jv', label: 'Jawa' },
        { code: '/su', label: 'Sunda' },
    ];

    let currentIndex = 0;
    const langDisplay = document.getElementById('lang-display');
    const prevBtn = document.getElementById('lang-prev');
    const nextBtn = document.getElementById('lang-next');

    function updateLanguage() {
        if (langDisplay) {
            langDisplay.textContent = languages[currentIndex].label;
            langDisplay.classList.remove('font-bold', 'text-green-400');
            langDisplay.classList.add('font-bold', 'text-green-400');
        }
    }

    if (langDisplay && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + languages.length) % languages.length;
            updateLanguage();
        });

        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % languages.length;
            updateLanguage();
        });

        langDisplay.addEventListener('click', function () {
            // Optional: window.location.href = languages[currentIndex].code;
        });

        updateLanguage();
    }

    // Footer language selector
    const footerLanguages = [
        { code: '/id', label: 'Indonesia' },
        { code: '/en', label: 'English' },
        { code: '/ar', label: 'العربية' },
        { code: '/zh', label: '中文' },
        { code: '/fr', label: 'Français' },
        { code: '/de', label: 'Deutsch' },
        // More languages...
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
            footerCurrentIndex =
                (footerCurrentIndex - 1 + footerLanguages.length) % footerLanguages.length;
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
}

// Define Alpine.js components
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
            {
                title: 'Masuk atau Daftar',
                desc: 'Login dengan akun Anda atau buat baru untuk mulai berbelanja.',
            },
            {
                title: 'Jelajahi Produk',
                desc: 'Pilih dari katalog tanaman dan layanan lanskap kami.',
            },
            {
                title: 'Tambah ke Keranjang',
                desc: 'Tentukan jumlah, lalu masukkan ke keranjang belanja.',
            },
            {
                title: 'Checkout & Bayar',
                desc: 'Selesaikan pembayaran menggunakan metode pilihan Anda.',
            },
            {
                title: 'Lacak Pengiriman',
                desc: 'Cek status dan estimasi tiba di dashboard akun Anda.',
            },
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
        },
    };
};

// Last update: 2025-07-30 05:58:57 by mulyadafa
