<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Promotion;
use App\Models\Contact;
<<<<<<< HEAD

class PromoController extends Controller
{
    /**
     * Aktivasi kode promo.
     * Validasi kode promo, membership promo, status, dan tanggal.
     * Simpan data promo ke session.
     * Tidak melakukan update langsung ke keranjang (biarkan CartController tangani).
     */
=======
use App\Models\Cart;

class PromoController extends Controller
{
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
    public function activate(Request $request)
    {
        $code = trim($request->input('promo_code'));
        $user = Auth::user();

        if (!$user) {
<<<<<<< HEAD
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan login untuk menggunakan kode promo.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
        }

        // Cek membership promo khusus (newsletter/undangan)
=======
            return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
        }

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $membership = Contact::where('email', $user->email)
            ->where('promo_code', $code)
            ->first();

<<<<<<< HEAD
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

        // Jangan update keranjang di sini; biarkan CartController yang tangani saat checkout

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'promo_code' => $promo_code,
                'promo_type' => $promo_type,
                'promo_discount' => $discount_value,
                'message' => 'Kode promo berhasil diaktifkan!'
            ]);
=======
        if (!$membership) {
            return redirect()->back()->with('error', 'Kode promo tidak valid untuk akun Anda.');
        }

        $now = now();
        $promo = Promotion::where('promo_code', $code)->active()->first();

        if (!$promo) {
            $promoCheck = Promotion::where('promo_code', $code)->first();
            if (!$promoCheck) {
                return redirect()->back()->with('error', 'Kode promo tidak ditemukan. Silakan cek kembali.');
            }
            if (!$promoCheck->status) {
                return redirect()->back()->with('error', 'Kode promo sudah tidak aktif.');
            }
            if ($promoCheck->start_date && $promoCheck->start_date > $now) {
                return redirect()->back()->with('error', 'Kode promo belum berlaku. Berlaku mulai ' . $promoCheck->start_date->format('d-m-Y H:i'));
            }
            if ($promoCheck->end_date && $promoCheck->end_date < $now) {
                return redirect()->back()->with('error', 'Kode promo sudah kadaluarsa pada ' . $promoCheck->end_date->format('d-m-Y H:i'));
            }
            return redirect()->back()->with('error', 'Kode promo tidak valid atau sudah kadaluarsa.');
        }

        // Fix: diskon percent selalu 10%
        $discount_value = ($promo->discount_type === 'percent') ? 10.0 : floatval($promo->discount_value);

        Session::put('promo_code', $promo->promo_code);
        Session::put('promo_type', $promo->discount_type);
        Session::put('promo_discount', $discount_value);

        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            if (!$item->promo_code) {
                $item->promo_code = $promo->promo_code;
                if ($promo->discount_type === 'percent') {
                    $item->discount = round(($item->product->price ?? 0) * (10/100));
                } else {
                    $item->discount = $discount_value;
                }
                $item->save();
            }
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        }

        return redirect()->back()->with('success', 'Kode promo berhasil diaktifkan!');
    }

<<<<<<< HEAD
    /**
     * Nonaktifkan kode promo.
     * Hanya hapus session promo tanpa update keranjang.
     */
    public function deactivate(Request $request)
    {
        Session::forget(['promo_code', 'promo_type', 'promo_discount']);

        // Jangan update keranjang; biarkan diskon tidak berlaku saat checkout

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Promo berhasil dinonaktifkan']);
        }

        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }
}
=======
    public function deactivate(Request $request)
    {
        Session::forget('promo_code');
        Session::forget('promo_type');
        Session::forget('promo_discount');

        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }
}
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
