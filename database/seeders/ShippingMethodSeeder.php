<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('=== Starting ShippingMethodSeeder ===');
        $now = Carbon::now();

        if (!Schema::hasTable('shipping_methods')) {
            $this->command->error('Table shipping_methods does not exist!');
            $this->command->info('Creating config file instead...');
            $this->createShippingConfig();
            return;
        }

        $tableColumns = Schema::getColumnListing('shipping_methods');
        $this->command->info('Available columns in shipping_methods table: ' . implode(', ', $tableColumns));
        $hasSettingsColumn = in_array('settings', $tableColumns);

        $this->command->info('Using upsert approach to avoid foreign key issues...');

        $methods = [
            [
                'code' => 'JNT',
                'name' => 'J&T Express',
                'service' => 'EZ',
                'cost' => 14000.00,
                'description' => 'Pengiriman reguler via J&T Express (Rp14,000)',
                'is_active' => true,
                'sort' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'JNE',
                'name' => 'JNE',
                'service' => 'REG',
                'cost' => 12000.00,
                'description' => 'Pengiriman reguler via JNE (Rp12,000)',
                'is_active' => true,
                'sort' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'SICEPAT',
                'name' => 'SiCepat',
                'service' => 'BEST',
                'cost' => 15000.00,
                'description' => 'Pengiriman reguler via SiCepat (Rp15,000)',
                'is_active' => true,
                'sort' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'GOSEND',
                'name' => 'GoSend',
                'service' => 'Sameday',
                'cost' => 25000.00,
                'description' => 'Pengiriman cepat via GoSend (estimasi Rp25,000 sesuai jarak)',
                'is_active' => true,
                'sort' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'KURIR_TOKO_DEKAT',
                'name' => 'Kurir Toko (<5km)',
                'service' => 'Internal-Dekat',
                'cost' => 10000.00,
                'description' => 'Pengiriman langsung dari toko Azka Garden (jarak <5km)',
                'is_active' => true,
                'sort' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'KURIR_TOKO',
                'name' => 'Kurir Toko (5-10km)',
                'service' => 'Internal',
                'cost' => 15000.00,
                'description' => 'Pengiriman langsung dari toko Azka Garden (jarak 5-10km)',
                'is_active' => true,
                'sort' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'KURIR_TOKO_JAUH',
                'name' => 'Kurir Toko (>10km)',
                'service' => 'Internal-Jauh',
                'cost' => 20000.00,
                'description' => 'Pengiriman langsung dari toko Azka Garden (jarak >10km)',
                'is_active' => true,
                'sort' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri',
                'service' => '-',
                'cost' => 0.00,
                'description' => 'Ambil langsung di toko Azka Garden, bebas ongkir!',
                'is_active' => true,
                'sort' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        if ($hasSettingsColumn) {
            $settingsData = [
                'KURIR_TOKO_DEKAT' => ['distance_range' => 'less_than_5km', 'max_distance' => 5],
                'KURIR_TOKO' => ['distance_range' => '5_to_10km', 'min_distance' => 5, 'max_distance' => 10],
                'KURIR_TOKO_JAUH' => ['distance_range' => 'more_than_10km', 'min_distance' => 10],
                'AMBIL_SENDIRI' => ['free_shipping' => true],
            ];
            foreach ($methods as &$method) {
                $method['settings'] = $settingsData[$method['code']] ?? null;
                if ($method['settings']) {
                    $method['settings'] = json_encode($method['settings']);
                }
            }
        }

        $inserted = 0;
        $updated = 0;

        foreach ($methods as $method) {
            $existing = DB::table('shipping_methods')->where('code', $method['code'])->first();

            if ($existing) {
                $updateData = $method;
                unset($updateData['created_at']);
                unset($updateData['code']);

                DB::table('shipping_methods')
                    ->where('code', $method['code'])
                    ->update($updateData);

                $this->command->info("✓ Updated: {$method['code']} - {$method['name']} (Rp" . number_format($method['cost'], 0, ',', '.') . ")");
                $updated++;
            } else {
                DB::table('shipping_methods')->insert($method);
                $this->command->info("✓ Inserted: {$method['code']} - {$method['name']} (Rp" . number_format($method['cost'], 0, ',', '.') . ")");
                $inserted++;
            }
        }

        $this->createShippingConfig();

        $this->command->info('');
        $this->command->info('=== ShippingMethodSeeder Completed Successfully ===');
        $this->command->info("✓ Inserted {$inserted} new shipping methods");
        $this->command->info("✓ Updated {$updated} existing shipping methods");
        $this->command->info("✓ Total methods processed: " . ($inserted + $updated));
    }

    /**
     * Create shipping config file as fallback
     */
    private function createShippingConfig(): void
    {
        $configData = [
            'costs' => [
                'JNT' => 14000.00,
                'GOSEND' => 25000.00,
                'JNE' => 12000.00,
                'SICEPAT' => 15000.00,
                'KURIR_TOKO_DEKAT' => 10000.00,
                'KURIR_TOKO' => 15000.00,
                'KURIR_TOKO_JAUH' => 20000.00,
                'AMBIL_SENDIRI' => 0.00,
            ],
            'kurir_toko_costs' => [
                'less_than_5km' => 10000.00,
                '5_to_10km' => 15000.00,
                'more_than_10km' => 20000.00,
            ],
            'store_location' => [
                'name' => 'Azka Garden',
                'address' => 'Jl. Raya KSU, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16412',
                'latitude' => -6.4122794,
                'longitude' => 106.829692,
            ],
            'method_labels' => [
                'JNT' => 'J&T EZ',
                'GOSEND' => 'GoSend Sameday',
                'JNE' => 'JNE REG',
                'SICEPAT' => 'SiCepat BEST',
                'KURIR_TOKO_DEKAT' => 'Kurir Toko (<5km)',
                'KURIR_TOKO' => 'Kurir Toko (5-10km)',
                'KURIR_TOKO_JAUH' => 'Kurir Toko (>10km)',
                'AMBIL_SENDIRI' => 'Ambil Sendiri di Toko',
            ],
            'service_names' => [
                'JNT' => 'EZ',
                'GOSEND' => 'Sameday',
                'JNE' => 'REG',
                'SICEPAT' => 'BEST',
                'KURIR_TOKO_DEKAT' => 'Internal-Dekat',
                'KURIR_TOKO' => 'Internal',
                'KURIR_TOKO_JAUH' => 'Internal-Jauh',
                'AMBIL_SENDIRI' => '-',
            ],
            'defaults' => [
                'method' => 'JNT',
                'cost' => 14000.00,
                'free_shipping_threshold' => 100000,
                'tax_rate' => 0.11,
            ],
            'updated_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => 'mulyadafa',
        ];

        $configPath = config_path('shipping.php');
        $configContent = "<?php\n\n// Generated by ShippingMethodSeeder\n\nreturn " . var_export($configData, true) . ";\n";

        try {
            file_put_contents($configPath, $configContent);
            $this->command->info('✓ Created/updated shipping config file at ' . $configPath);
        } catch (\Exception $e) {
            $this->command->error('✗ Failed to create shipping config file: ' . $e->getMessage());
        }
    }
}
