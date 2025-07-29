<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterfaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('interfaces')->updateOrInsert(
            ['id' => 1],
            [
                'name'        => 'User Interface',
                'description' => 'Interface untuk user biasa',
            ]
        );

        DB::table('interfaces')->updateOrInsert(
            ['id' => 8],
            [
                'name'        => 'Admin Interface',
                'description' => 'Interface untuk admin',
            ]
        );

        DB::table('interfaces')->updateOrInsert(
            ['id' => 11],
            [
                'name'        => 'Developer Interface',
                'description' => 'Interface untuk developer',
            ]
        );
    }
}
