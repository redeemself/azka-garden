<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMiddleware extends Command
{
    protected $signature = 'test:middleware';

    protected $description = 'Test if middleware alias policy.accepted works';

    public function handle()
    {
        try {
            $middleware = app()->make('policy.accepted');
            $this->info('Middleware policy.accepted berhasil dibuat: ' . get_class($middleware));
        } catch (\Exception $e) {
            $this->error('Gagal membuat middleware: ' . $e->getMessage());
        }
    }
}
