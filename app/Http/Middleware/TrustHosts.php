<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     */
    public function hosts(): array
    {
        // Percayai semua subdomain dari APP_URL
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
