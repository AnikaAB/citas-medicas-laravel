@extends('layouts.app')
@section('titulo', 'Detalle de cita')
@section('contenido')
<h3><i class="bi bi-calendar2-check me-2"></i>Cita #{{ $cita->id }}</h3>
<table class="table table-borderless">
    <tr><th style="width: 220px;">Paciente</th><td>{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</td></tr>
    <tr><th>Doctor</th><td>Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }}</td></tr>
    <tr><th>Fecha</th><td>{{ $cita->fecha->format('d/m/Y') }}</td></tr>
    <tr><th>Hora</th><td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td></tr>
    <tr><th>Motivo</th><td>{{ $cita->motivo }}</td></tr>
    <tr><th>Estado</th><td><x-estado-badge :estado="$cita->estado" /></td></tr>
    <tr><th>Observaciones</th><td>{{ $cita->observaciones ?? '—' }}</td></tr>
    <tr><th>Registrada por</th><td>{{ $cita->creadoPor->name ?? '—' }}</td></tr>
</table>
<a href="{{ route('citas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
@endsection
