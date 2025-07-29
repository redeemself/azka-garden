<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\EnumSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\InterfaceSeeder;
use Database\Seeders\EnumRolesSeeder;  // <- Pastikan import Seeder ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed semua tabel enum (lookup)
        $this->call(EnumSeeder::class);

        // 2. Seed tabel enum_roles (tambah ini)
        $this->call(EnumRolesSeeder::class);

        // 3. Seed tabel roles, menggunakan data dari enum_roles
        $this->call(RoleSeeder::class);

        // 4. Seed tabel interfaces (lookup antarmuka)
        $this->call(InterfaceSeeder::class);

        // Jika Anda punya seeder lain, bisa ditambahkan di sini:
        // $this->call(UserSeeder::class);
        // $this->call(ProductSeeder::class);
    }
}
