<?php

namespace App\Http\Controllers;

// Modelo Eloquent de la tabla `usuarios`.
use App\Models\Usuario;
// Request: contiene los datos del formulario + validación.
// Hash: encripta passwords con bcrypt (reemplaza tu password_hash() manual).
// RedirectResponse/View: tipos de retorno (solo para claridad del comentario).
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// DB: para borrar tablas hijas en transacción (cliente, administrador).
// QueryException: para capturar el error 1451 de FK y mostrar mensaje amigable en vez de 500.
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

/**
 * UsuarioController — CRUD completo para la tabla `usuarios`.
 * ------------------------------------------------------------------
 * Convención Resource de Laravel (7 acciones):
 *  GET    /usuarios          -> index()   (listar)
 *  GET    /usuarios/create   -> create()  (form nuevo)
 *  POST   /usuarios          -> store()   (guardar nuevo)
 *  GET    /usuarios/{id}     -> show()    (ver uno)
 *  GET    /usuarios/{id}/edit-> edit()    (form editar)
 *  PUT    /usuarios/{id}     -> update()  (guardar cambios)
 *  DELETE /usuarios/{id}     -> destroy() (eliminar)
 *
 * Equivale a tu flujo actual:
 *  usuariolista.php  -> index()  | usuarioform.php   -> create()/edit()
 *  UsuarioClase::*   -> este controlador + modelo Usuario
 *  usuarioControlador.php -> store()/update()/destroy()
 */
class UsuarioController extends Controller
{
    /**
     * 1. LISTAR — GET /usuarios
     * Muestra todos los usuarios ordenados por id descendente, paginados de 10 en 10.
     * Equivale a Usuario::listarUsuarios() de tu proyecto actual.
     */
    public function index()
    {
        // orderBy + paginate evita traer miles de filas de golpe.
        $usuarios = Usuario::orderBy('id_usuario', 'desc')->paginate(10);

        // Pasamos la colección a la vista Blade.
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * 2. FORMULARIO CREAR — GET /usuarios/create
     * Solo muestra el formulario vacío (vista/usuarioform.php en tu proyecto).
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * 3. GUARDAR NUEVO — POST /usuarios
     * Valida, encripta el password y crea la fila.
     * Equivale a tu bloque `if (isset($_POST['guardar_admin']))` + `guardarConHerencia()`.
     */
    public function store(Request $request)
    {
        // Validación del lado servidor (reemplaza tu validarPasswordServer + required HTML).
        // Si falla, Laravel redirige atrás con errores y old() automáticamente.
        $datos = $request->validate([
            'nombreusuario' => 'required|string|max:35|unique:usuarios,nombreusuario',
            // min:8 + mayúscula + símbolo = misma regla que tu regex actual.
            'password'      => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[!@#$%^&*(),.?":{}|<>]/'],
            'tipousuario'   => 'required|in:administrador,cliente',
            'estado'        => 'required|in:activo,inactivo',
            'nombre'        => 'required|string|max:35',
            'paterno'       => 'required|string|max:35',
            'materno'       => 'nullable|string|max:35',
            'fecha'         => 'nullable|date',
            'ci'            => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
        ], [
            // Mensajes en español para la regla de password (igual que tu msg=error_pass).
            'password.regex' => 'El password debe tener al menos una mayúscula y un símbolo.',
        ]);

        // Encriptar antes de guardar. bcrypt genera 60 chars → por eso la migración
        // amplía `password` de VARCHAR(35) a VARCHAR(255).
        $datos['password'] = Hash::make($datos['password']);

        // create() usa $fillable del modelo para inserción segura.
        Usuario::create($datos);

        // Redirige al listado con mensaje flash (tu ?msg=guardado).
        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado correctamente.');
    }

    /**
     * 4. VER UNO — GET /usuarios/{usuario}
     * Laravel inyecta automáticamente el modelo por su PK (Route Model Binding).
     * {usuario} en la URL → busca WHERE id_usuario = valor. 404 si no existe.
     */
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * 5. FORMULARIO EDITAR — GET /usuarios/{usuario}/edit
     * Muestra el form precargado (tu vista/usuarioeditar.php).
     */
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * 6. ACTUALIZAR — PUT /usuarios/{usuario}
     * Si el password viene vacío, se conserva el actual (igual que tu actualizar()).
     */
    public function update(Request $request, Usuario $usuario)
    {
        // `sometimes` = el password es opcional al editar.
        // unique ignora la fila actual para no chocar consigo misma.
        $datos = $request->validate([
            'nombreusuario' => 'required|string|max:35|unique:usuarios,nombreusuario,' . $usuario->id_usuario . ',id_usuario',
            'password'      => ['nullable', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[!@#$%^&*(),.?":{}|<>]/'],
            'tipousuario'   => 'required|in:administrador,cliente',
            'estado'        => 'required|in:activo,inactivo',
            'nombre'        => 'required|string|max:35',
            'paterno'       => 'required|string|max:35',
            'materno'       => 'nullable|string|max:35',
            'fecha'         => 'nullable|date',
            'ci'            => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
        ], [
            'password.regex' => 'El password debe tener al menos una mayúscula y un símbolo.',
        ]);

        // Si escribió nuevo password → encriptar. Si lo dejó vacío → no tocar el actual.
        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']); // update() ignorará esta columna
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado correctamente.');
    }

    /**
     * 7. ELIMINAR — DELETE /usuarios/{usuario}
     * ------------------------------------------------------------------
     * Por qué fallaba antes con error 1451:
     *  `cliente.usuario_id` y `administrador.usuario_id` son FK hacia
     *  `usuarios.id_usuario` (ver juvenil.sql). MySQL impide borrar al padre
     *  si aún existen hijos. Hay que borrar primero al hijo y luego al padre,
     *  igual que hace tu `usuarioControlador.php` + `eliminarConHerencia()`.
     *
     * Si prefieres borrado lógico (recomendado), cambia todo el cuerpo por:
     *  $usuario->update(['estado' => 'inactivo']);
     */
    public function destroy(Usuario $usuario)
    {
        try {
            // Transacción = todo o nada: si algo falla se revierte solo.
            DB::transaction(function () use ($usuario) {
                // 1. Borrar hijo en `cliente` (si existe). Equivale a tu Cliente::eliminarClienteFisico().
                DB::table('cliente')->where('usuario_id', $usuario->id_usuario)->delete();
                // 2. Borrar hijo en `administrador` (si existe). Equivale a Administrador::eliminarConHerencia().
                DB::table('administrador')->where('usuario_id', $usuario->id_usuario)->delete();
                // 3. Recién ahora borrar el padre en `usuarios`.
                $usuario->delete();
            });

            return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado correctamente.');
        } catch (QueryException $e) {
            // 1451 = otra tabla (ej. ventas) aún referencia a este usuario/cliente.
            // En vez de pantalla 500, volvemos al listado con mensaje claro.
            return redirect()->route('usuarios.index')
                ->withErrors('No se puede eliminar: el usuario tiene registros relacionados (cliente/ventas). Desactívalo con estado=inactivo.');
        }
    }
}
