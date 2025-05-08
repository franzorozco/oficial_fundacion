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
        Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'campaign_manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'donor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'volunteer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);


        User::create([
            'name' => 'Franz Jhostin Orozco Salazar',
            'email' => 'ftanzorozco2@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('admin')->assignRole('user'); // Asigna el rol de admin y el rol de usuario simple
        
        User::create([
            'name' => 'Juan Carlos Melgarejo',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('volunteer')->assignRole('user'); // Asigna el rol de voluntario y el rol de usuario simple
        
        User::create([
            'name' => 'Isacc Mealla',
            'email' => 'isacc@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
        ])->assignRole('donor')->assignRole('user'); // Asigna el rol de donante y el rol de usuario simple
        
        User::create([
            'name' => 'Carlos Pérez',
            'email' => 'carlos.perez@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567891',
        ])->assignRole('donor')->assignRole('user'); // Asigna el rol de donante y el rol de usuario simple
        
        User::create([
            'name' => 'Ana García',
            'email' => 'ana.garcia@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567892',
        ])->assignRole('volunteer')->assignRole('user'); // Asigna el rol de voluntario y el rol de usuario simple
        
        User::create([
            'name' => 'Luis Gómez',
            'email' => 'luis.gomez@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567893',
        ])->assignRole('user')->assignRole('user'); // Asigna solo el rol de usuario simple (obligatorio para todos)
        
        User::create([
            'name' => 'Marta Rodríguez',
            'email' => 'marta.rodriguez@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567894',
        ])->assignRole('donor')->assignRole('user'); // Asigna el rol de donante y el rol de usuario simple
        
        User::create([
            'name' => 'Pedro Díaz',
            'email' => 'pedro.diaz@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567895',
        ])->assignRole('volunteer')->assignRole('user'); // Asigna el rol de voluntario y el rol de usuario simple
        
        User::create([
            'name' => 'Sofía Morales',
            'email' => 'sofia.morales@example.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567896',
        ])->assignRole('user')->assignRole('user'); // Asigna solo el rol de usuario simple (obligatorio para todos)
        
        
    }
}
