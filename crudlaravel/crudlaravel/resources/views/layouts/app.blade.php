<!DOCTYPE html>
{{-- Layout base con Bootstrap 5 (igual librería que tu proyecto actual en vista/layout).
     Todas las vistas del CRUD heredan de aquí con @extends('layouts.app'). --}}
<html lang="es">
<head>
    <meta charset="UTF-8">
    {{-- Necesario para que Bootstrap sea responsive en móvil --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Título dinámico: cada vista define @section('titulo') --}}
    <title>@yield('titulo', 'CRUD Usuarios') - crudlaravel</title>
    {{-- Bootstrap 5 por CDN: sin instalar nada, misma apariencia que tu proyecto --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

{{-- Barra superior: reemplaza tu layout/header actual --}}
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        {{-- route('usuarios.index') genera la URL /usuarios automáticamente --}}
        <a class="navbar-brand" href="{{ route('usuarios.index') }}">Almacén Juvenil · CRUD Usuarios (Laravel)</a>
        <a class="btn btn-sm btn-success" href="{{ route('usuarios.create') }}">+ Nuevo usuario</a>
    </div>
</nav>

<div class="container">
    {{-- Mensaje flash "ok" que envía el controlador con ->with('ok', ...) --}}
    @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    {{-- Aquí se injecta el contenido de cada vista (index/create/edit/show) --}}
    @yield('contenido')
</div>

</body>
</html>
