<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Models\Promotion; // pastikan model Promotion sudah dibuat
use App\Mail\PromoCodeMail;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        // Validasi input email
        $data = $request->validate([
            'email' => 'required|email|max:255|unique:contacts,email',
        ]);

        try {
            // Mulai DB Transaction
            DB::beginTransaction();

            // Simpan email ke table contacts
            $contact = Contact::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => 'Newsletter Subscriber',
                    'message' => 'newsletter',
                ]
            );

            // Pastikan kolom promo_code sudah ada di migration
            // Gunakan array access agar hilang warning undefined property
            $promoCode = $contact['promo_code'] ?? null;

            if (empty($promoCode)) {
                // Generate kode promo unik
                do {
                    $promoCode = 'PROMO-' . strtoupper(Str::random(6));
                } while (Contact::where('promo_code', $promoCode)->exists());

                // Simpan ke contact
                $contact['promo_code'] = $promoCode;
                $contact->save();
            }

            // === Sinkronisasi ke tabel promotions, agar kode promo bisa diaktifkan ===
            Promotion::firstOrCreate(
                ['promo_code' => $promoCode],
                [
                    'title' => 'Promo Newsletter untuk ' . ($contact['email'] ?? ''),
                    'description' => 'Promo khusus subscriber newsletter.',
                    'discount_type' => 'percent',
                    'discount_value' => 10,
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                    'status' => 1,
                    'interface_id' => 1
                ]
            );
            // === End sinkronisasi ===

            DB::commit();

            // Kirim email kode promo ke subscriber
            try {
                $emailRecipient = $contact['email'] ?? null;
                if ($emailRecipient) {
                    Mail::to($emailRecipient)->send(new PromoCodeMail($promoCode));
                } else {
                    return back()->with('error', 'Gagal menemukan email subscriber.');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Berlangganan berhasil, tapi gagal mengirim email promo: ' . $e->getMessage());
            }

            // Flash message sukses
            return back()->with('success', 'Berhasil berlangganan! Kode promo telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
