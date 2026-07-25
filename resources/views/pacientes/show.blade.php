@extends('layouts.app')
@section('titulo', 'Detalle paciente')
@section('contenido')
<h3>{{ $paciente->nombre }} {{ $paciente->apellido }}</h3>
<table class="table table-hover align-middle">
    <tr><th>Cedula</th><td>{{ $paciente->cedula }}</td></tr>
    <tr><th>Telefono</th><td>{{ $paciente->telefono }}</td></tr>
    <tr><th>Email</th><td>{{ $paciente->email }}</td></tr>
    <tr><th>Fecha nacimiento</th><td>{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</td></tr>
    <tr><th>Direccion</th><td>{{ $paciente->direccion }}</td></tr>
</table>
<h5>Historial de citas</h5>
<table class="table table-hover align-middle">
    <thead><tr><th>Fecha</th><th>Doctor</th><th>Estado</th></tr></thead>
    <tbody>
        @forelse($paciente->citas as $cita)
        <tr><td>{{ $cita->fecha->format('d/m/Y') }}</td><td>Dr. {{ $cita->doctor->nombre }}</td><td>{{ $cita->estado }}</td></tr>
        @empty
        <tr><td colspan="3" class="text-center">Sin citas registradas.</td></tr>
        @endforelse
    </tbody>
</table>
<a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Volver</a>
@endsection
