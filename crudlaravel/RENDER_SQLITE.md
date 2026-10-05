# Despliegue en Render con SQLite

Guía para publicar el CRUD `crudlaravel` (Laravel 12) en Render utilizando SQLite, sin base de datos externa.

## 1. Variables de entorno

Configure estas variables en el panel de Render (sección Environment del Web Service):

| Variable         | Valor                                           | Descripción                                              |
|------------------|-------------------------------------------------|----------------------------------------------------------|
| APP_NAME         | crudlaravel                                     | Nombre de la aplicación.                                 |
| APP_ENV          | production                                      | Entorno de ejecución.                                    |
| APP_DEBUG        | false                                           | Desactiva el modo de depuración en producción.           |
| APP_KEY          | (generar local, ver nota)                       | Clave de cifrado de Laravel.                             |
| APP_URL          | https://<su-servicio>.onrender.com              | URL pública asignada por Render.                         |
| DB_CONNECTION    | sqlite                                          | Motor de base de datos.                                  |
| DB_DATABASE      | /var/www/html/database/database.sqlite          | Ruta del archivo SQLite dentro del contenedor.           |
| LOG_CHANNEL      | stderr                                          | Envía los registros a la salida estándar para verlos en Render. |
| SESSION_DRIVER   | file                                            | Almacena sesiones en archivos.                           |
| CACHE_STORE      | file                                            | Almacena caché en archivos.                              |
| QUEUE_CONNECTION | sync                                            | Ejecuta colas de forma sincrónica.                       |

Nota sobre APP_KEY: genere el valor en su equipo con el comando `php artisan key:generate --show` y copie el resultado en Render. No utilice un valor inventado ni comparta la clave en el repositorio.

## 2. Pasos en el panel de Render

1. Ingrese a https://dashboard.render.com y seleccione New > Web Service.
2. Conecte el repositorio y selecciónelo.
3. En Root Directory escriba `crudlaravel` (el Dockerfile se encuentra en esa carpeta).
4. En Environment seleccione Docker (Render detecta el Dockerfile automáticamente).
5. Elija el plan Free para la demostración.
6. Agregue las variables de entorno de la tabla anterior.
7. Pulse Deploy y espere a que finalice la construcción.
8. Al iniciar, el contenedor ejecuta `php artisan migrate --force` y `php artisan db:seed --force`, por lo que la tabla `usuarios` se crea y se cargan 3 usuarios de demostración.
9. Verifique el despliegue visitando la URL pública: el listado de usuarios debe cargarse y permitir iniciar sesión con `admin01` / `Admin123!`.

El contenedor expone el puerto 80, que es el puerto que Render utiliza por defecto para servicios Docker.

## 3. Advertencias

- El plan gratuito se suspende después de aproximadamente 15 minutos sin actividad. La primera solicitud posterior puede tardar hasta un minuto en responder mientras el servicio se reactiva.
- El sistema de archivos de Render es efímero: los datos guardados en SQLite se pierden en cada despliegue o reinicio. Es adecuado para una demostración, no para producción con datos permanentes.
- Como alternativa, Render ofrece PostgreSQL, pero la capa gratuita expira a los 30 días. Si necesita persistencia, migre DB_CONNECTION a pgsql con las credenciales correspondientes.

## 4. Usuarios de demostración

| Usuario     | Tipo          | Estado   | Contraseña    |
|-------------|---------------|----------|---------------|
| admin01     | administrador | activo   | Admin123!     |
| cliente01   | cliente       | activo   | Cliente123!   |
| invitado01  | cliente       | inactivo | Invitado123!  |
