<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update shipping costs sesuai requirement
     * Updated: 2025-07-29 13:14:08 by mulyadafa
     */
    public function up(): void
    {
        // Update JNT shipping cost from 25000 to 14000
        DB::table('shippings')
            ->where('courier', 'JNT')
            ->where('service', 'EZ')
            ->update([
                'shipping_cost' => 14000.00,
                'updated_at' => now()
            ]);

        // Ensure other costs are correct
        $updates = [
            ['courier' => 'GOSEND', 'service' => 'Sameday', 'cost' => 25000.00],
            ['courier' => 'JNE', 'service' => 'REG', 'cost' => 12000.00],
            ['courier' => 'SICEPAT', 'service' => 'BEST', 'cost' => 15000.00],
            ['courier' => 'AMBIL_SENDIRI', 'service' => '-', 'cost' => 0.00],
        ];

        foreach ($updates as $update) {
            DB::table('shippings')
                ->where('courier', $update['courier'])
                ->where('service', $update['service'])
                ->update([
                    'shipping_cost' => $update['cost'],
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert JNT cost back to 25000 (original incorrect value)
        DB::table('shippings')
            ->where('courier', 'JNT')
            ->where('service', 'EZ')
            ->update([
                'shipping_cost' => 25000.00,
                'updated_at' => now()
            ]);
    }
};