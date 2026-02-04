<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer Admin
        User::create([
            'name' => 'Lauriano',
            'email' => 'elelgbedelauriano@gmail.com',
            'password' => Hash::make('angelo123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer Aide
        User::create([
            'name' => 'Aide Test',
            'email' => 'aide@ejp.com',
            'password' => Hash::make('password123'),
            'role' => 'aide',
            'email_verified_at' => now(),
        ]);

        echo "Utilisateurs créés :\n";
        echo "- elelgbedelauriano@gmail.com / angelo123 (admin)\n";
        echo "- aide@ejp.com / password123 (Aide)\n";
    }
}