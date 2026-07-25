@extends('layouts.app')
@section('titulo', 'Pacientes')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-people me-2"></i>Pacientes</h3>
    <a href="{{ route('pacientes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo paciente</a>
</div>
<form class="mb-3" method="GET">
    <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o cedula" value="{{ request('buscar') }}">
</form>
<table class="table table-hover align-middle">
    <thead><tr><th>#</th><th>Nombre</th><th>Cedula</th><th>Telefono</th><th>Email</th><th>Acciones</th></tr></thead>
    <tbody>
        @forelse($pacientes as $paciente)
        <tr>
            <td>{{ $paciente->id }}</td>
            <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
            <td>{{ $paciente->cedula }}</td>
            <td>{{ $paciente->telefono }}</td>
            <td>{{ $paciente->email }}</td>
            <td>
                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar paciente?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No hay pacientes registrados.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $pacientes->links() }}
@endsection
