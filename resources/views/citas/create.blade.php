@extends('layouts.app')
@section('titulo', 'Nueva cita')
@section('contenido')
<h3>Registrar nueva cita</h3>
<form method="POST" action="{{ route('citas.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Paciente</label>
        <select name="paciente_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellido }} - {{ $paciente->cedula }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach($doctores as $doctor)
                <option value="{{ $doctor->id }}">Dr. {{ $doctor->nombre }} {{ $doctor->apellido }} ({{ $doctor->especialidad }})</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="{{ old('fecha') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" required value="{{ old('hora') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Motivo</label>
        <input type="text" name="motivo" class="form-control" required value="{{ old('motivo') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmada">Confirmada</option>
            <option value="cancelada">Cancelada</option>
            <option value="atendida">Atendida</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
