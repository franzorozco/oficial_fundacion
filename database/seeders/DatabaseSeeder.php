<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Llamar al seeder de roles
        $this->call(RoleSeeder::class);

        // Llamar al seeder de usuarios
        $this->call(UserSeeder::class);
    }
}
