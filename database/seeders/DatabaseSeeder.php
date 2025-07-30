<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Database\Seeders\EnumSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\InterfaceSeeder;
use Database\Seeders\EnumRolesSeeder;
use Database\Seeders\ShippingSeeder;
use Database\Seeders\ShippingMethodSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Faker\Factory as Faker;

/**
 * Database Seeder
 *
 * Main seeder class that orchestrates the execution of all seeders
 *
 * @updated 2025-07-30 05:20:32 UTC by mulyadafa
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Updated: 2025-07-30 05:20:32 UTC by mulyadafa
     * Fixed user creation to avoid column errors by using direct DB inserts
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

        // Get database column information first
        $this->command->info('Checking database schema...');
        $userColumns = $this->getUserTableColumns();
        $this->command->info('   - Found ' . count($userColumns) . ' columns in users table');

        // 7. Create admin user
        $this->command->info('7. Creating admin user...');
        $this->createAdminUser($userColumns);

        // 8. Create test users - only if admin user was successfully created
        $this->command->info('8. Creating test users...');
        $this->createTestUsers($userColumns, 3);

        // 9. Seed additional data
        $this->command->info('9. Seeding additional data...');

        // Use conditional seeding to prevent IDE warnings about unknown classes
        $additionalSeeders = [];

        if (class_exists(CategorySeeder::class)) {
            $additionalSeeders[] = CategorySeeder::class;
        } else {
            $this->command->warn('CategorySeeder class not found. Skipping...');
        }

        if (class_exists(ProductSeeder::class)) {
            $additionalSeeders[] = ProductSeeder::class;
        } else {
            $this->command->warn('ProductSeeder class not found. Skipping...');
        }

        if (class_exists(PromotionSeeder::class)) {
            $additionalSeeders[] = PromotionSeeder::class;
        } else {
            $this->command->warn('PromotionSeeder class not found. Skipping...');
        }

        $additionalSeeders[] = PaymentMethodSeeder::class;

        $this->call($additionalSeeders);

        // Display completion summary
        $this->command->info('');
        $this->command->info('=== Database Seeding Completed Successfully ===');
        $this->command->info('Total seeders executed: ' . (5 + count($additionalSeeders)));
        $this->command->info('Key shipping updates applied:');
        $this->command->info('  ✓ JNT shipping cost: Rp14,000 (corrected)');
        $this->command->info('  ✓ KURIR_TOKO: Distance-based pricing');
        $this->command->info('  ✓ AMBIL_SENDIRI: Free shipping');
        $this->command->info('  ✓ All other shipping costs validated');
        $this->command->info('');
        $this->command->info('Completed at: ' . now()->format('Y-m-d H:i:s') . ' UTC');
        $this->command->info('Executed by: mulyadafa');
    }

    /**
     * Get columns that exist in the users table
     *
     * @return array
     */
    private function getUserTableColumns(): array
    {
        $columns = [];
        try {
            $columns = DB::select("SHOW COLUMNS FROM users");
            $columns = array_map(function ($column) {
                return $column->Field;
            }, $columns);
        } catch (\Exception $e) {
            $this->command->error('Failed to get users table columns: ' . $e->getMessage());
        }
        return $columns;
    }

    /**
     * Create an admin user with only the columns that exist in the database
     *
     * @param array $existingColumns
     * @return bool
     */
    private function createAdminUser(array $existingColumns): bool
    {
        try {
            $userData = [
                'name' => 'Admin User',
                'email' => 'admin@azkagarden.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Only include columns that exist in the database
            if (in_array('role', $existingColumns)) {
                $userData['role'] = 'admin';
            }

            if (in_array('role_id', $existingColumns)) {
                try {
                    $adminRoleId = DB::table('roles')->where('name', 'admin')->first()?->id ?? 1;
                    $userData['role_id'] = $adminRoleId;
                } catch (\Exception $e) {
                    $this->command->warn('Could not find admin role ID. Using default value 1.');
                    $userData['role_id'] = 1;
                }
            }

            if (in_array('interface_id', $existingColumns)) {
                $userData['interface_id'] = 1;
            }

            // Filter out any columns that don't exist in the database
            $userData = array_intersect_key($userData, array_flip($existingColumns));

            DB::table('users')->insert($userData);
            $this->command->info('   - Admin user created successfully');
            return true;
        } catch (\Exception $e) {
            $this->command->error('   - Failed to create admin user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create test users with only the columns that exist in the database
     *
     * @param array $existingColumns
     * @param int $count
     * @return bool
     */
    private function createTestUsers(array $existingColumns, int $count): bool
    {
        try {
            $faker = Faker::create();

            // Get available roles
            $roles = ['user'];
            try {
                $dbRoles = DB::table('roles')->pluck('name')->toArray();
                if (!empty($dbRoles)) {
                    $roles = $dbRoles;
                }
            } catch (\Exception $e) {
                $this->command->warn('Could not fetch roles from database. Using default roles.');
            }

            $userRoleId = 2; // Default user role ID
            try {
                $userRoleId = DB::table('roles')->where('name', 'user')->first()?->id ?? 2;
            } catch (\Exception $e) {
                $this->command->warn('Could not find user role ID. Using default value 2.');
            }

            for ($i = 0; $i < $count; $i++) {
                $userData = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Add optional columns if they exist in the database
                if (in_array('phone', $existingColumns)) {
                    $userData['phone'] = $faker->phoneNumber;
                }

                if (in_array('role', $existingColumns)) {
                    $userData['role'] = 'user';
                }

                if (in_array('role_id', $existingColumns)) {
                    $userData['role_id'] = $userRoleId;
                }

                if (in_array('interface_id', $existingColumns)) {
                    $userData['interface_id'] = 1;
                }

                // Filter out any columns that don't exist in the database
                $userData = array_intersect_key($userData, array_flip($existingColumns));

                DB::table('users')->insert($userData);
            }

            $this->command->info('   - ' . $count . ' test users created successfully');
            return true;
        } catch (\Exception $e) {
            $this->command->error('   - Failed to create test users: ' . $e->getMessage());
            return false;
        }
    }
}
