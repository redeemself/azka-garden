<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS local_payment_methods");
        DB::statement("CREATE VIEW local_payment_methods AS SELECT * FROM payment_methods WHERE type = 'LOCAL'");
        DB::statement("DROP VIEW IF EXISTS global_payment_methods");
        DB::statement("CREATE VIEW global_payment_methods AS SELECT * FROM payment_methods WHERE type = 'GLOBAL'");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS local_payment_methods");
        DB::statement("DROP VIEW IF EXISTS global_payment_methods");
    }
};
