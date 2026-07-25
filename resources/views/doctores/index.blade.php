@extends('layouts.app')
@section('titulo', 'Doctores')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-clipboard2-pulse me-2"></i>Doctores</h3>
    <a href="{{ route('doctores.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo doctor</a>
</div>
<table class="table table-hover align-middle">
    <thead><tr><th>#</th><th>Nombre</th><th>Especialidad</th><th>Telefono</th><th>Email</th><th>Horario</th><th>Acciones</th></tr></thead>
    <tbody>
        @forelse($doctores as $doctor)
        <tr>
            <td>{{ $doctor->id }}</td>
            <td>Dr. {{ $doctor->nombre }} {{ $doctor->apellido }}</td>
            <td>{{ $doctor->especialidad }}</td>
            <td>{{ $doctor->telefono }}</td>
            <td>{{ $doctor->email }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($doctor->horario_inicio)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($doctor->horario_fin)->format('H:i') }}</td>
            <td>
                <a href="{{ route('doctores.show', $doctor) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('doctores.edit', $doctor) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('doctores.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar doctor?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No hay doctores registrados.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $doctores->links() }}
@endsection
