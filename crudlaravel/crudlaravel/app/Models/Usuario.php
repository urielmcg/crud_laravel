<?php

namespace App\Models;

// Importamos la clase base Eloquent: es el ORM de Laravel.
// Cada modelo representa una tabla y permite consultar/crear/editar sin SQL manual.
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Usuario
 * ------------------------------------------------------------------
 * Mapea la tabla EXISTENTE `usuarios` de tu base `juvenil`
 * (creada por juvenil.sql, NO la tabla `users` que trae Laravel).
 *
 * Estructura real (juvenil.sql líneas 310-323):
 *  id_usuario (PK, autoincrement), nombreusuario, password,
 *  tipousuario (administrador|cliente), estado (activo|inactivo),
 *  nombre, paterno, materno, fecha, ci, email
 */
class Usuario extends Model
{
    // 1. Nombre exacto de la tabla en MariaDB.
    //    Sin esto Laravel buscaría "usuarios" en plural inglés ("usuarios" sí coincide,
    //    pero lo dejamos explícito para que se entienda).
    protected $table = 'usuarios';

    // 2. Clave primaria NO se llama `id` sino `id_usuario`.
    //    Hay que decirle a Eloquent cuál es.
    protected $primaryKey = 'id_usuario';

    // 3. La PK es autoincremental (igual que en tu SQL). true = Eloquent no la pide al insertar.
    public $incrementing = true;

    // 4. Tipo de la clave: entero.
    protected $keyType = 'int';

    // 5. Tu tabla NO tiene columnas `created_at` ni `updated_at`.
    //    false evita que Laravel intente guardarlas y falle con "unknown column".
    public $timestamps = false;

    // 6. Campos que se pueden asignar en masa (create/update con $request->all() filtrado).
    //    Es la lista blanca de seguridad contra Mass Assignment.
    protected $fillable = [
        'nombreusuario', // login, ej: admin01
        'password',      // se guarda con Hash::make() en el controlador
        'tipousuario',   // administrador | cliente
        'estado',        // activo | inactivo
        'nombre',
        'paterno',
        'materno',
        'fecha',         // date nacimiento
        'ci',
        'email',
    ];

    // 7. Ocultamos el password cuando el modelo se convierte a JSON/array.
    protected $hidden = [
        'password',
    ];
}
