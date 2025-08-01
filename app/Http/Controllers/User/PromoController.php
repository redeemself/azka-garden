<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Promotion;
use App\Models\Contact;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;

/**
 * User Promo Controller
 * 
 * Handles promo code activation and deactivation for user cart
 * Updated: 2025-07-31 17:10:57 by DenuJanuari
 */
class PromoController extends Controller
{
    /**
     * Activate promo code
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request): RedirectResponse|JsonResponse
    {
        try {
            Log::info('Promo activation attempt', [
                'promo_code' => $request->input('promo_code'),
                'user_id' => auth()->id(),
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            // Validate input
            $request->validate([
                'promo_code' => 'required|string|max:50'
            ], [
                'promo_code.required' => 'Kode promo harus diisi',
                'promo_code.string' => 'Format kode promo tidak valid',
                'promo_code.max' => 'Kode promo terlalu panjang'
            ]);

            $promoCode = strtoupper(trim($request->input('promo_code')));
            $user = Auth::user();

            // Check if user is authenticated
            if (!$user) {
                Log::warning('Unauthenticated promo activation attempt', [
                    'promo_code' => $promoCode,
                    'ip' => $request->ip(),
                    'timestamp' => '2025-07-31 17:10:57'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan login untuk menggunakan kode promo',
                        'error' => 'Unauthenticated',
                        'data' => []
                    ], 401);
                }

                return back()->with('error', 'Silakan login untuk menggunakan kode promo');
            }

            // Check if promo is already active
            if (Session::get('promo_code')) {
                $currentPromo = Session::get('promo_code');
                if ($currentPromo === $promoCode) {
                    Log::warning('Promo code already active', [
                        'promo_code' => $promoCode,
                        'user_id' => $user->id,
                        'timestamp' => '2025-07-31 17:10:57'
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kode promo sudah aktif',
                            'error' => 'Already active',
                            'data' => []
                        ], 400);
                    }

                    return back()->with('error', 'Kode promo sudah aktif');
                }
            }

            // Check membership promo (newsletter/invitation)
            $membership = Contact::where('email', $user->email)
                ->where('promo_code', $promoCode)
                ->first();

            // Find the promotion in database - FIXED: Only use status column
            $promotion = Promotion::where('promo_code', $promoCode)
                ->where('status', 1) // Only use status column, not active
                ->where(function ($query) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->first();

            // Check if promo exists
            if (!$promotion && !$membership) {
                Log::warning('Invalid promo code attempted', [
                    'promo_code' => $promoCode,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'timestamp' => '2025-07-31 17:10:57',
                    'user' => 'DenuJanuari'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode promo tidak valid atau sudah kedaluwarsa',
                        'error' => 'Invalid promo code',
                        'data' => []
                    ], 400);
                }

                return back()->with('error', 'Kode promo tidak valid atau sudah kedaluwarsa');
            }

            // Additional validation for promotion if it exists
            if ($promotion) {
                $now = now();

                // Check if promotion is active
                if (!$promotion->status) {
                    Log::warning('Inactive promo code attempted', [
                        'promo_code' => $promoCode,
                        'promotion_status' => $promotion->status,
                        'user_id' => $user->id,
                        'timestamp' => '2025-07-31 17:10:57'
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kode promo sudah tidak aktif',
                            'error' => 'Promo inactive',
                            'data' => []
                        ], 400);
                    }

                    return back()->with('error', 'Kode promo sudah tidak aktif');
                }

                // Check start date
                if ($promotion->start_date && $promotion->start_date > $now) {
                    $formattedDate = $promotion->start_date->format('d-m-Y H:i');
                    $message = "Kode promo belum berlaku. Berlaku mulai {$formattedDate}";

                    Log::warning('Promo code not yet valid', [
                        'promo_code' => $promoCode,
                        'start_date' => $promotion->start_date,
                        'current_time' => $now,
                        'user_id' => $user->id,
                        'timestamp' => '2025-07-31 17:10:57'
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'error' => 'Not yet valid',
                            'data' => []
                        ], 400);
                    }

                    return back()->with('error', $message);
                }

                // Check end date
                if ($promotion->end_date && $promotion->end_date < $now) {
                    $formattedDate = $promotion->end_date->format('d-m-Y H:i');
                    $message = "Kode promo sudah kadaluarsa pada {$formattedDate}";

                    Log::warning('Expired promo code attempted', [
                        'promo_code' => $promoCode,
                        'end_date' => $promotion->end_date,
                        'current_time' => $now,
                        'user_id' => $user->id,
                        'timestamp' => '2025-07-31 17:10:57'
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'error' => 'Expired',
                            'data' => []
                        ], 400);
                    }

                    return back()->with('error', $message);
                }

                // Check usage limit if applicable
                if ($promotion->usage_limit && $promotion->used_count >= $promotion->usage_limit) {
                    Log::warning('Promo code usage limit exceeded', [
                        'promo_code' => $promoCode,
                        'usage_limit' => $promotion->usage_limit,
                        'used_count' => $promotion->used_count,
                        'user_id' => $user->id,
                        'timestamp' => '2025-07-31 17:10:57'
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kode promo sudah mencapai batas penggunaan',
                            'error' => 'Usage limit exceeded',
                            'data' => []
                        ], 400);
                    }

                    return back()->with('error', 'Kode promo sudah mencapai batas penggunaan');
                }
            }

            // Determine discount value and type
            if ($promotion) {
                $discount_value = floatval($promotion->discount_value);
                $promo_code = $promotion->promo_code;
                $promo_type = $promotion->discount_type;
                $promo_description = $promotion->description ?? '';
            } elseif ($membership) {
                // Default 10% for membership promo
                $discount_value = 10.0;
                $promo_code = $membership->promo_code;
                $promo_type = 'percent';
                $promo_description = 'Promo member khusus';
            } else {
                // Fallback (should not reach here)
                $discount_value = 0;
                $promo_code = $promoCode;
                $promo_type = 'fixed';
                $promo_description = '';
            }

            // Store promo data in session
            Session::put([
                'promo_code' => $promo_code,
                'promo_type' => $promo_type,
                'promo_discount' => $discount_value,
                'promo_description' => $promo_description,
                'promo_activated_at' => now()->toDateTimeString()
            ]);

            // Update cart items with the promo code
            $cartItems = Cart::where('user_id', $user->id)->get();
            $updatedItems = 0;

            foreach ($cartItems as $item) {
                if (!$item->promo_code) {
                    $item->promo_code = $promo_code;

                    if ($promo_type === 'percent') {
                        $item->discount = round(($item->product->price ?? $item->price) * ($discount_value / 100));
                    } else {
                        $item->discount = min($discount_value, $item->product->price ?? $item->price);
                    }

                    $item->save();
                    $updatedItems++;
                }
            }

            // Increment usage count for promotion
            if ($promotion) {
                $promotion->increment('used_count');
            }

            Log::info('Promo code activated successfully', [
                'promo_code' => $promo_code,
                'discount_type' => $promo_type,
                'discount_value' => $discount_value,
                'user_id' => $user->id,
                'cart_items_updated' => $updatedItems,
                'is_membership_promo' => (bool) $membership,
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode promo berhasil diterapkan',
                    'error' => null,
                    'data' => [
                        'promo_code' => $promo_code,
                        'discount_type' => $promo_type,
                        'discount_value' => $discount_value,
                        'description' => $promo_description,
                        'cart_items_updated' => $updatedItems
                    ]
                ], 200);
            }

            return back()->with('success', 'Kode promo berhasil diterapkan');
        } catch (Exception $e) {
            Log::error('Error activating promo code', [
                'promo_code' => $request->input('promo_code'),
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                    'data' => []
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menerapkan kode promo');
        }
    }

    /**
     * Deactivate promo code
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deactivate(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $user = Auth::user();
            $currentPromoCode = Session::get('promo_code');

            Log::info('Promo deactivation attempt', [
                'current_promo_code' => $currentPromoCode,
                'user_id' => $user ? $user->id : null,
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            // Check if there's an active promo
            if (!$currentPromoCode) {
                Log::warning('No active promo to deactivate', [
                    'user_id' => $user ? $user->id : null,
                    'timestamp' => '2025-07-31 17:10:57'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada kode promo yang aktif',
                        'error' => 'No active promo',
                        'data' => []
                    ], 400);
                }

                return back()->with('error', 'Tidak ada kode promo yang aktif');
            }

            // Clear promo session values
            Session::forget([
                'promo_code',
                'promo_type',
                'promo_discount',
                'promo_description',
                'promo_activated_at'
            ]);

            // Reset cart item discounts if user is logged in
            $updatedItems = 0;
            if ($user) {
                $updatedItems = Cart::where('user_id', $user->id)
                    ->where('promo_code', $currentPromoCode)
                    ->update([
                        'promo_code' => null,
                        'discount' => 0
                    ]);
            }

            Log::info('Promo code deactivated successfully', [
                'deactivated_promo_code' => $currentPromoCode,
                'user_id' => $user ? $user->id : null,
                'cart_items_updated' => $updatedItems,
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode promo berhasil dihapus',
                    'error' => null,
                    'data' => [
                        'deactivated_promo_code' => $currentPromoCode,
                        'cart_items_updated' => $updatedItems
                    ]
                ], 200);
            }

            return back()->with('success', 'Kode promo berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Error deactivating promo code', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                    'data' => []
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menghapus kode promo');
        }
    }

    /**
     * Check if promo code is valid (for API endpoints)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'promo_code' => 'required|string|max:50'
            ]);

            $promoCode = strtoupper(trim($request->input('promo_code')));
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                    'valid' => false
                ], 401);
            }

            // Check membership promo
            $membership = Contact::where('email', $user->email)
                ->where('promo_code', $promoCode)
                ->first();

            // Check promotion
            $promotion = Promotion::where('promo_code', $promoCode)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->first();

            $isValid = $promotion || $membership;

            Log::info('Promo code validity check', [
                'promo_code' => $promoCode,
                'user_id' => $user->id,
                'is_valid' => $isValid,
                'is_membership' => (bool) $membership,
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => true,
                'message' => $isValid ? 'Kode promo valid' : 'Kode promo tidak valid',
                'valid' => $isValid,
                'data' => $isValid ? [
                    'promo_code' => $promoCode,
                    'discount_type' => $promotion ? $promotion->discount_type : 'percent',
                    'discount_value' => $promotion ? $promotion->discount_value : 10.0,
                    'is_membership_promo' => (bool) $membership
                ] : []
            ]);
        } catch (Exception $e) {
            Log::error('Error checking promo code validity', [
                'promo_code' => $request->input('promo_code'),
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'valid' => false,
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get current active promo information
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request): JsonResponse
    {
        try {
            $promoCode = Session::get('promo_code');
            $promoType = Session::get('promo_type');
            $promoDiscount = Session::get('promo_discount');
            $promoDescription = Session::get('promo_description');
            $promoActivatedAt = Session::get('promo_activated_at');

            $hasActivePromo = !empty($promoCode);

            Log::info('Current promo information requested', [
                'user_id' => auth()->id(),
                'has_active_promo' => $hasActivePromo,
                'promo_code' => $promoCode,
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => true,
                'message' => $hasActivePromo ? 'Promo aktif ditemukan' : 'Tidak ada promo aktif',
                'has_active_promo' => $hasActivePromo,
                'data' => $hasActivePromo ? [
                    'promo_code' => $promoCode,
                    'promo_type' => $promoType,
                    'promo_discount' => $promoDiscount,
                    'promo_description' => $promoDescription,
                    'activated_at' => $promoActivatedAt
                ] : []
            ]);
        } catch (Exception $e) {
            Log::error('Error getting current promo information', [
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'timestamp' => '2025-07-31 17:10:57',
                'user' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'has_active_promo' => false,
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
