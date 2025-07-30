<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\EnumSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\InterfaceSeeder;
use Database\Seeders\EnumRolesSeeder;
use Database\Seeders\ShippingSeeder;
use Database\Seeders\ShippingMethodSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\PromotionSeeder;
use Database\Seeders\PaymentMethodSeeder;

/**
 * Database Seeder
 * 
 * Main seeder class that orchestrates the execution of all seeders
 * 
 * @updated 2025-07-30 03:52:17 UTC by mulyadafa
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Updated: 2025-07-30 03:52:17 UTC by mulyadafa
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
        
        // 7. Create admin user
        $this->command->info('7. Creating admin user...');
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@azkagarden.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        
        // 8. Create test users
        $this->command->info('8. Creating test users...');
        \App\Models\User::factory(10)->create();
        
        // 9. Seed additional data
        $this->command->info('9. Seeding additional data...');
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            PromotionSeeder::class,
            PaymentMethodSeeder::class,
        ]);

        // Display completion summary
        $this->command->info('');
        $this->command->info('=== Database Seeding Completed Successfully ===');
        $this->command->info('Total seeders executed: 9');
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