@extends('layouts.app')
@section('titulo', 'Nueva recepcionista')
@section('contenido')
<h3><i class="bi bi-person-badge me-2"></i>Registrar recepcionista</h3>
<p class="text-muted">Se creará con acceso inmediato al sistema (cuenta activa).</p>

<form method="POST" action="{{ route('recepcionistas.store') }}" style="max-width: 480px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required placeholder="Mínimo 8 caracteres">
    </div>
    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Registrar recepcionista</button>
    <a href="{{ route('recepcionistas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
