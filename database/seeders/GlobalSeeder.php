<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'bio' => 'Biografía de ' . $user->name,
                'document_number' => 'CI-' . rand(1000000, 9999999),
                'photo' => 'profiles/' . strtolower(str_replace(' ', '_', $user->name)) . '.jpg',
                'birthdate' => now()->subYears(rand(18, 50))->subDays(rand(0, 365)),

                // Campos personalizados
                'skills' => fake()->sentence(6),
                'interests' => fake()->sentence(6),
                'availability_days' => 'lunes, miércoles, viernes',
                'availability_hours' => '08:00 - 12:00',
                'location' => fake()->city(),
                'transport_available' => (bool)rand(0, 1),
                'experience_level' => collect(['básico', 'intermedio', 'avanzado'])->random(),
                'physical_condition' => collect(['buena', 'moderada', 'limitada'])->random(),
                'preferred_tasks' => fake()->sentence(8),
                'languages_spoken' => 'español, inglés',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
