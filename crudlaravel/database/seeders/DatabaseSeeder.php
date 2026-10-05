<?php

namespace Database\Seeders;

use Database\Seeders\UsuarioSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Solo el seeder de usuarios: no usa factories ni faker,
        // así funciona en producción (composer --no-dev).
        $this->call([UsuarioSeeder::class]);
    }
}
