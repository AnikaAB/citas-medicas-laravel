@extends('layouts.app')
@section('titulo', 'Agendar cita')
@section('contenido')

<h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Agendar nueva cita</h3>

<form method="POST" action="{{ route('mis-citas.store') }}" style="max-width: 520px;">
    @csrf

    <div class="mb-3">
        <label class="form-label">Doctor</label>
        <select name="doctor_id" class="form-select" required>
            <option value="">Selecciona un doctor</option>
            @foreach($doctores as $doctor)
                <option value="{{ $doctor->id }}" @selected(old('doctor_id')==$doctor->id)>
                    Dr. {{ $doctor->nombre }} {{ $doctor->apellido }} — {{ $doctor->especialidad }}
                    ({{ substr($doctor->horario_inicio,0,5) }} a {{ substr($doctor->horario_fin,0,5) }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" min="{{ now()->toDateString() }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" value="{{ old('hora') }}" required>
            <small class="text-muted">Formato 24 horas. Ej: 14:00 = 2:00 pm.</small>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Motivo de la consulta</label>
        <textarea name="motivo" class="form-control" rows="3" required placeholder="Ej: Dolor de cabeza persistente">{{ old('motivo') }}</textarea>
    </div>

    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-1"></i>
        Tu cita quedara en estado <strong>pendiente</strong> hasta que recepcion la confirme.
    </div>

    <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Agendar cita</button>
    <a href="{{ route('mis-citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>

@endsection