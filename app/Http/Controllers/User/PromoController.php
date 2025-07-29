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
            return redirect()->back()->with('error', 'Silakan login untuk menggunakan kode promo.');
        }

        $membership = Contact::where('email', $user->email)
            ->where('promo_code', $code)
            ->first();

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
        }

        return redirect()->back()->with('success', 'Kode promo berhasil diaktifkan!');
    }

    public function deactivate(Request $request)
    {
        Session::forget('promo_code');
        Session::forget('promo_type');
        Session::forget('promo_discount');

        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }
}