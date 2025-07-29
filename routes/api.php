<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| Updated: 2025-07-29 13:29:10 UTC by mulyadafa
| Fixed protected visibility error and added proper shipping cost handling
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function () {
        return Auth::user();
    });

    /*
    |--------------------------------------------------------------------------
    | Shipping Cost API Endpoints
    |--------------------------------------------------------------------------
    |
    | API endpoints untuk mendapatkan ongkos kirim sesuai database shippings.sql
    | Updated shipping costs: JNT = 14000 (bukan 25000)
    |
    */

    // Get shipping cost by method
    Route::get('/shipping-cost/{method}', function($method) {
        // Shipping costs sesuai database shippings.sql (Updated: 2025-07-29)
        $costs = [
            'JNT' => 14000.00,          // ID 15 - J&T EZ (CORRECTED from 25000)
            'GOSEND' => 25000.00,       // ID 13 - GoSend Sameday
            'JNE' => 12000.00,          // ID 14 - JNE REG
            'SICEPAT' => 15000.00,      // ID 16 - SiCepat BEST
            'KURIR_TOKO' => 15000.00,   // ID 11 - Default 5-10km (varies by distance)
            'AMBIL_SENDIRI' => 0.00     // ID 17 - Free pickup
        ];

        $method = strtoupper($method);
        $cost = $costs[$method] ?? 0;

        // Log API access for debugging
        Log::info('Shipping cost API accessed', [
            'method' => $method,
            'cost' => $cost,
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        return response()->json([
            'success' => true,
            'method' => $method,
            'cost' => $cost,
            'formatted_cost' => 'Rp' . number_format($cost, 0, ',', '.'),
            'currency' => 'IDR',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    });

    // Get all shipping costs
    Route::get('/shipping-costs', function() {
        $costs = [
            'JNT' => [
                'cost' => 14000.00,
                'service' => 'EZ',
                'description' => 'Pengiriman reguler via J&T Express'
            ],
            'GOSEND' => [
                'cost' => 25000.00,
                'service' => 'Sameday',
                'description' => 'Pengiriman cepat via GoSend'
            ],
            'JNE' => [
                'cost' => 12000.00,
                'service' => 'REG',
                'description' => 'Pengiriman reguler via JNE'
            ],
            'SICEPAT' => [
                'cost' => 15000.00,
                'service' => 'BEST',
                'description' => 'Pengiriman reguler via SiCepat'
            ],
            'KURIR_TOKO' => [
                'cost' => 15000.00,
                'service' => 'Internal',
                'description' => 'Kurir toko (ongkir bervariasi sesuai jarak)',
                'distance_pricing' => [
                    'less_than_5km' => 10000.00,
                    '5_to_10km' => 15000.00,
                    'more_than_10km' => 20000.00
                ]
            ],
            'AMBIL_SENDIRI' => [
                'cost' => 0.00,
                'service' => '-',
                'description' => 'Ambil sendiri di toko (GRATIS)'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $costs,
            'updated_at' => '2025-07-29 13:29:10',
            'updated_by' => 'mulyadafa',
            'note' => 'JNT cost corrected from Rp25,000 to Rp14,000'
        ]);
    });

    // Calculate shipping cost with distance (for KURIR_TOKO)
    Route::post('/calculate-shipping', function(Request $request) {
        $request->validate([
            'method' => 'required|string|in:JNT,GOSEND,JNE,SICEPAT,KURIR_TOKO,AMBIL_SENDIRI',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $method = strtoupper($request->input('method'));
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        // Base costs
        $baseCosts = [
            'JNT' => 14000.00,
            'GOSEND' => 25000.00,
            'JNE' => 12000.00,
            'SICEPAT' => 15000.00,
            'KURIR_TOKO' => 15000.00,
            'AMBIL_SENDIRI' => 0.00
        ];

        $cost = $baseCosts[$method] ?? 0;
        $distanceInfo = null;

        // Special calculation for KURIR_TOKO with coordinates
        if ($method === 'KURIR_TOKO' && !is_null($lat) && !is_null($lng)) {
            // Azka Garden store coordinates
            $storeLat = -6.4122794;
            $storeLng = 106.829692;

            // Calculate distance using Haversine formula (approximate)
            $distance = sqrt(pow($lat - $storeLat, 2) + pow($lng - $storeLng, 2)) * 111.32; // km

            if ($distance > 10) {
                $cost = 20000.00; // > 10km
                $zone = 'more_than_10km';
            } elseif ($distance > 5) {
                $cost = 15000.00; // 5-10km
                $zone = '5_to_10km';
            } else {
                $cost = 10000.00; // < 5km
                $zone = 'less_than_5km';
            }

            $distanceInfo = [
                'distance_km' => round($distance, 2),
                'zone' => $zone,
                'store_coordinates' => [
                    'latitude' => $storeLat,
                    'longitude' => $storeLng,
                    'address' => 'Jl. Raya KSU, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16412'
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'method' => $method,
            'cost' => $cost,
            'formatted_cost' => 'Rp' . number_format($cost, 0, ',', '.'),
            'distance_info' => $distanceInfo,
            'calculated_at' => now()->format('Y-m-d H:i:s')
        ]);
    });

    // Get available shipping methods
    Route::get('/shipping-methods', function() {
        $methods = [
            [
                'code' => 'JNT',
                'name' => 'J&T Express',
                'service' => 'EZ',
                'cost' => 14000.00,
                'active' => true
            ],
            [
                'code' => 'JNE',
                'name' => 'JNE',
                'service' => 'REG',
                'cost' => 12000.00,
                'active' => true
            ],
            [
                'code' => 'SICEPAT',
                'name' => 'SiCepat',
                'service' => 'BEST',
                'cost' => 15000.00,
                'active' => true
            ],
            [
                'code' => 'GOSEND',
                'name' => 'GoSend',
                'service' => 'Sameday',
                'cost' => 25000.00,
                'active' => true
            ],
            [
                'code' => 'KURIR_TOKO',
                'name' => 'Kurir Toko',
                'service' => 'Internal',
                'cost' => 15000.00,
                'active' => true,
                'note' => 'Ongkir bervariasi sesuai jarak'
            ],
            [
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri',
                'service' => '-',
                'cost' => 0.00,
                'active' => true,
                'note' => 'Gratis'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $methods,
            'total' => count($methods)
        ]);
    });

    // Get shipping cost from config (fallback)
    Route::get('/config/shipping/{method?}', function($method = null) {
        $costs = config('shipping.costs', [
            'JNT' => 14000.00,
            'GOSEND' => 25000.00,
            'JNE' => 12000.00,
            'SICEPAT' => 15000.00,
            'KURIR_TOKO' => 15000.00,
            'AMBIL_SENDIRI' => 0.00
        ]);

        if ($method) {
            $method = strtoupper($method);
            $cost = $costs[$method] ?? 0;
            
            return response()->json([
                'success' => true,
                'method' => $method,
                'cost' => $cost,
                'source' => 'config'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $costs,
            'source' => 'config'
        ]);
    });

    // endpoint lain yang sudah ada...
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
|
| Routes yang bisa diakses tanpa authentication
|
*/

// Public shipping cost endpoint (for non-authenticated users)
Route::get('/public/shipping-cost/{method}', function($method) {
    $costs = [
        'JNT' => 14000.00,
        'GOSEND' => 25000.00,
        'JNE' => 12000.00,
        'SICEPAT' => 15000.00,
        'KURIR_TOKO' => 15000.00,
        'AMBIL_SENDIRI' => 0.00
    ];

    $method = strtoupper($method);
    $cost = $costs[$method] ?? 0;

    return response()->json([
        'success' => true,
        'method' => $method,
        'cost' => $cost,
        'formatted_cost' => 'Rp' . number_format($cost, 0, ',', '.'),
        'public' => true
    ]);
});

// Health check endpoint
Route::get('/health', function() {
    return response()->json([
        'status' => 'OK',
        'service' => 'Azka Garden API',
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ]);
});