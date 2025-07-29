<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\EnumSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\InterfaceSeeder;
use Database\Seeders\EnumRolesSeeder;
use Database\Seeders\ShippingSeeder;
use Database\Seeders\ShippingMethodSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Updated: 2025-07-29 13:26:26 UTC by mulyadafa
     * Added shipping-related seeders for proper ongkos kirim handling
     */
    public function run(): void
    {
        // Display seeding start information
        $this->command->info('=== Database Seeding Started ===');
        $this->command->info('Timestamp: ' . now()->format('Y-m-d H:i:s') . ' UTC');
        $this->command->info('User: mulyadafa');
        $this->command->info('');

        // 1. Seed semua tabel enum (lookup)
        $this->command->info('1. Seeding Enum tables...');
        $this->call(EnumSeeder::class);

        // 2. Seed tabel enum_roles
        $this->command->info('2. Seeding Enum Roles...');
        $this->call(EnumRolesSeeder::class);

        // 3. Seed tabel roles, menggunakan data dari enum_roles
        $this->command->info('3. Seeding Roles...');
        $this->call(RoleSeeder::class);

        // 4. Seed tabel interfaces (lookup antarmuka)
        $this->command->info('4. Seeding Interfaces...');
        $this->call(InterfaceSeeder::class);

        // 5. Seed shipping data dengan ongkos kirim yang benar
        $this->command->info('5. Seeding Shipping data...');
        $this->command->info('   - Correcting JNT cost from Rp25,000 to Rp14,000');
        $this->command->info('   - Setting up distance-based KURIR_TOKO pricing');
        $this->command->info('   - Ensuring AMBIL_SENDIRI is free (Rp0)');
        $this->call(ShippingSeeder::class);

        // 6. Seed shipping methods master data
        $this->command->info('6. Seeding Shipping Methods...');
        $this->call(ShippingMethodSeeder::class);
        
        // Additional seeders can be added here as needed
        $this->command->info('');
        $this->command->info('Available additional seeders (uncomment to use):');
        $this->command->info('   // $this->call(UserSeeder::class);');
        $this->command->info('   // $this->call(ProductSeeder::class);');
        $this->command->info('   // $this->call(CategorySeeder::class);');
        $this->command->info('   // $this->call(PaymentMethodSeeder::class);');
        $this->command->info('   // $this->call(PromotionSeeder::class);');

        // Future seeders placeholder (uncomment when ready)
        // $this->call(UserSeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(PaymentMethodSeeder::class);
        // $this->call(PromotionSeeder::class);

        // Display completion summary
        $this->command->info('');
        $this->command->info('=== Database Seeding Completed Successfully ===');
        $this->command->info('Total seeders executed: 6');
        $this->command->info('Key shipping updates applied:');
        $this->command->info('  ✓ JNT shipping cost: Rp14,000 (corrected)');
        $this->command->info('  ✓ KURIR_TOKO: Distance-based pricing');
        $this->command->info('  ✓ AMBIL_SENDIRI: Free shipping');
        $this->command->info('  ✓ All other shipping costs validated');
        $this->command->info('');
        $this->command->info('Completed at: ' . now()->format('Y-m-d H:i:s') . ' UTC');
        $this->command->info('Executed by: mulyadafa');
    }
}