@extends('layouts.app')
@section('titulo', 'Mis citas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-calendar2-heart me-2"></i>Mis citas</h3>
    <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Agendar cita</a>
</div>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Fecha</th><th>Hora</th><th>Doctor</th><th>Especialidad</th><th>Motivo</th><th>Estado</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($citas as $cita)
        <tr>
            <td>{{ $cita->fecha->format('d/m/Y') }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
            <td>Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }}</td>
            <td>{{ $cita->doctor->especialidad }}</td>
            <td>{{ $cita->motivo }}</td>
            <td><x-estado-badge :estado="$cita->estado" /></td>
            <td>
                @if($cita->puedeModificarse())
                    <a href="{{ route('paciente.citas.reprogramar', $cita) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-repeat"></i> Reprogramar
                    </a>
                    <form action="{{ route('paciente.citas.cancelar', $cita) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Cancelar esta cita? Esta acción no se puede deshacer.')">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Cancelar</button>
                    </form>
                @elseif(in_array($cita->estado, ['pendiente', 'confirmada']))
                    <span class="text-muted small">Faltan menos de 24h, no se puede modificar</span>
                @else
                    <span class="text-muted small">Sin acciones</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-calendar-x me-1"></i>Todavía no tienes citas agendadas.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $citas->links() }}
@endsection
