@extends('layouts.app')
@section('titulo', 'Mis citas')
@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="bi bi-calendar2-heart me-2"></i>Mis citas</h3>
    <a href="{{ route('mis-citas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Agendar nueva cita
    </a>
</div>

<h5 class="mb-3"><i class="bi bi-calendar2-week me-2"></i>Próximas citas</h5>
<div class="table-responsive mb-5">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proximas as $cita)
                <tr>
                    <td>Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }}</td>
                    <td>{{ $cita->doctor->especialidad }}</td>
                    <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
                    <td>{{ $cita->motivo }}</td>
                    <td><x-estado-badge :estado="$cita->estado" /></td>
                    <td>
                        @if($cita->puedeModificarse())
                            <a href="{{ route('mis-citas.reprogramar', $cita) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-repeat"></i> Reprogramar
                            </a>
                            <form action="{{ route('mis-citas.cancelar', $cita) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que quieres cancelar esta cita?')">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">{{ $cita->motivoNoModificable() }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-calendar-x me-1"></i>No tienes citas próximas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Historial</h5>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historial as $cita)
                <tr>
                    <td>Dr. {{ $cita->doctor->nombre }} {{ $cita->doctor->apellido }}</td>
                    <td>{{ $cita->doctor->especialidad }}</td>
                    <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
                    <td>{{ $cita->motivo }}</td>
                    <td><x-estado-badge :estado="$cita->estado" /></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Aun no tienes historial de citas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
