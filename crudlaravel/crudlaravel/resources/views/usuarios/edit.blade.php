{{-- Vista EDITAR: GET /usuarios/{id}/edit → edit() / PUT → update() --}}
{{-- Equivale a tu vista/usuarioeditar.php. $usuario ya viene cargado por Route Model Binding --}}
@extends('layouts.app')
@section('titulo', 'Editar usuario ' . $usuario->id_usuario)

@section('contenido')
<div class="card mx-auto" style="max-width:700px">
    <div class="card-header"><strong>Editar #{{ $usuario->id_usuario }} — {{ $usuario->nombreusuario }}</strong></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        {{-- @method('PUT') porque el form HTML solo hace POST --}}
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de usuario *</label>
                    <input name="nombreusuario" value="{{ old('nombreusuario', $usuario->nombreusuario) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    {{-- Vacío = conservar password actual (lógica en update() con unset) --}}
                    <label class="form-label">Password (vacío = no cambiar)</label>
                    <input name="password" type="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select name="tipousuario" class="form-select">
                        <option value="administrador" @selected(old('tipousuario', $usuario->tipousuario)=='administrador')>administrador</option>
                        <option value="cliente" @selected(old('tipousuario', $usuario->tipousuario)=='cliente')>cliente</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select">
                        <option value="activo" @selected(old('estado', $usuario->estado)=='activo')>activo</option>
                        <option value="inactivo" @selected(old('estado', $usuario->estado)=='inactivo')>inactivo</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Nombre *</label><input name="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Paterno *</label><input name="paterno" value="{{ old('paterno', $usuario->paterno) }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Materno</label><input name="materno" value="{{ old('materno', $usuario->materno) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Fecha nac.</label><input name="fecha" type="date" value="{{ old('fecha', $usuario->fecha) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">CI</label><input name="ci" value="{{ old('ci', $usuario->ci) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Email</label><input name="email" type="email" value="{{ old('email', $usuario->email) }}" class="form-control"></div>
            </div>
            <div class="mt-3">
                <button class="btn btn-warning">Actualizar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection
