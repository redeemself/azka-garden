<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Promotion;
use App\Models\Contact;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * PromoController
 *
 * Handles promo code activation and deactivation for user cart
 *
 * @updated 2025-07-30 04:25:22 by mulyadafa
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
        $code = trim($request->input('promo_code'));
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan login untuk menggunakan kode promo.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
        }

        // Cek membership promo khusus (newsletter/undangan)
        $membership = Contact::where('email', $user->email)
            ->where('promo_code', $code)
            ->first();

        // Cari promo di DB
        $promo = Promotion::where('promo_code', $code)->first();

        if (!$promo && !$membership) {
            $msg = 'Kode promo tidak ditemukan. Silakan cek kembali.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return redirect()->back()->with('error', $msg);
        }

        // Validasi status & tanggal promo jika ada
        if ($promo) {
            $now = now();
            if (!$promo->status) {
                $msg = 'Kode promo sudah tidak aktif.';
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg])
                    : redirect()->back()->with('error', $msg);
            }
            if ($promo->start_date && $promo->start_date > $now) {
                $msg = 'Kode promo belum berlaku. Berlaku mulai ' . $promo->start_date->format('d-m-Y H:i');
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg])
                    : redirect()->back()->with('error', $msg);
            }
            if ($promo->end_date && $promo->end_date < $now) {
                $msg = 'Kode promo sudah kadaluarsa pada ' . $promo->end_date->format('d-m-Y H:i');
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg])
                    : redirect()->back()->with('error', $msg);
            }
        }

        // Tentukan nilai diskon dan tipe promo
        if ($promo) {
            $discount_value = floatval($promo->discount_value);
            $promo_code = $promo->promo_code;
            $promo_type = $promo->discount_type;
        } elseif ($membership) {
            // Default 10% untuk membership promo
            $discount_value = 10.0;
            $promo_code = $membership->promo_code;
            $promo_type = 'percent';
        } else {
            $discount_value = 0;
            $promo_code = $code;
            $promo_type = 'fixed';
        }

        // Simpan promo ke session
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'promo_code' => $promo_code,
                'promo_type' => $promo_type,
                'promo_discount' => $discount_value,
                'message' => 'Kode promo berhasil diaktifkan!'
            ]);
        }

        return redirect()->back()->with('success', 'Kode promo berhasil diaktifkan!');
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
        $user = Auth::user();

        // Clear promo session values
        Session::forget(['promo_code', 'promo_type', 'promo_discount']);

        // Reset cart item discounts if user is logged in
        if ($user) {
            Cart::where('user_id', $user->id)
                ->update([
                    'promo_code' => null,
                    'discount' => 0
                ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Promo berhasil dinonaktifkan']);
        }

        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }
}
