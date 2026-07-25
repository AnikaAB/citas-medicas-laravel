@extends('layouts.app')
@section('titulo', 'Editar doctor')
@section('contenido')
<h3>Editar doctor</h3>
<form method="POST" action="{{ route('doctores.update', $doctor) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required value="{{ $doctor->nombre }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required value="{{ $doctor->apellido }}"></div>
    </div>
    <div class="mb-3"><label class="form-label">Especialidad</label><input name="especialidad" class="form-control" required value="{{ $doctor->especialidad }}"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Telefono</label><input name="telefono" class="form-control" required value="{{ $doctor->telefono }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ $doctor->email }}"></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Horario inicio</label><input type="time" name="horario_inicio" class="form-control" required value="{{ \Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Horario fin</label><input type="time" name="horario_fin" class="form-control" required value="{{ \Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i') }}"></div>
    </div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="{{ route('doctores.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
