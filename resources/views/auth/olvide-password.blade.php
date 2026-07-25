@extends('layouts.auth')
@section('titulo', 'Recuperar contraseña')
@section('contenido')
    <h3>¿Olvidaste tu contraseña?</h3>
    <p class="subtitle">Escribe tu correo y te daremos un codigo de verificacion para restablecerla.</p>

    <form method="POST" action="{{ route('password.enviar') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Correo electronico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autofocus placeholder="tucorreo@ejemplo.com">
            </div>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-send me-1"></i> Enviar codigo
        </button>
        <p class="text-center switch-link mb-0">
            <a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Volver a iniciar sesion</a>
        </p>
    </form>
@endsection