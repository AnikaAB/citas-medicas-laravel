@extends('layouts.app')
@section('titulo', 'Editar recepcionista')
@section('contenido')
<h3><i class="bi bi-person-badge me-2"></i>Editar recepcionista</h3>

<form method="POST" action="{{ route('usuarios.recepcionistas.update', $usuario) }}" style="max-width: 480px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nueva contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiarla">
    </div>
    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Guardar cambios</button>
    <a href="{{ route('usuarios.index', ['rol' => 'recepcionista']) }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
