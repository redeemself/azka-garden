@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    @php
        $sub = $items->sum(fn($i) => $i->price * $i->quantity);
        $disc =
            session('promo_type') === 'percent'
                ? $sub * (session('promo_discount') / 100)
                : session('promo_discount') ?? 0;

        $ship = $shippingMethods->firstWhere('id', $selectedShipId);
        $shipCost = $ship->cost ?? 0; // Pastikan 'cost' sesuai di model dan migrasi

        // Hitung pajak 10% dari subtotal setelah diskon
        $subtotalAfterDisc = max(0, $sub - $disc);
        $tax = $subtotalAfterDisc * 0.1;

        // Grand total sekarang termasuk pajak
        $grand = $subtotalAfterDisc + $shipCost + $tax;

        // Alamat user
        $user = auth()->user();
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        $primaryAddress = null;
        if ($hasAddress) {
            $primaryAddress = $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first();
        }

        // Lokasi toko (KSU - Toko Bunga Hendrik)
        $storeLocation = [
            'lat' => -6.4122794,
            'lng' => 106.829692,
            'address' => 'Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412',
            'plus_code' => 'HRQH+3VP',
        ];

        // Current timestamp and user
        $currentDateTime = '2025-07-31 09:47:39';
        $currentUser = 'DenuJanuari';
    @endphp

    <style>
        /* Location search styles */
        .location-panel {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .location-panel-header {
            padding: 0.75rem 1rem;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .location-panel-title {
            font-weight: 600;
            color: #374151;
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
            border: 1px solid #d1d5db;
            border-radius: 0.5rem 0 0 0.5rem;
            font-size: 1rem;
        }

        .location-search-input:focus {
            outline: none;
            border-color: #16a34a;
        }

        .location-search-btn {
            background: #16a34a;
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
            background: #14532d;
        }

        .location-search-results {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }

        .location-search-result {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }

        .location-search-result:last-child {
            border-bottom: none;
        }

        .location-search-result:hover {
            background-color: #f0fdf4;
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
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .location-action-btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .location-action-btn-primary {
            background-color: #f0fdf4;
            border-color: #16a34a;
            color: #16a34a;
        }

        .location-action-btn-primary:hover {
            background-color: #16a34a;
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

        .map-preview {
            height: 250px;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-top: 1rem;
            border: 1px solid #d1d5db;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .location-search {
                flex-direction: column;
            }

            .location-search-input {
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .location-search-btn {
                border-radius: 0.5rem;
            }

            .location-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="container mx-auto max-w-3xl px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        {{-- Jika keranjang kosong --}}
        @if ($items->isEmpty())
            <p class="text-center bg-yellow-50 border border-yellow-300 p-6 rounded-lg">
                Keranjang kosong. <a href="{{ route('products.index') }}" class="underline">Belanja sekarang</a>.
            </p>
        @else
            {{-- Tabel keranjang --}}
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-white border rounded-lg">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="p-3 text-left">Produk</th>
                            <th class="p-3 text-center">Harga</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-center">Subtotal</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $row)
                            <tr class="border-b last:border-0">
                                <td class="p-3">{{ $row->product->name ?? $row->name }}</td>
                                <td class="p-3 text-center">Rp{{ number_format($row->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('cart.update', $row->id) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" min="1" value="{{ $row->quantity }}"
                                            class="w-16 border rounded text-center">
                                        <button type="submit"
                                            class="ml-2 px-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Ubah</button>
                                    </form>
                                </td>
                                <td class="p-3 text-center">Rp{{ number_format($row->price * $row->quantity, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('cart.remove', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold">✕</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Alamat Pengiriman dan Jarak ke Toko --}}
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                <h2 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Alamat Pengiriman & Jarak
                </h2>

                @if ($primaryAddress)
                    <div class="bg-white border border-blue-100 p-3 rounded mb-3">
                        <div class="font-semibold">{{ $primaryAddress->label }} ({{ $primaryAddress->recipient }})</div>
                        <div>{{ $primaryAddress->full_address }}</div>
                        <div>{{ $primaryAddress->city }}, {{ $primaryAddress->zip_code }}</div>
                        <div>{{ $primaryAddress->phone_number }}</div>
                    </div>

                    <div class="text-sm text-gray-600 mb-3">
                        <strong>Toko:</strong> Azka Garden (Toko Bunga Hendrik) - Jalan Raya KSU, Tirtajaya, Sukmajaya,
                        Depok
                    </div>

                    {{-- Panel untuk pencarian alamat --}}
                    <div class="location-panel">
                        <div class="location-panel-header">
                            <h3 class="location-panel-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-map">
                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                </svg>
                                Cek Jarak & Ongkir
                            </h3>
                        </div>

                        <div class="location-panel-body">
                            <div class="location-search">
                                <input type="text" id="location-search-input" class="location-search-input"
                                    placeholder="Masukkan alamat atau tempat untuk cek jarak" />
                                <button type="button" id="location-search-btn" class="location-search-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    Cari
                                </button>
                            </div>

                            <div id="location-search-results" class="location-search-results" style="display:none;"></div>

                            <div class="location-actions">
                                <button type="button" id="get-current-location"
                                    class="location-action-btn location-action-btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-navigation">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                                    </svg>
                                    Gunakan Lokasi Saat Ini
                                </button>

                                <button type="button" id="show-store-location" class="location-action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    Lihat Lokasi Toko
                                </button>
                            </div>

                            <div id="distance-info"
                                class="mt-3 p-2 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <strong>Jarak ke toko:</strong> 7.2 km (waktu tempuh sekitar 25 menit via kendaraan)
                            </div>
                        </div>
                    </div>

                    {{-- Peta Lokasi --}}
                    <div id="map-container" class="map-preview mt-3"></div>

                    <div class="mt-2 text-xs text-gray-500">
                        <span class="block">Login: {{ $currentUser }}</span>
                        <span class="block">Waktu: {{ $currentDateTime }}</span>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 p-3 rounded text-yellow-800">
                        <p>Anda belum menambahkan alamat pengiriman. Harap tambahkan alamat pengiriman untuk melanjutkan
                            checkout.</p>
                        <a href="{{ route('user.address.create') }}"
                            class="text-blue-600 underline mt-2 inline-block">Tambah Alamat</a>
                    </div>
                @endif
            </div>

            {{-- Pilih metode pengiriman --}}
            <form action="{{ route('cart.selectShipping') }}" method="POST" class="mb-6">
                @csrf
                <label for="shipping_method_id" class="block mb-2 font-semibold">Metode Pengiriman:</label>
                <select name="shipping_method_id" id="shipping_method_id" class="w-full border rounded p-2">
                    @foreach ($shippingMethods as $method)
                        <option value="{{ $method->id }}" {{ $method->id == $selectedShipId ? 'selected' : '' }}>
                            {{ $method->name }} - Rp{{ number_format($method->cost, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Simpan
                    Pengiriman</button>
            </form>

            {{-- Ringkasan harga --}}
            <div class="bg-gray-50 border p-4 rounded mb-8">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($sub, 0, ',', '.') }}</span>
                </div>
                @if ($disc > 0)
                    <div class="flex justify-between text-green-700">
                        <span>Diskon</span>
                        <span>&minus; Rp{{ number_format($disc, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Ongkos Kirim</span>
                    <span>Rp{{ number_format($shipCost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-green-800">
                    <span>Pajak (10%)</span>
                    <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total Bayar</span>
                    <span>Rp{{ number_format($grand, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100 transition">←
                    Lanjut Belanja</a>
                <a href="{{ route('checkout.index') }}"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Checkout</a>
            </div>
        @endif
    </div>

    @if ($primaryAddress)
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
                const showStoreLocationBtn = document.getElementById('show-store-location');
                const distanceInfo = document.getElementById('distance-info');
                const mapContainer = document.getElementById('map-container');

                // Store location - Toko Bunga Hendrik di KSU
                const storeLocation = {
                    lat: {{ $storeLocation['lat'] }},
                    lng: {{ $storeLocation['lng'] }},
                    name: 'Azka Garden (Toko Bunga Hendrik)',
                    address: '{{ $storeLocation['address'] }}'
                };

                // Customer saved address location
                let customerLocation = {
                    lat: {{ $primaryAddress->latitude ?? 'null' }},
                    lng: {{ $primaryAddress->longitude ?? 'null' }},
                    name: 'Alamat Pengiriman',
                    address: '{{ $primaryAddress->full_address ?? '' }}, {{ $primaryAddress->city ?? '' }}, {{ $primaryAddress->zip_code ?? '' }}'
                };

                // Current selected location (initialized to customer address if coordinates exist)
                let currentLocation = customerLocation.lat && customerLocation.lng ? customerLocation : null;

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
                                    // Set as current location
                                    currentLocation = {
                                        lat: parseFloat(result.lat),
                                        lng: parseFloat(result.lon),
                                        name: 'Lokasi Pencarian',
                                        address: result.display_name
                                    };

                                    // Hide results
                                    searchResults.style.display = 'none';
                                    searchInput.value = result.display_name;

                                    // Show on map
                                    showMap();

                                    // Calculate distance
                                    calculateDistance(currentLocation, storeLocation);
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
                                const lng = position.coords.longitude;

                                // Set as current location
                                currentLocation = {
                                    lat: lat,
                                    lng: lng,
                                    name: 'Lokasi Anda Saat Ini',
                                    address: 'Lokasi saat ini'
                                };

                                // Reverse geocode to get address details
                                fetch(
                                        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
                                    )
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.display_name) {
                                            currentLocation.address = data.display_name;
                                            searchInput.value = data.display_name;
                                        }

                                        // Show on map
                                        showMap();

                                        // Calculate distance
                                        calculateDistance(currentLocation, storeLocation);

                                        // Reset button state
                                        getCurrentLocationBtn.innerHTML = originalText;
                                        getCurrentLocationBtn.disabled = false;
                                        if (typeof feather !== 'undefined') feather.replace();
                                    })
                                    .catch(error => {
                                        console.error('Error getting address details:', error);

                                        // Show on map anyway
                                        showMap();

                                        // Calculate distance
                                        calculateDistance(currentLocation, storeLocation);

                                        // Reset button state
                                        getCurrentLocationBtn.innerHTML = originalText;
                                        getCurrentLocationBtn.disabled = false;
                                        if (typeof feather !== 'undefined') feather.replace();
                                    });
                            },
                            function(error) {
                                // Reset button state
                                getCurrentLocationBtn.innerHTML = originalText;
                                getCurrentLocationBtn.disabled = false;
                                if (typeof feather !== 'undefined') feather.replace();

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

                // Show store location
                showStoreLocationBtn.addEventListener('click', function() {
                    // Set store as current location for map center
                    showMap(true);
                });

                // Calculate distance between points using Haversine formula (pure JS, no API needed)
                function haversineDistance(loc1, loc2) {
                    const toRad = (value) => value * Math.PI / 180;
                    const R = 6371; // Earth radius in km

                    const dLat = toRad(loc2.lat - loc1.lat);
                    const dLon = toRad(loc2.lng - loc1.lng);
                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(toRad(loc1.lat)) * Math.cos(toRad(loc2.lat)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    const distance = R * c;

                    return {
                        value: distance,
                        text: distance.toFixed(1) + ' km'
                    };
                }

                // Calculate and display distance
                function calculateDistance(origin, destination) {
                    if (!origin || !destination) return;

                    try {
                        // First try to use Nominatim for routing distance if available
                        fetch(`https://nominatim.openstreetmap.org/status`)
                            .then(response => {
                                if (!response.ok) throw new Error('Nominatim service unavailable');

                                // Calculate with Haversine formula instead
                                const distance = haversineDistance(origin, destination);

                                // Estimate driving time (very rough - 30km/h average)
                                const minutes = Math.round(distance.value * 2);

                                // Update distance info
                                if (distanceInfo) {
                                    distanceInfo.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <strong>Jarak ke toko:</strong> ${distance.text} (waktu tempuh sekitar ${minutes} menit via kendaraan)
                                `;
                                }

                                // Determine shipping cost based on distance
                                let shippingCost = 10000; // Default for < 5km
                                if (distance.value > 10) {
                                    shippingCost = 20000;
                                } else if (distance.value > 5) {
                                    shippingCost = 15000;
                                }

                                // Update the note with shipping cost
                                distanceInfo.innerHTML +=
                                    `<div class="mt-1 font-medium">Estimasi ongkir: Rp ${new Intl.NumberFormat('id-ID').format(shippingCost)}</div>`;
                            })
                            .catch(error => {
                                console.warn('Distance calculation API error:', error);

                                // Fallback to Haversine if API fails
                                const distance = haversineDistance(origin, destination);
                                const minutes = Math.round(distance.value * 2);

                                if (distanceInfo) {
                                    distanceInfo.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <strong>Jarak ke toko:</strong> ${distance.text} (waktu tempuh sekitar ${minutes} menit via kendaraan)
                                `;
                                }

                                // Determine shipping cost based on distance
                                let shippingCost = 10000; // Default for < 5km
                                if (distance.value > 10) {
                                    shippingCost = 20000;
                                } else if (distance.value > 5) {
                                    shippingCost = 15000;
                                }

                                // Update the note with shipping cost
                                distanceInfo.innerHTML +=
                                    `<div class="mt-1 font-medium">Estimasi ongkir: Rp ${new Intl.NumberFormat('id-ID').format(shippingCost)}</div>`;
                            });
                    } catch (error) {
                        console.error('Error calculating distance:', error);
                    }
                }

                // Show map with OpenStreetMap (no API key needed)
                function showMap(centerOnStore = false) {
                    if (!mapContainer) return;

                    try {
                        // Clear previous map
                        mapContainer.innerHTML = '';

                        // Create map iframe with OpenStreetMap
                        let targetLat, targetLng, zoom;

                        if (centerOnStore) {
                            // Center on store
                            targetLat = storeLocation.lat;
                            targetLng = storeLocation.lng;
                            zoom = 15;
                        } else if (currentLocation) {
                            // Center between store and current location
                            targetLat = (storeLocation.lat + currentLocation.lat) / 2;
                            targetLng = (storeLocation.lng + currentLocation.lng) / 2;
                            zoom = 13;
                        } else {
                            // Default to store if no current location
                            targetLat = storeLocation.lat;
                            targetLng = storeLocation.lng;
                            zoom = 15;
                        }

                        // Create an OpenStreetMap iframe (no API key needed)
                        const iframe = document.createElement('iframe');
                        iframe.width = '100%';
                        iframe.height = '100%';
                        iframe.frameBorder = '0';
                        iframe.scrolling = 'no';
                        iframe.marginHeight = '0';
                        iframe.marginWidth = '0';

                        // Create OSM URL with markers
                        let osmUrl =
                            `https://www.openstreetmap.org/export/embed.html?bbox=${targetLng-0.02}%2C${targetLat-0.02}%2C${targetLng+0.02}%2C${targetLat+0.02}&amp;layer=mapnik`;

                        // Add markers if possible
                        if (storeLocation) {
                            osmUrl += `&amp;marker=${storeLocation.lat}%2C${storeLocation.lng}`;
                        }

                        if (currentLocation && currentLocation !== storeLocation) {
                            osmUrl += `&amp;marker=${currentLocation.lat}%2C${currentLocation.lng}`;
                        }

                        iframe.src = osmUrl;
                        mapContainer.appendChild(iframe);

                        // Add link to larger map below iframe
                        const linkContainer = document.createElement('div');
                        linkContainer.className = 'text-center text-sm mt-1';
                        linkContainer.innerHTML =
                            `<a href="https://www.openstreetmap.org/?mlat=${targetLat}&mlon=${targetLng}#map=${zoom}/
                        ${targetLat}/${targetLng}" target="_blank" class="text-blue-600 hover:underline">Lihat Peta Lebih Besar</a>`;
                        mapContainer.appendChild(linkContainer);

                        // Calculate distance if we have both points
                        if (currentLocation && storeLocation) {
                            calculateDistance(currentLocation, storeLocation);
                        }
                    } catch (error) {
                        console.error('Error showing map:', error);
                        mapContainer.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                        <div class="text-center p-4">
                            <p>Tidak dapat menampilkan peta</p>
                            <p class="text-sm">Koordinat Toko: ${storeLocation.lat}, ${storeLocation.lng}</p>
                        </div>
                    </div>`;
                    }
                }

                // Initialize map with customer address if coordinates exist
                if (customerLocation.lat && customerLocation.lng) {
                    currentLocation = customerLocation;
                    searchInput.value = customerLocation.address;
                    showMap();
                    calculateDistance(customerLocation, storeLocation);
                } else {
                    // Just show store location if no customer coordinates
                    showMap(true);
                }
            });
        </script>
    @endif
@endsection
