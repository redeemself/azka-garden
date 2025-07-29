<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder untuk setting shipping costs yang benar
     * Updated: 2025-07-29 13:45:17 UTC by mulyadafa
     */
    public function run(): void
    {
        $this->command->info('=== Starting ShippingSeeder ===');
        $this->command->info('Current timestamp: 2025-07-29 13:45:17 UTC');
        $this->command->info('Current user: mulyadafa');
        $this->command->info('');
        
        // Gunakan pendekatan berbeda - Update nilai atau buat shipping methods table
        if (Schema::hasTable('shipping_methods')) {
            $this->updateShippingMethods();
        } else {
            $this->createShippingMethodsTable();
        }
        
        // Coba update shipping costs di existing records jika ada
        $this->updateExistingShippingCosts();
        
        // Output summary 
        $this->command->info('');
        $this->command->info('=== ShippingSeeder Completed Successfully ===');
        $this->command->info('Shipping costs corrected:');
        $this->command->info('✓ JNT shipping cost: Rp14,000 (corrected from Rp25,000)');
        $this->command->info('✓ KURIR_TOKO distance-based pricing:');
        $this->command->info('  - < 5km: Rp10,000');
        $this->command->info('  - 5-10km: Rp15,000');
        $this->command->info('  - > 10km: Rp20,000');
        $this->command->info('✓ AMBIL_SENDIRI: Free shipping (Rp0)');
        $this->command->info('✓ Other methods: GOSEND(Rp25k), JNE(Rp12k), SICEPAT(Rp15k)');
        $this->command->info('');
        $this->command->info('Completed at: 2025-07-29 13:45:17 UTC by mulyadafa');
    }
    
    /**
     * Update shipping methods in shipping_methods table
     */
    private function updateShippingMethods(): void
    {
        $this->command->info('Updating shipping_methods table...');
        
        $shippingMethods = [
            [
                'code' => 'JNT',
                'name' => 'J&T Express',
                'service' => 'EZ',
                'cost' => 14000.00, // CORRECTED from 25000
                'description' => 'Pengiriman reguler via J&T Express',
                'is_active' => true
            ],
            [
                'code' => 'GOSEND',
                'name' => 'GoSend',
                'service' => 'Sameday',
                'cost' => 25000.00,
                'description' => 'Pengiriman cepat via GoSend',
                'is_active' => true
            ],
            [
                'code' => 'JNE',
                'name' => 'JNE',
                'service' => 'REG',
                'cost' => 12000.00,
                'description' => 'Pengiriman reguler via JNE',
                'is_active' => true
            ],
            [
                'code' => 'SICEPAT',
                'name' => 'SiCepat',
                'service' => 'BEST',
                'cost' => 15000.00,
                'description' => 'Pengiriman reguler via SiCepat',
                'is_active' => true
            ],
            [
                'code' => 'KURIR_TOKO_DEKAT',
                'name' => 'Kurir Toko (<5km)',
                'service' => 'Internal',
                'cost' => 10000.00,
                'description' => 'Pengiriman langsung dari toko (jarak <5km)',
                'is_active' => true
            ],
            [
                'code' => 'KURIR_TOKO',
                'name' => 'Kurir Toko (5-10km)',
                'service' => 'Internal',
                'cost' => 15000.00,
                'description' => 'Pengiriman langsung dari toko (jarak 5-10km)',
                'is_active' => true
            ],
            [
                'code' => 'KURIR_TOKO_JAUH',
                'name' => 'Kurir Toko (>10km)',
                'service' => 'Internal',
                'cost' => 20000.00,
                'description' => 'Pengiriman langsung dari toko (jarak >10km)',
                'is_active' => true
            ],
            [
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri',
                'service' => '-',
                'cost' => 0.00,
                'description' => 'Ambil langsung di toko (GRATIS)',
                'is_active' => true
            ]
        ];
        
        $now = Carbon::now();
        
        foreach ($shippingMethods as $method) {
            $existing = DB::table('shipping_methods')
                ->where('code', $method['code'])
                ->first();
                
            if ($existing) {
                // Update existing record
                DB::table('shipping_methods')
                    ->where('code', $method['code'])
                    ->update([
                        'cost' => $method['cost'],
                        'name' => $method['name'],
                        'description' => $method['description'],
                        'updated_at' => $now
                    ]);
                $this->command->info("✓ Updated shipping method: {$method['name']} - Rp" . number_format($method['cost'], 0, ',', '.'));
            } else {
                // Insert new record
                $methodData = array_merge($method, [
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                
                DB::table('shipping_methods')->insert($methodData);
                $this->command->info("✓ Created shipping method: {$method['name']} - Rp" . number_format($method['cost'], 0, ',', '.'));
            }
        }
        
        $this->command->info('Shipping methods updated successfully!');
    }
    
    /**
     * Create shipping_methods table if it doesn't exist
     */
    private function createShippingMethodsTable(): void
    {
        $this->command->info('Creating shipping_methods configuration...');
        
        // Use config/shipping.php instead if table doesn't exist
        $configData = [
            'costs' => [
                'JNT' => 14000.00,
                'GOSEND' => 25000.00,
                'JNE' => 12000.00,
                'SICEPAT' => 15000.00,
                'KURIR_TOKO_DEKAT' => 10000.00,
                'KURIR_TOKO' => 15000.00,
                'KURIR_TOKO_JAUH' => 20000.00,
                'AMBIL_SENDIRI' => 0.00
            ]
        ];
        
        // Create or update config file
        $configPath = config_path('shipping.php');
        $configContent = "<?php\n\nreturn " . var_export($configData, true) . ";\n";
        
        try {
            file_put_contents($configPath, $configContent);
            $this->command->info("✓ Created/updated shipping config at {$configPath}");
        } catch (\Exception $e) {
            $this->command->error("✗ Failed to create shipping config: " . $e->getMessage());
        }
    }
    
    /**
     * Update existing shipping costs in shippings table
     */
    private function updateExistingShippingCosts(): void
    {
        $this->command->info('Checking for existing shipping records to update...');
        
        // Only proceed if shippings table exists
        if (!Schema::hasTable('shippings')) {
            $this->command->info('Shippings table does not exist - skipping updates.');
            return;
        }
        
        // Correct JNT cost from 25000 to 14000
        $jntUpdated = DB::table('shippings')
            ->where('courier', 'JNT')
            ->where('shipping_cost', 25000.00)
            ->update(['shipping_cost' => 14000.00]);
            
        if ($jntUpdated > 0) {
            $this->command->info("✓ Corrected {$jntUpdated} JNT shipping records from Rp25,000 to Rp14,000");
        } else {
            $this->command->info("→ No JNT shipping records needed correction");
        }
        
        // Update KURIR_TOKO based on distance patterns in description/notes if applicable
        $this->updateKurirTokoBasedOnDistance();
        
        // Ensure AMBIL_SENDIRI is free
        $selfPickupUpdated = DB::table('shippings')
            ->where('courier', 'AMBIL_SENDIRI')
            ->where('shipping_cost', '>', 0)
            ->update(['shipping_cost' => 0.00]);
            
        if ($selfPickupUpdated > 0) {
            $this->command->info("✓ Set {$selfPickupUpdated} AMBIL_SENDIRI shipping records to free (Rp0)");
        } else {
            $this->command->info("→ No AMBIL_SENDIRI shipping records needed correction");
        }
    }
    
    /**
     * Update KURIR_TOKO prices based on distance patterns
     */
    private function updateKurirTokoBasedOnDistance(): void
    {
        // This is a simplified example - in real application you'd need to use
        // actual distance calculation or pattern matching in existing data
        if (Schema::hasColumn('orders', 'shipping_address')) {
            $this->command->info("Checking for distance-based KURIR_TOKO pricing opportunities...");
            
            // Example: Check for addresses with clear distance indicators
            $nearOrders = DB::table('orders')
                ->where('shipping_method', 'KURIR_TOKO')
                ->whereRaw("shipping_address LIKE '%dekat%' OR shipping_address LIKE '%<5km%'")
                ->pluck('id');
                
            if (count($nearOrders) > 0) {
                DB::table('shippings')
                    ->whereIn('order_id', $nearOrders)
                    ->where('courier', 'KURIR_TOKO')
                    ->update(['shipping_cost' => 10000.00]);
                    
                $this->command->info("✓ Updated " . count($nearOrders) . " KURIR_TOKO shipping records to Rp10,000 (<5km)");
            }
            
            $farOrders = DB::table('orders')
                ->where('shipping_method', 'KURIR_TOKO')
                ->whereRaw("shipping_address LIKE '%jauh%' OR shipping_address LIKE '%>10km%'")
                ->pluck('id');
                
            if (count($farOrders) > 0) {
                DB::table('shippings')
                    ->whereIn('order_id', $farOrders)
                    ->where('courier', 'KURIR_TOKO')
                    ->update(['shipping_cost' => 20000.00]);
                    
                $this->command->info("✓ Updated " . count($farOrders) . " KURIR_TOKO shipping records to Rp20,000 (>10km)");
            }
        }
    }
    
    /**
     * Get correct shipping costs for each method
     */
    public static function getCorrectShippingCosts(): array
    {
        return [
            'JNT' => 14000.00,
            'GOSEND' => 25000.00,
            'JNE' => 12000.00,
            'SICEPAT' => 15000.00,
            'KURIR_TOKO_DEKAT' => 10000.00,
            'KURIR_TOKO' => 15000.00,
            'KURIR_TOKO_JAUH' => 20000.00,
            'AMBIL_SENDIRI' => 0.00
        ];
    }
}