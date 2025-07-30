@extends('layouts.app')

@section('title', 'Tambah Alamat Baru')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <style>
        :root {
            --primary: #166534;
            --primary-light: #16a34a;
            --primary-dark: #14532d;
            --primary-bg: #f0fdf4;
            --primary-bg-hover: #dcfce7;
            --accent: #4ade80;
            --white: #ffffff;
            --black: #111827;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --error: #b91c1c;
            --error-bg: #fee2e2;
            --warning: #ea580c;
            --warning-bg: #ffedd5;
            --success: #16a34a;
            --success-bg: #dcfce7;
            --info: #0284c7;
            --info-bg: #e0f2fe;
        }

        .address-container {
            max-width: 800px;
            margin: 2rem auto 3rem;
            padding: 0 1rem;
        }

        .address-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .address-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .address-header p {
            color: var(--gray-600);
            font-size: 1.1rem;
        }

        .address-form-card {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .address-form-header {
            background: var(--primary-bg);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .address-form-title {
            color: var(--primary-dark);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .address-form-body {
            padding: 1.5rem;
        }

        .address-form-footer {
            padding: 1.5rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.1);
        }

        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--error);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary);
        }

        .form-check-label {
            font-weight: 500;
            color: var(--gray-700);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline {
            background: var(--white);
            color: var(--gray-600);
            border-color: var(--gray-300);
        }

        .btn-outline:hover {
            background: var(--gray-100);
            color: var(--gray-800);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .location-panel {
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .location-panel-header {
            padding: 0.75rem 1rem;
            background-color: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .location-panel-title {
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            font-size: 1rem;
        }

        .location-panel-body {
            padding: 1rem;
        }

        .location-search {
            display: flex;
            margin-bottom: 1rem;
        }

        .location-search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem 0 0 0.5rem;
            font-size: 1rem;
        }

        .location-search-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .location-search-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .location-search-btn:hover {
            background: var(--primary-dark);
        }

        .location-search-results {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
        }

        .location-search-result {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--gray-200);
            transition: background-color 0.2s ease;
        }

        .location-search-result:last-child {
            border-bottom: none;
        }

        .location-search-result:hover {
            background-color: var(--primary-bg);
        }

        .location-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .location-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            border: 1px solid var(--gray-300);
            background-color: var(--white);
            transition: all 0.2s ease;
        }

        .location-action-btn:hover {
            background-color: var(--gray-100);
            border-color: var(--gray-400);
        }

        .location-action-btn-primary {
            background-color: var(--primary-bg);
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .location-action-btn-primary:hover {
            background-color: var(--primary-light);
            color: white;
        }

        .spinner {
            border: 2px solid rgba(0, 0, 0, 0.1);
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border-left-color: currentColor;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: text-bottom;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .address-header h1 {
                font-size: 1.75rem;
            }

            .address-container {
                margin-top: 1rem;
                padding: 0 0.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .address-form-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <div class="address-container">
        <div class="address-header">
            <h1>Tambah Alamat Baru</h1>
            <p>Isi data alamat pengiriman dengan lengkap</p>
        </div>

        <div class="address-form-card">
            <div class="address-form-header">
                <h2 class="address-form-title">
                    <i data-feather="map-pin"></i>
                    Formulir Alamat
                </h2>
            </div>

            <div class="address-form-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.address.store') }}" method="POST" id="address-form">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="label" class="form-label">Label Alamat</label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror" id="label"
                                name="label" value="{{ old('label') }}" placeholder="Rumah, Kantor, dll">
                            <small class="form-text">Contoh: Rumah, Kantor, Apartemen</small>
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="recipient" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control @error('recipient') is-invalid @enderror"
                                id="recipient" name="recipient" value="{{ old('recipient') }}"
                                placeholder="Nama lengkap penerima">
                            @error('recipient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                            id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="Nomor telepon aktif">
                        <small class="form-text">Contoh: 081234567890</small>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="full_address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control @error('full_address') is-invalid @enderror" id="full_address" name="full_address"
                            rows="3" placeholder="Alamat lengkap (jalan, nomor rumah, RT/RW, dll)">{{ old('full_address') }}</textarea>
                        @error('full_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">Kota/Kabupaten</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                name="city" value="{{ old('city') }}" placeholder="Nama kota/kabupaten">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="zip_code" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                id="zip_code" name="zip_code" value="{{ old('zip_code') }}" placeholder="Kode pos">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi di Peta</label>

                        <div class="location-panel">
                            <div class="location-panel-header">
                                <h3 class="location-panel-title">
                                    <i data-feather="map"></i>
                                    Pencarian Alamat
                                </h3>
                            </div>

                            <div class="location-panel-body">
                                <div class="location-search">
                                    <input type="text" id="location-search-input" class="location-search-input"
                                        placeholder="Masukkan alamat atau tempat" />
                                    <button type="button" id="location-search-btn" class="location-search-btn">
                                        <i data-feather="search"></i>
                                        Cari
                                    </button>
                                </div>

                                <div id="location-search-results" class="location-search-results" style="display:none;">
                                </div>

                                <div class="location-actions">
                                    <button type="button" id="get-current-location"
                                        class="location-action-btn location-action-btn-primary">
                                        <i data-feather="navigation"></i>
                                        Lokasi Saat Ini
                                    </button>

                                    <button type="button" id="reset-location" class="location-action-btn">
                                        <i data-feather="refresh-cw"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <small class="form-text">Gunakan pencarian atau isi koordinat secara manual untuk menandai lokasi
                            alamat.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                id="latitude" name="latitude" value="{{ old('latitude') }}"
                                placeholder="Koordinat latitude">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                id="longitude" name="longitude" value="{{ old('longitude') }}"
                                placeholder="Koordinat longitude">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary"
                            value="1" {{ old('is_primary') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_primary">Jadikan sebagai alamat utama</label>
                    </div>

                    <div class="address-form-footer">
                        <a href="{{ route('user.address.index') }}" class="btn btn-outline">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            } else {
                // Load Feather Icons if not available
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js';
                script.onload = function() {
                    feather.replace();
                };
                document.head.appendChild(script);
            }

            // DOM elements
            const searchInput = document.getElementById('location-search-input');
            const searchBtn = document.getElementById('location-search-btn');
            const searchResults = document.getElementById('location-search-results');
            const getCurrentLocationBtn = document.getElementById('get-current-location');
            const resetLocationBtn = document.getElementById('reset-location');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const addressInput = document.getElementById('full_address');
            const cityInput = document.getElementById('city');
            const zipCodeInput = document.getElementById('zip_code');

            // Handle location search
            searchBtn.addEventListener('click', function() {
                const query = searchInput.value.trim();
                if (!query) return;

                // Show loading indicator
                searchResults.style.display = 'block';
                searchResults.innerHTML =
                    '<div style="padding: 10px; text-align: center;"><span class="spinner"></span> Mencari lokasi...</div>';

                // Use Nominatim API (OpenStreetMap) for geocoding - no API key needed
                fetch(
                        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=5&countrycodes=id`
                        )
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';

                        if (data.length === 0) {
                            searchResults.innerHTML =
                                '<div style="padding: 10px; text-align: center;">Tidak ada hasil ditemukan</div>';
                            return;
                        }

                        // Display results
                        data.forEach(result => {
                            const resultItem = document.createElement('div');
                            resultItem.className = 'location-search-result';
                            resultItem.textContent = result.display_name;

                            // Set location when clicked
                            resultItem.addEventListener('click', function() {
                                // Set coordinates
                                latitudeInput.value = result.lat;
                                longitudeInput.value = result.lon;

                                // Set address info if empty
                                if (!addressInput.value.trim()) {
                                    addressInput.value = result.display_name;
                                }

                                // Extract city and zipcode from address components if available
                                if (result.address) {
                                    if (result.address.city && !cityInput.value
                                        .trim()) {
                                        cityInput.value = result.address.city;
                                    } else if (result.address.town && !cityInput.value
                                        .trim()) {
                                        cityInput.value = result.address.town;
                                    } else if (result.address.county && !cityInput.value
                                        .trim()) {
                                        cityInput.value = result.address.county;
                                    }

                                    if (result.address.postcode && !zipCodeInput.value
                                        .trim()) {
                                        zipCodeInput.value = result.address.postcode;
                                    }
                                }

                                // Hide results
                                searchResults.style.display = 'none';
                                searchInput.value = result.display_name;
                            });

                            searchResults.appendChild(resultItem);
                        });
                    })
                    .catch(error => {
                        console.error('Error searching for location:', error);
                        searchResults.innerHTML =
                            '<div style="padding: 10px; text-align: center;">Error mencari lokasi. Silakan coba lagi.</div>';
                    });
            });

            // Handle search on Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchBtn.click();
                }
            });

            // Get current location
            getCurrentLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="spinner"></span> Mencari...';
                    this.disabled = true;

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Get coordinates
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            // Set coordinates in form
                            latitudeInput.value = lat;
                            longitudeInput.value = lon;

                            // Reverse geocode to get address details
                            fetch(
                                    `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`
                                    )
                                .then(response => response.json())
                                .then(data => {
                                    // Fill address fields if empty
                                    if (!addressInput.value.trim()) {
                                        addressInput.value = data.display_name;
                                    }

                                    // Fill city and zip if available
                                    if (data.address) {
                                        if (data.address.city && !cityInput.value.trim()) {
                                            cityInput.value = data.address.city;
                                        } else if (data.address.town && !cityInput.value.trim()) {
                                            cityInput.value = data.address.town;
                                        } else if (data.address.county && !cityInput.value.trim()) {
                                            cityInput.value = data.address.county;
                                        }

                                        if (data.address.postcode && !zipCodeInput.value.trim()) {
                                            zipCodeInput.value = data.address.postcode;
                                        }
                                    }

                                    // Reset button state
                                    getCurrentLocationBtn.innerHTML = originalText;
                                    getCurrentLocationBtn.disabled = false;
                                    feather.replace();
                                })
                                .catch(error => {
                                    console.error('Error getting address details:', error);

                                    // Reset button state
                                    getCurrentLocationBtn.innerHTML = originalText;
                                    getCurrentLocationBtn.disabled = false;
                                    feather.replace();

                                    // Show coordinates anyway
                                    alert(
                                        'Koordinat lokasi Anda telah disimpan, tetapi detail alamat tidak dapat ditemukan.'
                                        );
                                });
                        },
                        function(error) {
                            // Reset button state
                            getCurrentLocationBtn.innerHTML = originalText;
                            getCurrentLocationBtn.disabled = false;
                            feather.replace();

                            // Show error message
                            let errorMsg = "Tidak dapat mengakses lokasi Anda.";
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg =
                                        "Akses lokasi ditolak. Pastikan Anda mengizinkan akses lokasi.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg = "Informasi lokasi tidak tersedia.";
                                    break;
                                case error.TIMEOUT:
                                    errorMsg = "Permintaan lokasi habis waktu.";
                                    break;
                            }
                            alert(errorMsg);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    alert("Browser Anda tidak mendukung geolokasi.");
                }
            });

            // Reset location
            resetLocationBtn.addEventListener('click', function() {
                // Clear coordinates
                latitudeInput.value = '';
                longitudeInput.value = '';

                // Clear search
                searchInput.value = '';
                searchResults.style.display = 'none';
            });

            // Form validation before submit
            document.getElementById('address-form').addEventListener('submit', function(e) {
                let valid = true;

                // Check required fields
                const requiredFields = ['label', 'recipient', 'phone_number', 'full_address', 'city',
                    'zip_code'
                ];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        valid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Silakan lengkapi semua field yang diperlukan');
                    return false;
                }

                return true;
            });
        });
    </script>
@endsection
