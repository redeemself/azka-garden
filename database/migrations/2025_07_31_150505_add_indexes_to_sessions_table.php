<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Add Performance Indexes to Sessions Table
 * Created: 2025-07-31 15:15:51
 * Updated by: DenuJanuari
 * Fixed getDoctrineSchemaManager compatibility issue
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Check and add indexes only if they don't exist
            // Using raw SQL to avoid Doctrine dependency

            // Add user_id index if it doesn't exist
            if (!$this->indexExists('sessions', 'sessions_user_id_index')) {
                $table->index('user_id', 'sessions_user_id_index');
            }

            // Add last_activity index if it doesn't exist
            if (!$this->indexExists('sessions', 'sessions_last_activity_index')) {
                $table->index('last_activity', 'sessions_last_activity_index');
            }

            // Add composite user_id + last_activity index if it doesn't exist
            if (!$this->indexExists('sessions', 'sessions_user_activity_index')) {
                $table->index(['user_id', 'last_activity'], 'sessions_user_activity_index');
            }

            // Add expires_at index if column exists and index doesn't exist
            if (
                Schema::hasColumn('sessions', 'expires_at') &&
                !$this->indexExists('sessions', 'sessions_expires_at_index')
            ) {
                $table->index('expires_at', 'sessions_expires_at_index');
            }

            // Add token index if column exists and index doesn't exist
            if (
                Schema::hasColumn('sessions', 'token') &&
                !$this->indexExists('sessions', 'sessions_token_index')
            ) {
                $table->index('token', 'sessions_token_index');
            }
        });

        // Log successful migration
        \Log::info('Sessions table indexes added successfully', [
            'timestamp' => '2025-07-31 15:15:51',
            'user' => 'DenuJanuari',
            'migration' => 'add_indexes_to_sessions_table',
            'table' => 'sessions'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Drop indexes if they exist
            if ($this->indexExists('sessions', 'sessions_user_id_index')) {
                $table->dropIndex('sessions_user_id_index');
            }

            if ($this->indexExists('sessions', 'sessions_last_activity_index')) {
                $table->dropIndex('sessions_last_activity_index');
            }

            if ($this->indexExists('sessions', 'sessions_user_activity_index')) {
                $table->dropIndex('sessions_user_activity_index');
            }

            if ($this->indexExists('sessions', 'sessions_expires_at_index')) {
                $table->dropIndex('sessions_expires_at_index');
            }

            if ($this->indexExists('sessions', 'sessions_token_index')) {
                $table->dropIndex('sessions_token_index');
            }
        });

        // Log rollback
        \Log::info('Sessions table indexes removed successfully', [
            'timestamp' => '2025-07-31 15:15:51',
            'user' => 'DenuJanuari',
            'action' => 'rollback_sessions_indexes'
        ]);
    }

    /**
     * Check if an index exists on a table using raw SQL.
     * Fixed: Removed Doctrine dependency
     * 
     * @param string $table
     * @param string $index
     * @return bool
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $database = config('database.connections.mysql.database');

            // Query to check if index exists in MySQL
            $result = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.statistics 
                WHERE table_schema = ? 
                AND table_name = ? 
                AND index_name = ?
            ", [$database, $table, $index]);

            return isset($result[0]) && $result[0]->count > 0;
        } catch (\Exception $e) {
            // Log error and return false to be safe
            \Log::warning('Could not check index existence', [
                'table' => $table,
                'index' => $index,
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-31 15:15:51',
                'user' => 'DenuJanuari'
            ]);

            return false;
        }
    }

    /**
     * Get all indexes for a table (for debugging purposes).
     * 
     * @param string $table
     * @return array
     */
    private function getTableIndexes(string $table): array
    {
        try {
            $database = config('database.connections.mysql.database');

            $indexes = DB::select("
                SELECT 
                    index_name,
                    column_name,
                    non_unique,
                    seq_in_index
                FROM information_schema.statistics 
                WHERE table_schema = ? 
                AND table_name = ?
                ORDER BY index_name, seq_in_index
            ", [$database, $table]);

            return collect($indexes)->groupBy('index_name')->toArray();
        } catch (\Exception $e) {
            \Log::error('Could not retrieve table indexes', [
                'table' => $table,
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-31 15:15:51',
                'user' => 'DenuJanuari'
            ]);

            return [];
        }
    }
};
