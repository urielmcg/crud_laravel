<?php

// Migración SEGURA para reutilizar tu tabla existente `usuarios`.
// NO crea ni borra la tabla (tus 5 usuarios se conservan).
// Solo amplía `password` de VARCHAR(35) a VARCHAR(255) porque
// bcrypt (Hash::make) genera 60 caracteres y con 35 se cortaría.
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up(): se ejecuta con `php artisan migrate`
    public function up(): void
    {
        // Solo actuar si la tabla ya existe (viene de juvenil.sql).
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // change() = modificar columna existente. Requiere doctrine/dbal en Laravel <11,
                // en Laravel 12 funciona nativo para varchar.
                $table->string('password', 255)->change();
            });
        }
    }

    // down(): revierte el cambio con `php artisan migrate:rollback`
    public function down(): void
    {
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('password', 35)->change();
            });
        }
    }
};
