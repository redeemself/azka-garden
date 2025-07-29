<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::updateOrCreate([
            'code' => 'bank_transfer'
        ], [
            'name' => 'Transfer Bank',
            'type' => 'LOCAL',
            'config' => json_encode(['desc' => 'Transfer ke rekening BCA']),
            'status' => 1
        ]);

        PaymentMethod::updateOrCreate([
            'code' => 'qris'
        ], [
            'name' => 'QRIS',
            'type' => 'LOCAL',
            'config' => json_encode(['desc' => 'Pembayaran QRIS']),
            'status' => 1
        ]);

        PaymentMethod::updateOrCreate([
            'code' => 'stripe'
        ], [
            'name' => 'Stripe',
            'type' => 'GLOBAL',
            'config' => json_encode(['desc' => 'Kartu Kredit / Debit']),
            'status' => 1
        ]);
    }
}
