<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Promotion;
use Carbon\Carbon;

class SyncContactPromoSeeder extends Seeder
{
    public function run()
    {
        $contacts = Contact::whereNotNull('promo_code')->get();
        foreach ($contacts as $contact) {
            // Gunakan akses array agar warning hilang
            $promoCode = $contact['promo_code'] ?? null;
            $name = $contact['name'] ?? 'Member';

            if (!$promoCode) continue;

            Promotion::firstOrCreate(
                ['promo_code' => $promoCode],
                [
                    'title' => 'Promo Membership untuk ' . $name,
                    'description' => 'Promo otomatis dari membership/contact.',
                    'discount_type' => 'percent',
                    'discount_value' => 10,
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(30),
                    'status' => 1,
                    'interface_id' => 1
                ]
            );
        }
    }
}
