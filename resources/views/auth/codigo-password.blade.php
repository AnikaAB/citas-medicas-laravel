@extends('layouts.auth')
@section('titulo', 'Verificar codigo')
@section('contenido')
    <h3>Verifica tu codigo</h3>
    <p class="subtitle">Escribe el codigo de 6 digitos que generamos para tu correo.</p>

    @if(session('codigo_demo'))
        <div class="alert alert-info">
            <strong>Modo demostracion:</strong> como el sistema aun no tiene correo configurado,
            este es tu codigo de verificacion: <strong>{{ session('codigo_demo') }}</strong>
            <br>(en un ambiente real, este codigo llegaria a tu correo).
        </div>
    @endif

    <form method="POST" action="{{ route('password.verificar') }}">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">
        <div class="mb-3">
            <label class="form-label">Correo electronico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" value="{{ old('email', $email) }}" disabled>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Codigo de verificacion</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input type="text" name="codigo" class="form-control" maxlength="6" inputmode="numeric" required autofocus placeholder="123456">
            </div>
        </div>
        <button class="btn btn-gradient w-100 mb-3">
            <i class="bi bi-check2-circle me-1"></i> Verificar codigo
        </button>
        <p class="text-center switch-link mb-0">
            <a href="{{ route('password.olvide') }}">¿No te llego? Solicitar otro codigo</a>
        </p>
    </form>
@endsection