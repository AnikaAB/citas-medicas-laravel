@extends('layouts.app')
@section('titulo', 'Editar paciente')
@section('contenido')
<h3>Editar paciente</h3>
<form method="POST" action="{{ route('pacientes.update', $paciente) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="{{ $paciente->nombre }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="{{ $paciente->apellido }}"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Cedula</label><input name="cedula" class="form-control" required value="{{ $paciente->cedula }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required value="{{ $paciente->telefono }}"></div>
    </div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ $paciente->email }}"></div>
    <div class="mb-3"><label class="form-label">Fecha de nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required value="{{ $paciente->fecha_nacimiento->format('Y-m-d') }}"></div>
    <div class="mb-3"><label class="form-label">Direccion</label><input name="direccion" class="form-control" value="{{ $paciente->direccion }}"></div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
