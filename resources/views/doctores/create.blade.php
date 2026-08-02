@extends('layouts.app')
@section('titulo', 'Nuevo doctor')
@section('contenido')
<h3>Registrar doctor</h3>
<form method="POST" action="{{ route('doctores.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="{{ old('nombre') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="{{ old('apellido') }}"></div>
    </div>
    <div class="mb-3"><label class="form-label">Especialidad</label><input name="especialidad" class="form-control" required value="{{ old('especialidad') }}"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required pattern="[0-9]{7,10}" title="Solo numeros, entre 7 y 10 digitos" value="{{ old('telefono') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Horario inicio</label><input type="time" name="horario_inicio" class="form-control" required value="08:00"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Horario fin</label><input type="time" name="horario_fin" class="form-control" required value="17:00"></div>
    </div>
    <div class="mb-3"><label class="form-label">Contraseña (para el login del doctor)</label><input type="password" name="password" class="form-control" required minlength="8"></div>
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('doctores.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection