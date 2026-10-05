<?php

// Migración que crea la tabla `usuarios` solo si no existe.
// Permite desplegar en una base de datos nueva (por ejemplo SQLite en Render)
// sin afectar una base existente que ya tenga la tabla.
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up(): se ejecuta con `php artisan migrate`.
    public function up(): void
    {
        // Protección: si la tabla ya existe (base heredada), no se hace nada.
        if (Schema::hasTable('usuarios')) {
            return;
        }

        Schema::create('usuarios', function (Blueprint $table) {
            // Clave primaria autoincremental.
            $table->increments('id_usuario');
            // Nombre de usuario único para el inicio de sesión.
            $table->string('nombreusuario', 35)->unique();
            // Capacidad de 255 caracteres para almacenar el hash bcrypt.
            $table->string('password', 255);
            // Tipo de usuario con valor predeterminado.
            $table->string('tipousuario', 20)->default('cliente');
            // Estado con valor predeterminado.
            $table->string('estado', 20)->default('activo');
            $table->string('nombre', 35);
            $table->string('paterno', 35);
            $table->string('materno', 35)->nullable();
            $table->date('fecha')->nullable();
            $table->string('ci', 20)->nullable();
            $table->string('email', 100)->nullable();
            // Sin columnas created_at / updated_at: el modelo tiene timestamps desactivados.
        });
    }

    // down(): revierte la migración con `php artisan migrate:rollback`.
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
