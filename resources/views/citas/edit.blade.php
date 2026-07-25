@extends('layouts.app')
@section('titulo', 'Editar cita')
@section('contenido')
<h3>Editar cita #{{ $cita->id }}</h3>
<form method="POST" action="{{ route('citas.update', $cita) }}">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Paciente</label>
        <select name="paciente_id" class="form-select" required>
            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}" @selected($cita->paciente_id==$paciente->id)>{{ $paciente->nombre }} {{ $paciente->apellido }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            @foreach($doctores as $doctor)
                <option value="{{ $doctor->id }}" @selected($cita->doctor_id==$doctor->id)>Dr. {{ $doctor->nombre }} {{ $doctor->apellido }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="{{ $cita->fecha->format('Y-m-d') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" required value="{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Motivo</label>
        <input type="text" name="motivo" class="form-control" required value="{{ $cita->motivo }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            @foreach(['pendiente','confirmada','cancelada','atendida'] as $estado)
                <option value="{{ $estado }}" @selected($cita->estado==$estado)>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control">{{ $cita->observaciones }}</textarea>
    </div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
