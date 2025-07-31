<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Sessions Table Migration for Azka Garden E-Commerce
 * Created: 2025-07-31 15:01:21
 * Updated by: DenuJanuari
 * Fixed session database driver compatibility with Laravel standards
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing sessions table if exists
        Schema::dropIfExists('sessions');

        // Create sessions table with Laravel standard structure
        Schema::create('sessions', function (Blueprint $table) {
            // Laravel session ID - string primary key (not auto-increment)
            $table->string('id', 255)->primary();

            // User ID - nullable foreign key for authenticated sessions
            $table->foreignId('user_id')->nullable()->index()
                ->constrained('users')
                ->onDelete('cascade');

            // Session metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Session payload - stores serialized session data
            $table->longText('payload');

            // Last activity timestamp (Unix timestamp)
            $table->integer('last_activity')->index();

            // Additional token field for custom authentication if needed
            $table->string('token', 255)->nullable()->index();

            // Session expiration
            $table->dateTime('expires_at')->nullable()->index();

            // Standard Laravel timestamps
            $table->timestamps();

            // Performance indexes
            $table->index(['user_id', 'last_activity'], 'sessions_user_activity_idx');
            $table->index(['last_activity', 'expires_at'], 'sessions_activity_expiry_idx');
            $table->index(['user_id', 'token'], 'sessions_user_token_idx');
        });

        // Add table comment for documentation
        DB::statement("ALTER TABLE sessions COMMENT='Laravel sessions table for database session driver - Azka Garden E-Commerce'");

        // Log the migration
        \Log::info('Sessions table created successfully', [
            'timestamp' => '2025-07-31 15:01:21',
            'user' => 'redeemself',
            'table' => 'sessions',
            'migration' => 'create_sessions_table'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Log the rollback
        \Log::info('Rolling back sessions table', [
            'timestamp' => '2025-07-31 15:01:21',
            'user' => 'redeemself',
            'action' => 'rollback_sessions_table'
        ]);

        Schema::dropIfExists('sessions');
    }
};
