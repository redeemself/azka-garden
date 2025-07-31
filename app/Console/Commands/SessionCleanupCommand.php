<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Session Cleanup Command
 * Created: 2025-07-31 14:57:36
 * Updated by: DenuJanuari
 * Cleanup expired sessions for better performance
 */
class SessionCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'session:cleanup 
                           {--hours=24 : Number of hours to keep sessions}
                           {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up expired sessions from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $dryRun = $this->option('dry-run');

        $expiredTime = Carbon::now()->subHours($hours)->timestamp;

        $query = DB::table('sessions')
            ->where('last_activity', '<', $expiredTime);

        $count = $query->count();

        if ($dryRun) {
            $this->info("Would delete {$count} expired sessions (older than {$hours} hours)");

            if ($count > 0) {
                $this->table(
                    ['ID', 'User ID', 'Last Activity', 'IP Address'],
                    $query->select('id', 'user_id', 'last_activity', 'ip_address')
                        ->limit(10)
                        ->get()
                        ->map(function ($session) {
                            return [
                                substr($session->id, 0, 20) . '...',
                                $session->user_id ?? 'Guest',
                                Carbon::createFromTimestamp($session->last_activity)->format('Y-m-d H:i:s'),
                                $session->ip_address
                            ];
                        })
                        ->toArray()
                );

                if ($count > 10) {
                    $this->line("... and " . ($count - 10) . " more sessions");
                }
            }

            return 0;
        }

        $deleted = $query->delete();

        $this->info("Cleaned up {$deleted} expired sessions");
        $this->line("Timestamp: 2025-07-31 14:57:36");
        $this->line("Updated by: DenuJanuari");

        return 0;
    }
}
