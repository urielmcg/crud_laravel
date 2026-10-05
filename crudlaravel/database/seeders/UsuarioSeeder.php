<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    // Crea 3 usuarios de demostración de forma idempotente.
    // firstOrCreate permite ejecutar el seeder varias veces sin duplicar registros.
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['nombreusuario' => 'admin01'],
            [
                'password' => Hash::make('Admin123!'),
                'tipousuario' => 'administrador',
                'estado' => 'activo',
                'nombre' => 'Juan',
                'paterno' => 'Pérez',
                'materno' => 'Mamani',
                'fecha' => '1990-05-15',
                'ci' => '1234567 LP',
                'email' => 'admin01@example.com',
            ]
        );

        Usuario::firstOrCreate(
            ['nombreusuario' => 'cliente01'],
            [
                'password' => Hash::make('Cliente123!'),
                'tipousuario' => 'cliente',
                'estado' => 'activo',
                'nombre' => 'María',
                'paterno' => 'Quispe',
                'materno' => 'Condori',
                'fecha' => '1995-08-20',
                'ci' => '7654321 CB',
                'email' => 'cliente01@example.com',
            ]
        );

        Usuario::firstOrCreate(
            ['nombreusuario' => 'invitado01'],
            [
                'password' => Hash::make('Invitado123!'),
                'tipousuario' => 'cliente',
                'estado' => 'inactivo',
                'nombre' => 'Carlos',
                'paterno' => 'Flores',
                'materno' => 'Alarcón',
                'fecha' => '2000-01-10',
                'ci' => '4567890 SC',
                'email' => 'invitado01@example.com',
            ]
        );
    }
}
