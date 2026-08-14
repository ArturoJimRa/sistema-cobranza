<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cobranza.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'rol' => 'ADMIN',
                'estado' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'capturista@cobranza.com'],
            [
                'name' => 'Capturista',
                'password' => Hash::make('captura123'),
                'rol' => 'CAPTURISTA',
                'estado' => 1,
                'email_verified_at' => now(),
            ]
        );
    }
}