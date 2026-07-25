@extends('layouts.auth')
@section('titulo', 'Nueva contraseña')
@section('contenido')
    <h3>Crea tu nueva contraseña</h3>
    <p class="subtitle">Elige una contraseña segura de al menos 8 caracteres.</p>

    <form method="POST" action="{{ route('password.restablecer.guardar') }}">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">
        <input type="hidden" name="codigo" value="{{ old('codigo', $codigo) }}">

        <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
            </div>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-check-circle me-1"></i> Guardar nueva contraseña
        </button>
    </form>
@endsection
