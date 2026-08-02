@extends('layouts.app')
@section('titulo', 'Reprogramar cita')
@section('contenido')

<h3 class="mb-1"><i class="bi bi-arrow-repeat me-2"></i>Reprogramar cita</h3>
<p class="text-muted mb-4">Con Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }} — {{ $cita->motivo }}</p>

<form method="POST" action="{{ route('paciente.citas.reprogramar.update', $cita) }}" style="max-width: 480px;">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nueva fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}" min="{{ now()->toDateString() }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nueva hora</label>
        <input type="time" name="hora" class="form-control" value="{{ old('hora', \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i')) }}" required>
        <small class="text-muted">Formato 24 horas. Ej: 14:00 = 2:00 pm.</small>
    </div>

    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-1"></i>
        Al reprogramar, tu cita quedara en estado <strong>pendiente</strong> hasta que recepcion la confirme de nuevo.
    </div>

    <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Guardar nueva fecha</button>
    <a href="{{ route('paciente.citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>

@endsection