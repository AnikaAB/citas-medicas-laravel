@extends('layouts.app')
@section('titulo', 'Citas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-calendar2-week me-2"></i>Gestión de Citas</h3>
    @if(auth()->user()->esAdmin() || auth()->user()->esRecepcionista())
        <a href="{{ route('citas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva cita</a>
    @endif
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
        <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            @foreach(['pendiente','confirmada','cancelada','atendida'] as $estado)
                <option value="{{ $estado }}" @selected(request('estado')==$estado)>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Filtrar</button>
    </div>
</form>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>#</th><th>Paciente</th><th>Doctor</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($citas as $cita)
        <tr>
            <td>{{ $cita->id }}</td>
            <td>{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</td>
            <td>Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }}</td>
            <td>{{ $cita->fecha->format('d/m/Y') }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
            <td><x-estado-badge :estado="$cita->estado" /></td>
            <td>
                @if(auth()->user()->esAdmin() || auth()->user()->esRecepcionista())
                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta cita?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                @else
                    <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No hay citas registradas.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $citas->links() }}
@endsection
