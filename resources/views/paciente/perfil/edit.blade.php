@extends('layouts.app')
@section('titulo', 'Mi perfil')
@section('contenido')
<h3><i class="bi bi-person-vcard me-2"></i>Mi perfil</h3>

<form method="POST" action="{{ route('perfil.update') }}">
    @csrf @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" required maxlength="100" value="{{ old('nombre', $paciente->nombre) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Apellido</label>
            <input name="apellido" class="form-control" required maxlength="100" value="{{ old('apellido', $paciente->apellido) }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" required pattern="[0-9]{7,10}" title="Solo numeros, entre 7 y 10 digitos" maxlength="10" value="{{ old('telefono', $paciente->telefono) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input name="direccion" class="form-control" maxlength="255" value="{{ old('direccion', $paciente->direccion) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Cédula</label>
        <input class="form-control" value="{{ $paciente->cedula }}" disabled>
        <div class="form-text">La cédula no se puede modificar desde aquí. Si necesitas corregirla, contacta a recepción.</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Correo</label>
        <input class="form-control" value="{{ $paciente->email }}" disabled>
    </div>

    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar cambios</button>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-x-lg me-1"></i>Cancelar</a>
</form>
@endsection
