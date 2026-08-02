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
        @if(auth()->user()->esAdmin())
            {{-- El admin no gestiona el flujo clinico de la cita: se muestra
                 como texto informativo (el backend tambien lo ignora si se
                 manipula el form). No se envia campo "estado" para admin. --}}
            <div><x-estado-badge :estado="$cita->estado" /></div>
            <div class="form-text">Como administrador no puedes cambiar el estado clínico de la cita.</div>
        @else
            {{-- Solo se muestran las transiciones validas desde el estado
                 actual (maquina de estados definida en Cita::TRANSICIONES).
                 Esto es solo UX: el servidor vuelve a validar la transicion. --}}
            <select name="estado" class="form-select" required>
                <option value="{{ $cita->estado }}" selected>{{ ucfirst($cita->estado) }} (actual)</option>
                @foreach(\App\Models\Cita::TRANSICIONES[$cita->estado] ?? [] as $siguiente)
                    <option value="{{ $siguiente }}">{{ ucfirst($siguiente) }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control">{{ $cita->observaciones }}</textarea>
    </div>
    <button class="btn btn-primary">Actualizar</button>
    <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
