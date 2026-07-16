<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@generationpush.com'],
            [
                'name' => 'Admin Generation PUSH',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
                'role' => 'Admin',
                'country' => 'Burkina Faso',
                'city' => 'Ouagadougou',
            ]
        );

        $demo = [
            ['name' => 'Aisha Kone', 'email' => 'aisha.kone@email.com', 'phone' => '+223 65 12 34 56', 'country' => 'Mali', 'city' => 'Bamako', 'status' => 'active', 'role' => 'Member'],
            ['name' => 'Jean Claude Mvondo', 'email' => 'jc.mvondo@email.com', 'phone' => '+237 67 89 01 23', 'country' => 'Cameroon', 'city' => 'Yaoundé', 'status' => 'active', 'role' => 'Leader'],
            ['name' => 'Fatou Diallo', 'email' => 'fatou.diallo@email.com', 'phone' => '+221 77 12 34 56', 'country' => 'Senegal', 'city' => 'Dakar', 'status' => 'inactive', 'role' => 'Member'],
            ['name' => 'Kwame Asante', 'email' => 'kwame.asante@email.com', 'phone' => '+233 20 12 34 56', 'country' => 'Ghana', 'city' => 'Accra', 'status' => 'active', 'role' => 'Coach'],
            ['name' => 'Amina Hassan', 'email' => 'amina.hassan@email.com', 'phone' => '+256 70 12 34 56', 'country' => 'Uganda', 'city' => 'Kampala', 'status' => 'suspended', 'role' => 'Member'],
            ['name' => 'Daniel Okonkwo', 'email' => 'daniel.okonkwo@email.com', 'phone' => '+234 81 23 45 67', 'country' => 'Nigeria', 'city' => 'Lagos', 'status' => 'active', 'role' => 'Speaker'],
            ['name' => 'Grace Mwangi', 'email' => 'grace.mwangi@email.com', 'phone' => '+254 71 12 34 56', 'country' => 'Kenya', 'city' => 'Nairobi', 'status' => 'active', 'role' => 'Member'],
            ['name' => 'Pierre Durand', 'email' => 'pierre.durand@email.com', 'phone' => '+225 07 12 34 56', 'country' => 'Ivory Coast', 'city' => 'Abidjan', 'status' => 'active', 'role' => 'Trainer'],
        ];

        foreach ($demo as $row) {
            User::updateOrCreate(
                ['email' => $row['email']],
                [...$row, 'password' => Hash::make('password'), 'email_verified_at' => now()]
            );
        }

        User::factory()->count(15)->create();
    }
}
