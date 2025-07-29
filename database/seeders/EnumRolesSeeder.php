<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnumRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = ['CUSTOMER', 'ADMIN', 'USER', 'GUEST', 'DEVELOPER'];

        foreach ($roles as $role) {
            DB::table('enum_roles')->updateOrInsert(
                ['value' => $role],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
