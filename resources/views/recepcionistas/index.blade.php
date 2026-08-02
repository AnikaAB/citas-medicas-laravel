@extends('layouts.app')
@section('titulo', 'Recepcionistas')
@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-person-badge me-2"></i>Recepcionistas</h3>
    <a href="{{ route('recepcionistas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva recepcionista</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('buscar') }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Buscar</button>
    </div>
</form>

<table class="table table-hover align-middle">
    <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
        @forelse($recepcionistas as $recepcionista)
        <tr>
            <td>{{ $recepcionista->id }}</td>
            <td>{{ $recepcionista->name }}</td>
            <td>{{ $recepcionista->email }}</td>
            <td>
                @if($recepcionista->activo)
                    <span class="badge-estado" style="background: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.4); color: #059669;"><i class="bi bi-check-circle"></i> Activa</span>
                @else
                    <span class="badge-estado" style="background: rgba(244,63,94,0.15); border: 1px solid rgba(244,63,94,0.4); color: #be123c;"><i class="bi bi-x-circle"></i> Inactiva</span>
                @endif
            </td>
            <td>
                <a href="{{ route('recepcionistas.edit', $recepcionista) }}" class="btn btn-sm btn-warning">Editar</a>
                @if($recepcionista->activo)
                    <form action="{{ route('recepcionistas.destroy', $recepcionista) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar a {{ $recepcionista->name }}? No podrá iniciar sesión, pero su historial de citas registradas se conserva.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Desactivar</button>
                    </form>
                @else
                    <form action="{{ route('recepcionistas.activar', $recepcionista) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success">Activar</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No hay recepcionistas registradas.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $recepcionistas->links() }}
@endsection
