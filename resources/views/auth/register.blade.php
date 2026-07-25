@extends('layouts.auth')
@section('titulo', 'Crear cuenta')
@section('contenido')
    <h3>Crea tu cuenta</h3>
    <p class="subtitle">Regístrate como paciente para agendar tus citas médicas</p>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombres</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required autofocus placeholder="Ej. Ana Mishelle">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellidos</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required placeholder="Ej. Vásquez Fariño">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Cédula</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                    <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" required maxlength="15" placeholder="0700000000">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required maxlength="20" placeholder="0990000000">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de nacimiento</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Dirección <span class="text-muted">(opcional)</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Calle, número, ciudad">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="tucorreo@ejemplo.com">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" required placeholder="Mínimo 8 caracteres">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Repite tu contraseña">
                </div>
            </div>
        </div>

        <button class="btn btn-gradient w-100 mb-3 mt-2">
            <i class="bi bi-person-plus me-1"></i> Crear cuenta
        </button>
        <p class="text-center switch-link mb-0">
            ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </form>
@endsection
