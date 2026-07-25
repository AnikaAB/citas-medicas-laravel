@extends('layouts.app')
@section('titulo', 'Detalle doctor')
@section('contenido')
<h3>Dr. {{ $doctor->nombre }} {{ $doctor->apellido }}</h3>
<table class="table table-hover align-middle">
    <tr><th>Especialidad</th><td>{{ $doctor->especialidad }}</td></tr>
    <tr><th>Telefono</th><td>{{ $doctor->telefono }}</td></tr>
    <tr><th>Email</th><td>{{ $doctor->email }}</td></tr>
    <tr><th>Horario</th><td>{{ \Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i') }}</td></tr>
</table>
<h5>Citas asignadas</h5>
<table class="table table-hover align-middle">
    <thead><tr><th>Fecha</th><th>Paciente</th><th>Estado</th></tr></thead>
    <tbody>
        @forelse($doctor->citas as $cita)
        <tr><td>{{ $cita->fecha->format('d/m/Y') }}</td><td>{{ $cita->paciente->nombre }}</td><td>{{ $cita->estado }}</td></tr>
        @empty
        <tr><td colspan="3" class="text-center">Sin citas asignadas.</td></tr>
        @endforelse
    </tbody>
</table>
<a href="{{ route('doctores.index') }}" class="btn btn-secondary">Volver</a>
@endsection
