{{-- Vista DETALLE: GET /usuarios/{id} → UsuarioController@show --}}
@extends('layouts.app')
@section('titulo', 'Usuario ' . $usuario->id_usuario)

@section('contenido')
<div class="card mx-auto" style="max-width:600px">
    <div class="card-header"><strong>#{{ $usuario->id_usuario }} — {{ $usuario->nombreusuario }}</strong></div>
    {{-- Lista cada columna de la tabla usuarios --}}
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>Nombre:</b> {{ $usuario->nombre }} {{ $usuario->paterno }} {{ $usuario->materno }}</li>
        <li class="list-group-item"><b>Tipo:</b> {{ $usuario->tipousuario }} | <b>Estado:</b> {{ $usuario->estado }}</li>
        <li class="list-group-item"><b>CI:</b> {{ $usuario->ci ?? '—' }} | <b>Fecha:</b> {{ $usuario->fecha ?? '—' }}</li>
        <li class="list-group-item"><b>Email:</b> {{ $usuario->email ?? '—' }}</li>
    </ul>
    <div class="card-body">
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
