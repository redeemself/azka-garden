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
 * PromoController
 *
 * Handles promo code activation and deactivation for user cart
 *
 * @updated 2025-07-31 05:22:34 by marseltriwanto
 */
class PromoController extends Controller
{
    /**
     * Aktivasi kode promo.
     * Validasi kode promo, membership promo, status, dan tanggal.
     * Simpan data promo ke session dan update keranjang jika perlu.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function activate(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $request->validate([
                'promo_code' => 'required|string|max:50'
            ]);

            $promoCode = strtoupper(trim($request->promo_code));
            $user = Auth::user();

            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan login untuk menggunakan kode promo.'
                    ], 401);
                }
                return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
            }

            // Cek membership promo khusus (newsletter/undangan)
            $membership = Contact::where('email', $user->email)
                ->where('promo_code', $promoCode)
                ->first();

            // Find the promotion
            $promotion = Promotion::where('promo_code', $promoCode)
                ->where(function ($query) {
                    $query->where('status', 1)
                        ->orWhere('active', true); // Support both column names
                })
                ->where(function ($query) {
                    $now = now();
                    $query->where(function ($q) use ($now) {
                        $q->whereNull('start_date')
                            ->orWhere('start_date', '<=', $now);
                    })->where(function ($q) use ($now) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', $now);
                    });
                })
                ->first();

            // Check if promo exists
            if (!$promotion && !$membership) {
                $msg = 'Kode promo tidak valid atau sudah kadaluarsa';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }

            // Additional validation for promotion if it exists
            if ($promotion) {
                $now = now();

                // Check if promotion is active
                if (!$promotion->status && !($promotion->active ?? false)) {
                    $msg = 'Kode promo sudah tidak aktif.';
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->with('error', $msg);
                }

                // Check start date
                if ($promotion->start_date && $promotion->start_date > $now) {
                    $formattedDate = $promotion->start_date->format('d-m-Y H:i');
                    $msg = "Kode promo belum berlaku. Berlaku mulai {$formattedDate}";
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->with('error', $msg);
                }

                // Check end date
                if ($promotion->end_date && $promotion->end_date < $now) {
                    $formattedDate = $promotion->end_date->format('d-m-Y H:i');
                    $msg = "Kode promo sudah kadaluarsa pada {$formattedDate}";
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->with('error', $msg);
                }
            }

            // Determine discount value and type
            if ($promotion) {
                $discount_value = floatval($promotion->discount_value);
                $promo_code = $promotion->promo_code;
                $promo_type = $promotion->discount_type;
            } elseif ($membership) {
                // Default 10% for membership promo
                $discount_value = 10.0;
                $promo_code = $membership->promo_code;
                $promo_type = 'percent';
            } else {
                $discount_value = 0;
                $promo_code = $promoCode;
                $promo_type = 'fixed';
            }

            // Store promo in session
            Session::put('promo_code', $promo_code);
            Session::put('promo_type', $promo_type);
            Session::put('promo_discount', $discount_value);

            // Update cart items with the promo code
            $cartItems = Cart::where('user_id', $user->id)->get();
            foreach ($cartItems as $item) {
                if (!$item->promo_code) {
                    $item->promo_code = $promo_code;
                    if ($promo_type === 'percent') {
                        $item->discount = round(($item->product->price ?? 0) * ($discount_value / 100));
                    } else {
                        $item->discount = $discount_value;
                    }
                    $item->save();
                }
            }

            // Log success for debugging
            Log::info('Promo code activated', [
                'user_id' => $user->id,
                'promo_code' => $promo_code,
                'promo_type' => $promo_type,
                'discount_value' => $discount_value,
                'timestamp' => now()->toDateTimeString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode promo berhasil diaktifkan',
                    'promo' => [
                        'code' => $promo_code,
                        'type' => $promo_type,
                        'value' => $discount_value
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Kode promo berhasil diaktifkan!');
        } catch (Exception $e) {
            Log::error('Error activating promo code', [
                'promo_code' => $request->input('promo_code'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengaktifkan kode promo.');
        }
    }

    /**
     * Nonaktifkan kode promo.
     * Hapus session promo dan reset diskon di keranjang.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function deactivate(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $user = Auth::user();

            // Get promo code for logging
            $promoCode = Session::get('promo_code');

            // Clear promo session values
            Session::forget(['promo_code', 'promo_type', 'promo_discount']);

            // Reset cart item discounts if user is logged in
            if ($user) {
                Cart::where('user_id', $user->id)
                    ->update([
                        'promo_code' => null,
                        'discount' => 0
                    ]);

                Log::info('Promo code deactivated', [
                    'user_id' => $user->id,
                    'promo_code' => $promoCode,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode promo berhasil dihapus'
                ]);
            }

            return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
        } catch (Exception $e) {
            Log::error('Error deactivating promo code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kode promo.');
        }
    }
}
