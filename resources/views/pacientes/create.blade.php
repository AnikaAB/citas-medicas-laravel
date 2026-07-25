@extends('layouts.app')
@section('titulo', 'Nuevo paciente')
@section('contenido')
<h3>Registrar paciente</h3>
<form method="POST" action="{{ route('pacientes.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="{{ old('nombre') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="{{ old('apellido') }}"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Cedula</label><input name="cedula" class="form-control" required value="{{ old('cedula') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required value="{{ old('telefono') }}"></div>
    </div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}"></div>
    <div class="mb-3"><label class="form-label">Fecha de nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Direccion</label><input name="direccion" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Contraseña (para el login del paciente)</label><input type="password" name="password" class="form-control" required minlength="8"></div>
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
