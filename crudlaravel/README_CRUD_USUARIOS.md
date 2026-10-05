# CRUD Laravel — Tabla `usuarios` (base `juvenil`)

Proyecto nuevo en `C:\xampp\htdocs\crudlaravel`.
Tu proyecto anterior `almacenjuvenil` NO fue modificado.

## Qué se instaló
- Laravel 12 (PHP 8.2 OK). Se habilitó `extension=zip` en `C:\xampp\php\php.ini` (requisito Composer).
- Conexión MySQL XAMPP en `.env`:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=juvenil
  DB_USERNAME=root
  DB_PASSWORD=
  SESSION_DRIVER=file  CACHE_STORE=file  QUEUE_CONNECTION=sync
  ```

## Archivos del CRUD (todos comentados en español)
1. `app/Models/Usuario.php` — Eloquent mapeado a `usuarios.id_usuario`, `timestamps=false`, `$fillable` con las 10 columnas.
2. `app/Http/Controllers/UsuarioController.php` — 7 métodos resource:
   index/create/store/show/edit/update/destroy. Valida igual que tu `validarPasswordServer()`
   (min 8 + mayúscula + símbolo) y usa `Hash::make()` en vez de `password_hash()`.
3. `routes/web.php` — `Route::resource('usuarios', ...)` + `/` redirige a `/usuarios`.
4. `resources/views/layouts/app.blade.php` — layout Bootstrap 5.
   `resources/views/usuarios/{index,create,edit,show}.blade.php` — las 4 pantallas.
5. `database/migrations/2026_10_04_000001_ajustar_password_usuarios_table.php`
   — amplía `password VARCHAR(35)→255` SIN borrar datos (bcrypt necesita 60 chars).
   Ya ejecutada: verificado con `DESCRIBE juvenil.usuarios`.

## Cómo usar
```powershell
# 1. Asegura MySQL corriendo (XAMPP Control Panel → Start MySQL)
# 2. Entra al proyecto
cd C:\xampp\htdocs\crudlaravel
# 3. Corre el servidor
php artisan serve --port=8001
# 4. Abre en navegador:
http://127.0.0.1:8001/usuarios
```

Rutas generadas (`php artisan route:list --name=usuarios`):
GET /usuarios, GET /usuarios/create, POST /usuarios,
GET /usuarios/{id}, GET /usuarios/{id}/edit, PUT /usuarios/{id}, DELETE /usuarios/{id}

## Notas vs tu proyecto actual
- Tu `UsuarioClase::listarUsuarios()` con JOINs a empleado/cargo se simplificó a
  `Usuario::orderBy()->paginate(10)`. Si quieres los JOINs, dímelo y los agrego.
- Tu tabla viva tiene columna extra `id_empleado` (no estaba en juvenil.sql). El CRUD la ignora (queda NULL).
- Borrado es físico (`delete()`). Para borrado lógico cambia `destroy()` por
  `$usuario->update(['estado'=>'inactivo'])` como tu `desactivar()`.
