@extends('layouts.app')
@section('titulo', 'Panel principal')
@section('contenido')

@php $usuario = auth()->user(); @endphp

<h3 class="mb-1"><i class="bi bi-speedometer2 me-2"></i>Bienvenido, {{ $usuario->name }}</h3>
<p class="text-muted mb-4">Este es el resumen de tu actividad en el sistema.</p>

@if(in_array($usuario->rol, ['doctor','paciente']))
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-calendar2-week me-2"></i>Tus próximas citas</h5>
        @if($usuario->esPaciente())
            <a href="{{ route('mis-citas.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-calendar2-heart"></i> Gestionar mis citas
            </a>
        @endif
    </div>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>{{ $usuario->esDoctor() ? 'Paciente' : 'Doctor' }}</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($citas as $cita)
            <tr>
                <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($cita->hora)->format('H:i') }}</td>
                <td>{{ $usuario->esDoctor() ? $cita->paciente->nombre.' '.$cita->paciente->apellido : 'Dr. '.$cita->doctor->nombre.' '.$cita->doctor->apellido }}</td>
                <td>{{ $cita->motivo }}</td>
                <td><x-estado-badge :estado="$cita->estado" /></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-calendar-x me-1"></i>No tienes citas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
@else
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #4f7cff, #7b3fe4);">
                <div class="stat-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                <h2>{{ $totalDoctores }}</h2>
                <p>Doctores</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <h2>{{ $totalPacientes }}</h2>
                <p>Pacientes</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="stat-icon"><i class="bi bi-calendar2-week"></i></div>
                <h2>{{ $totalCitas }}</h2>
                <p>Citas totales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                <div class="stat-icon"><i class="bi bi-calendar2-check"></i></div>
                <h2>{{ $citasHoy }}</h2>
                <p>Citas hoy</p>
            </div>
        </div>
    </div>
    <div class="mt-4 p-3" style="background: #FEF3C7; border-radius: 12px; color: #92400E;">
        <i class="bi bi-hourglass-split me-2"></i>
        Citas pendientes por confirmar: <strong>{{ $citasPendientes }}</strong>
    </div>
@endif

@endsection
