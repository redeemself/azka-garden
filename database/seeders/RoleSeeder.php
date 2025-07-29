<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('enum_roles') || !Schema::hasTable('roles')) {
            return;
        }

        $enumRoles = DB::table('enum_roles')->select('id', 'value')->get()->keyBy('value');

        $rolesToInsert = [
            'ADMIN',
            'USER',
            'GUEST',
            'CUSTOMER',
            'DEVELOPER',
        ];

        foreach ($rolesToInsert as $roleValue) {
            if (isset($enumRoles[$roleValue])) {
                DB::table('roles')->updateOrInsert(
                    ['enum_role_id' => $enumRoles[$roleValue]->id], // kondisi unik
                    [
                        'name' => $roleValue,   // kolom wajib di tabel roles
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
