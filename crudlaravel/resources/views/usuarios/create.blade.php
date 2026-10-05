{{-- Vista CREAR: GET /usuarios/create → UsuarioController@create / POST → store --}}
{{-- Equivale a tu vista/usuarioform.php --}}
@extends('layouts.app')
@section('titulo', 'Crear usuario')

@section('contenido')
<div class="card mx-auto" style="max-width:700px">
    <div class="card-header"><strong>Nuevo usuario</strong></div>
    <div class="card-body">
        {{-- Mostrar errores de validación del controlador --}}
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        {{-- action apunta a usuarios.store (POST /usuarios). old() conserva lo escrito si falla validación --}}
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de usuario *</label>
                    <input name="nombreusuario" value="{{ old('nombreusuario') }}" class="form-control" maxlength="35" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password * (min 8, 1 mayúscula, 1 símbolo)</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select name="tipousuario" class="form-select">
                        <option value="administrador" @selected(old('tipousuario')=='administrador')>administrador</option>
                        <option value="cliente" @selected(old('tipousuario', 'cliente')=='cliente')>cliente</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select">
                        <option value="activo" @selected(old('estado','activo')=='activo')>activo</option>
                        <option value="inactivo" @selected(old('estado')=='inactivo')>inactivo</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Nombre *</label><input name="nombre" value="{{ old('nombre') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Paterno *</label><input name="paterno" value="{{ old('paterno') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Materno</label><input name="materno" value="{{ old('materno') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Fecha nac.</label><input name="fecha" type="date" value="{{ old('fecha') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">CI</label><input name="ci" value="{{ old('ci') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Email</label><input name="email" type="email" value="{{ old('email') }}" class="form-control"></div>
            </div>
            <div class="mt-3">
                <button class="btn btn-success">Guardar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection
