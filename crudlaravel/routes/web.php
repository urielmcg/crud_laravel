<?php

// Facade Route: registra las URLs de la app.
// UsuarioController: nuestro CRUD de la tabla `usuarios`.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Redirigir la raíz al CRUD (en vez del welcome de Laravel).
// Así al abrir http://localhost:8000 o http://localhost/crudlaravel/public ves usuarios.
Route::get('/', function () {
    return redirect()->route('usuarios.index');
});

// Una sola línea crea las 7 rutas del CRUD:
//  GET /usuarios, GET /usuarios/create, POST /usuarios,
//  GET /usuarios/{usuario}, GET /usuarios/{usuario}/edit,
//  PUT /usuarios/{usuario}, DELETE /usuarios/{usuario}
// Los nombres generados: usuarios.index, usuarios.create, usuarios.store,
// usuarios.show, usuarios.edit, usuarios.update, usuarios.destroy
Route::resource('usuarios', UsuarioController::class);
