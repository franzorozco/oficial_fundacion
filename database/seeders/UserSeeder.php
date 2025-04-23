<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que los roles existen
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'volunteer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'donor', 'guard_name' => 'web']);

        User::create([
            'name' => 'Franz Jhostin Orozco Salazar',
            'email' => 'ftanzorozco2@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('admin');

        User::create([
            'name' => 'Juan Carlos Melgarejo',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('volunteer');

        User::create([
            'name' => 'Isaac Mealla',
            'email' => 'isacc@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('donor');
        
    }
}
