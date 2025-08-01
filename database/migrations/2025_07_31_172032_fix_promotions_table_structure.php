<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Fix promotions table structure (Version 2)
 * 
 * This migration fixes the duplicate key error and ensures proper
 * table structure without conflicts.
 * 
 * Created: 2025-07-31 17:24:00 by redeemself
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Log::info('Starting promotions table structure fix v2', [
            'timestamp' => '2025-07-31 17:24:00',
            'user' => 'redeemself',
            'migration' => '2025_07_31_172400_fix_promotions_table_structure_v2'
        ]);

        try {
            // First, let's check the current table structure
            $this->analyzeCurrentStructure();

            Schema::table('promotions', function (Blueprint $table) {
                // Check and add promo_code column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'promo_code')) {
                    $table->string('promo_code', 50)->after('id');
                    Log::info('Added promo_code column to promotions table');
                }

                // Check and add title column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'title')) {
                    $table->string('title')->nullable()->after('promo_code');
                    Log::info('Added title column to promotions table');
                }

                // Check and add description column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'description')) {
                    $table->text('description')->nullable()->after('title');
                    Log::info('Added description column to promotions table');
                }

                // Check and add discount_type column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'discount_type')) {
                    $table->enum('discount_type', ['percent', 'fixed'])->default('percent')->after('description');
                    Log::info('Added discount_type column to promotions table');
                }

                // Check and add discount_value column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'discount_value')) {
                    $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
                    Log::info('Added discount_value column to promotions table');
                }

                // Check and add minimum_purchase column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'minimum_purchase')) {
                    $table->decimal('minimum_purchase', 15, 2)->nullable()->after('discount_value');
                    Log::info('Added minimum_purchase column to promotions table');
                }

                // Check and add maximum_discount column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'maximum_discount')) {
                    $table->decimal('maximum_discount', 15, 2)->nullable()->after('minimum_purchase');
                    Log::info('Added maximum_discount column to promotions table');
                }

                // Check and add usage_limit column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'usage_limit')) {
                    $table->integer('usage_limit')->nullable()->after('maximum_discount');
                    Log::info('Added usage_limit column to promotions table');
                }

                // Check and add used_count column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'used_count')) {
                    $table->integer('used_count')->default(0)->after('usage_limit');
                    Log::info('Added used_count column to promotions table');
                }

                // Check and add start_date column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'start_date')) {
                    $table->timestamp('start_date')->nullable()->after('used_count');
                    Log::info('Added start_date column to promotions table');
                }

                // Check and add end_date column if it doesn't exist
                if (!Schema::hasColumn('promotions', 'end_date')) {
                    $table->timestamp('end_date')->nullable()->after('start_date');
                    Log::info('Added end_date column to promotions table');
                }

                // Ensure status column exists and has correct type
                if (!Schema::hasColumn('promotions', 'status')) {
                    $table->boolean('status')->default(true)->after('end_date');
                    Log::info('Added status column to promotions table');
                }

                // Check and add interface_id column if it doesn't exist (for compatibility)
                if (!Schema::hasColumn('promotions', 'interface_id')) {
                    $table->unsignedBigInteger('interface_id')->nullable()->after('status');
                    Log::info('Added interface_id column to promotions table');
                }
            });

            // Remove conflicting active column in separate statement
            $this->removeConflictingColumns();

            // Add indexes and constraints safely
            $this->addIndexesSafely();

            // Update existing records
            $this->updateExistingRecords();

            // Add foreign key constraints
            $this->addForeignKeyConstraints();

            Log::info('Promotions table structure fix v2 completed successfully', [
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fixing promotions table structure v2', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Log::info('Starting promotions table structure rollback v2', [
            'timestamp' => '2025-07-31 17:24:00',
            'user' => 'redeemself'
        ]);

        try {
            // Drop foreign key constraints first
            $this->dropForeignKeyConstraints();

            // Drop indexes safely
            $this->dropIndexesSafely();

            Schema::table('promotions', function (Blueprint $table) {
                // Remove columns that were added (keep essential ones for backward compatibility)
                $columnsToRemove = [
                    'minimum_purchase',
                    'maximum_discount',
                    'usage_limit',
                    'used_count',
                    'interface_id'
                ];

                foreach ($columnsToRemove as $column) {
                    if (Schema::hasColumn('promotions', $column)) {
                        $table->dropColumn($column);
                        Log::info("Dropped column: {$column}");
                    }
                }

                // Restore active column for backward compatibility
                if (!Schema::hasColumn('promotions', 'active')) {
                    $table->boolean('active')->default(true)->after('status');
                    Log::info('Restored active column for backward compatibility');
                }
            });

            Log::info('Promotions table structure rollback v2 completed', [
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rolling back promotions table structure v2', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
            throw $e;
        }
    }

    /**
     * Analyze current table structure
     *
     * @return void
     */
    private function analyzeCurrentStructure(): void
    {
        try {
            $columns = DB::select('SHOW COLUMNS FROM promotions');
            $indexes = DB::select('SHOW INDEX FROM promotions');

            Log::info('Current promotions table structure', [
                'columns' => collect($columns)->pluck('Field')->toArray(),
                'indexes' => collect($indexes)->pluck('Key_name')->unique()->toArray(),
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not analyze table structure: ' . $e->getMessage());
        }
    }

    /**
     * Remove conflicting columns safely
     *
     * @return void
     */
    private function removeConflictingColumns(): void
    {
        try {
            Schema::table('promotions', function (Blueprint $table) {
                // Remove active column if it exists to avoid conflicts
                if (Schema::hasColumn('promotions', 'active')) {
                    $table->dropColumn('active');
                    Log::info('Removed conflicting active column from promotions table');
                }
            });
        } catch (\Exception $e) {
            Log::warning('Could not remove conflicting columns: ' . $e->getMessage());
        }
    }

    /**
     * Add indexes safely by checking existence first
     *
     * @return void
     */
    private function addIndexesSafely(): void
    {
        try {
            // Get existing indexes
            $existingIndexes = $this->getExistingIndexes();

            Schema::table('promotions', function (Blueprint $table) use ($existingIndexes) {
                // Add unique constraint on promo_code if it doesn't exist
                if (
                    !in_array('promotions_promo_code_unique', $existingIndexes) &&
                    !in_array('promo_code', $existingIndexes)
                ) {
                    try {
                        $table->unique('promo_code', 'promotions_promo_code_unique');
                        Log::info('Added unique constraint on promo_code');
                    } catch (\Exception $e) {
                        Log::warning('Could not add unique constraint on promo_code: ' . $e->getMessage());
                        // Try alternative approach
                        DB::statement('ALTER TABLE promotions ADD CONSTRAINT promotions_promo_code_unique UNIQUE (promo_code)');
                        Log::info('Added unique constraint using raw SQL');
                    }
                }

                // Add regular indexes
                $indexesToAdd = [
                    ['column' => 'status', 'name' => 'promotions_status_index'],
                    ['column' => 'start_date', 'name' => 'promotions_start_date_index'],
                    ['column' => 'end_date', 'name' => 'promotions_end_date_index'],
                    ['column' => 'discount_type', 'name' => 'promotions_discount_type_index'],
                ];

                foreach ($indexesToAdd as $indexInfo) {
                    if (!in_array($indexInfo['name'], $existingIndexes)) {
                        try {
                            $table->index($indexInfo['column'], $indexInfo['name']);
                            Log::info("Added index: {$indexInfo['name']}");
                        } catch (\Exception $e) {
                            Log::warning("Could not add index {$indexInfo['name']}: " . $e->getMessage());
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::warning('Could not add indexes safely: ' . $e->getMessage());
        }
    }

    /**
     * Drop indexes safely
     *
     * @return void
     */
    private function dropIndexesSafely(): void
    {
        try {
            $existingIndexes = $this->getExistingIndexes();

            Schema::table('promotions', function (Blueprint $table) use ($existingIndexes) {
                $indexesToDrop = [
                    'promotions_promo_code_unique',
                    'promotions_status_index',
                    'promotions_start_date_index',
                    'promotions_end_date_index',
                    'promotions_discount_type_index'
                ];

                foreach ($indexesToDrop as $indexName) {
                    if (in_array($indexName, $existingIndexes)) {
                        try {
                            if (str_contains($indexName, 'unique')) {
                                $table->dropUnique($indexName);
                            } else {
                                $table->dropIndex($indexName);
                            }
                            Log::info("Dropped index: {$indexName}");
                        } catch (\Exception $e) {
                            Log::warning("Could not drop index {$indexName}: " . $e->getMessage());
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::warning('Could not drop indexes safely: ' . $e->getMessage());
        }
    }

    /**
     * Get existing indexes from the table
     *
     * @return array
     */
    private function getExistingIndexes(): array
    {
        try {
            $indexes = DB::select('SHOW INDEX FROM promotions');
            return collect($indexes)->pluck('Key_name')->unique()->toArray();
        } catch (\Exception $e) {
            Log::warning('Could not get existing indexes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update existing records to ensure data consistency
     *
     * @return void
     */
    private function updateExistingRecords(): void
    {
        try {
            // Update any null status values to true
            $statusUpdated = DB::table('promotions')
                ->whereNull('status')
                ->update(['status' => true]);

            if ($statusUpdated > 0) {
                Log::info("Updated {$statusUpdated} records with null status to true");
            }

            // Update any null used_count values to 0
            if (Schema::hasColumn('promotions', 'used_count')) {
                $usedCountUpdated = DB::table('promotions')
                    ->whereNull('used_count')
                    ->update(['used_count' => 0]);

                if ($usedCountUpdated > 0) {
                    Log::info("Updated {$usedCountUpdated} records with null used_count to 0");
                }
            }

            // Ensure discount_value is not null
            if (Schema::hasColumn('promotions', 'discount_value')) {
                $discountUpdated = DB::table('promotions')
                    ->whereNull('discount_value')
                    ->update(['discount_value' => 0]);

                if ($discountUpdated > 0) {
                    Log::info("Updated {$discountUpdated} records with null discount_value to 0");
                }
            }

            // Ensure discount_type has valid values
            if (Schema::hasColumn('promotions', 'discount_type')) {
                $typeUpdated = DB::table('promotions')
                    ->whereNotIn('discount_type', ['percent', 'fixed'])
                    ->orWhereNull('discount_type')
                    ->update(['discount_type' => 'percent']);

                if ($typeUpdated > 0) {
                    Log::info("Updated {$typeUpdated} records with invalid discount_type to 'percent'");
                }
            }

            // Convert promo codes to uppercase
            if (Schema::hasColumn('promotions', 'promo_code')) {
                $promotions = DB::table('promotions')->whereNotNull('promo_code')->get();
                $uppercaseUpdated = 0;

                foreach ($promotions as $promotion) {
                    $upperCode = strtoupper(trim($promotion->promo_code));
                    if ($upperCode !== $promotion->promo_code) {
                        DB::table('promotions')
                            ->where('id', $promotion->id)
                            ->update(['promo_code' => $upperCode]);
                        $uppercaseUpdated++;
                    }
                }

                if ($uppercaseUpdated > 0) {
                    Log::info("Updated {$uppercaseUpdated} promo codes to uppercase");
                }
            }

            // Remove duplicate promo codes (keep the newest)
            $this->removeDuplicatePromoCodes();

            Log::info('Updated existing promotion records for data consistency', [
                'timestamp' => '2025-07-31 17:24:00',
                'user' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not update existing records: ' . $e->getMessage());
        }
    }

    /**
     * Remove duplicate promo codes
     *
     * @return void
     */
    private function removeDuplicatePromoCodes(): void
    {
        try {
            if (!Schema::hasColumn('promotions', 'promo_code')) {
                return;
            }

            // Find duplicates
            $duplicates = DB::table('promotions')
                ->select('promo_code', DB::raw('COUNT(*) as count'))
                ->whereNotNull('promo_code')
                ->groupBy('promo_code')
                ->having('count', '>', 1)
                ->get();

            $removedCount = 0;

            foreach ($duplicates as $duplicate) {
                // Keep the most recent record, delete others
                $promotions = DB::table('promotions')
                    ->where('promo_code', $duplicate->promo_code)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Skip the first (most recent) record
                $toDelete = $promotions->skip(1);

                foreach ($toDelete as $promotion) {
                    DB::table('promotions')->where('id', $promotion->id)->delete();
                    $removedCount++;
                }
            }

            if ($removedCount > 0) {
                Log::info("Removed {$removedCount} duplicate promotion records");
            }
        } catch (\Exception $e) {
            Log::warning('Could not remove duplicate promo codes: ' . $e->getMessage());
        }
    }

    /**
     * Add foreign key constraints if related tables exist
     *
     * @return void
     */
    private function addForeignKeyConstraints(): void
    {
        try {
            // Check if interface_models table exists
            if (Schema::hasTable('interface_models') && Schema::hasColumn('promotions', 'interface_id')) {
                $existingConstraints = $this->getExistingForeignKeys();

                if (!in_array('promotions_interface_id_foreign', $existingConstraints)) {
                    Schema::table('promotions', function (Blueprint $table) {
                        $table->foreign('interface_id', 'promotions_interface_id_foreign')
                            ->references('id')
                            ->on('interface_models')
                            ->onDelete('set null')
                            ->onUpdate('cascade');
                    });

                    Log::info('Added foreign key constraint for interface_id', [
                        'timestamp' => '2025-07-31 17:24:00',
                        'user' => 'redeemself'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not add foreign key constraints: ' . $e->getMessage());
        }
    }

    /**
     * Drop foreign key constraints
     *
     * @return void
     */
    private function dropForeignKeyConstraints(): void
    {
        try {
            $existingConstraints = $this->getExistingForeignKeys();

            if (in_array('promotions_interface_id_foreign', $existingConstraints)) {
                Schema::table('promotions', function (Blueprint $table) {
                    $table->dropForeign(['interface_id']);
                });
                Log::info('Dropped foreign key constraint on interface_id');
            }
        } catch (\Exception $e) {
            Log::warning('Could not drop foreign key constraints: ' . $e->getMessage());
        }
    }

    /**
     * Get existing foreign key constraints
     *
     * @return array
     */
    private function getExistingForeignKeys(): array
    {
        try {
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'promotions' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            return collect($constraints)->pluck('CONSTRAINT_NAME')->toArray();
        } catch (\Exception $e) {
            Log::warning('Could not get existing foreign keys: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Seed sample promotions for testing
     *
     * @return void
     */
    private function seedSamplePromotions(): void
    {
        try {
            $samplePromotions = [
                [
                    'promo_code' => 'WELCOME10',
                    'title' => 'Welcome Discount',
                    'description' => 'Welcome discount for new customers',
                    'discount_type' => 'percent',
                    'discount_value' => 10.00,
                    'minimum_purchase' => 50000.00,
                    'maximum_discount' => 25000.00,
                    'usage_limit' => 100,
                    'used_count' => 0,
                    'start_date' => now(),
                    'end_date' => now()->addMonths(3),
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'promo_code' => 'GARDEN2025',
                    'title' => 'Garden Special',
                    'description' => 'Special discount for garden enthusiasts',
                    'discount_type' => 'fixed',
                    'discount_value' => 15000.00,
                    'minimum_purchase' => 100000.00,
                    'usage_limit' => 50,
                    'used_count' => 0,
                    'start_date' => now(),
                    'end_date' => now()->addMonths(2),
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            foreach ($samplePromotions as $promotion) {
                $exists = DB::table('promotions')
                    ->where('promo_code', $promotion['promo_code'])
                    ->exists();

                if (!$exists) {
                    DB::table('promotions')->insert($promotion);
                    Log::info("Seeded sample promotion: {$promotion['promo_code']}", [
                        'timestamp' => '2025-07-31 17:24:00',
                        'user' => 'redeemself'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not seed sample promotions: ' . $e->getMessage());
        }
    }
};
