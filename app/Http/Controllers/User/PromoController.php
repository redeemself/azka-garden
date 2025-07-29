<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Promotion;
use App\Models\Contact;
use App\Models\Cart;

class PromoController extends Controller
{
    public function activate(Request $request)
    {
        $code = trim($request->input('promo_code'));
        $user = Auth::user();

        if (!$user) {
            // Untuk Ajax
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan login untuk menggunakan kode promo.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
        }

        // Cek membership promo khusus (newsletter/undangan)
        $membership = Contact::where('email', $user->email)
            ->where('promo_code', $code)
            ->first();

        // Jika ada membership promo, langsung valid
        $promo = Promotion::where('promo_code', $code)->first();

        if (!$promo && !$membership) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Kode promo tidak ditemukan']);
            }
            return redirect()->back()->with('error', 'Kode promo tidak ditemukan. Silakan cek kembali.');
        }

        // Validasi status & tanggal promo (jika ada di database)
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

        // Diskon percent sesuai value di DB atau default 10% untuk membership
        if ($promo) {
            $discount_value = ($promo->discount_type === 'percent')
                ? floatval($promo->discount_value)
                : floatval($promo->discount_value);
            $promo_code = $promo->promo_code;
            $promo_type = $promo->discount_type;
        } else if ($membership) {
            // default percent 10% untuk promo newsletter
            $discount_value = 10.0;
            $promo_code = $membership->promo_code;
            $promo_type = 'percent';
        } else {
            $discount_value = 0;
            $promo_code = $code;
            $promo_type = 'fixed';
        }

        // Simpan ke session
        Session::put('promo_code', $promo_code);
        Session::put('promo_type', $promo_type);
        Session::put('promo_discount', $discount_value);

        // Update cart
        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            // Pakai promo code jika belum ada
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
            return response()->json(['success' => true, 'promo_code' => $promo_code, 'promo_type' => $promo_type, 'promo_discount' => $discount_value]);
        }
        return redirect()->back()->with('success', 'Kode promo berhasil diaktifkan!');
    }

    public function deactivate(Request $request)
    {
        Session::forget('promo_code');
        Session::forget('promo_type');
        Session::forget('promo_discount');

        // Update keranjang, hapus diskon
        $user = Auth::user();
        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)->get();
            foreach ($cartItems as $item) {
                $item->promo_code = null;
                $item->discount = 0;
                $item->save();
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Promo dinonaktifkan']);
        }
        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }
}
