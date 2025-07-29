<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeederStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeder:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the status of all seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seeders = DB::table('seeder_status')->orderBy('ran_at', 'desc')->get();

        if ($seeders->isEmpty()) {
            $this->info('No seeders have been run yet.');
            return 0;
        }

        $this->table(
            ['Seeder Name', 'Last Run At'],
            $seeders->map(function ($seeder) {
                return [$seeder->seeder_name, $seeder->ran_at];
            })->toArray()
        );

        return 0;
    }
}
