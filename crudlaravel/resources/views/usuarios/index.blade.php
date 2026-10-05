{{-- Vista LISTADO: GET /usuarios → UsuarioController@index --}}
{{-- Equivale a tu vista/usuariolista.php --}}
@extends('layouts.app')
@section('titulo', 'Lista de usuarios')

@section('contenido')
{{-- Errores de borrado con FK (vienen del catch QueryException con withErrors) --}}
@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Usuarios ({{ $usuarios->total() }})</strong>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">Crear usuario</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Usuario</th><th>Nombre</th><th>Tipo</th><th>Estado</th><th>Email</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- $usuarios viene paginado desde el controlador --}}
                @forelse ($usuarios as $u)
                <tr>
                    <td>{{ $u->id_usuario }}</td>
                    <td>{{ $u->nombreusuario }}</td>
                    <td>{{ $u->nombre }} {{ $u->paterno }}</td>
                    <td><span class="badge bg-info">{{ $u->tipousuario }}</span></td>
                    <td>
                        <span class="badge {{ $u->estado === 'activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $u->estado }}
                        </span>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td class="text-nowrap">
                        {{-- route() genera URLs con el ID automáticamente --}}
                        <a href="{{ route('usuarios.show', $u) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                        <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-warning">Editar</a>
                        {{-- DELETE necesita formulario + @method('DELETE') porque HTML solo soporta GET/POST --}}
                        <form action="{{ route('usuarios.destroy', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar a {{ $u->nombreusuario }}?')">
                            @csrf {{-- token anti-CSRF: equivale a tu exigirCSRF() --}}
                            @method('DELETE') {{-- engaña al navegador para enviar DELETE --}}
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center p-4">Sin usuarios. Crea el primero.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Botones de paginación (10 por página) --}}
    <div class="card-footer">{{ $usuarios->links() }}</div>
</div>
@endsection
