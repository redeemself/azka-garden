<?php

namespace App\Extensions\Database;

use Illuminate\Database\Connection;

/**
 * ConnectionExtension provides compatibility for missing methods
 *
 * @author DenuJanuari
 * @created 2025-08-01 05:06:15
 */
class ConnectionExtension extends Connection
{
    /**
     * Get the name of the connected database.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getConfig('name') ?: $this->getConfig('database');
    }
}
