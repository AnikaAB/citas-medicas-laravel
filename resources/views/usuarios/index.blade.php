@extends('layouts.app')
@section('titulo', 'Usuarios')
@section('contenido')

<h3><i class="bi bi-person-gear me-2"></i>Gestion de usuarios</h3>
<p class="text-muted">Activa o desactiva el acceso al sistema. Un usuario desactivado no puede iniciar sesion, pero conserva su historial.</p>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="rol" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los roles</option>
            <option value="admin" @selected(request('rol') === 'admin')>Admin</option>
            <option value="recepcionista" @selected(request('rol') === 'recepcionista')>Recepcionista</option>
            <option value="doctor" @selected(request('rol') === 'doctor')>Doctor</option>
            <option value="paciente" @selected(request('rol') === 'paciente')>Paciente</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('buscar') }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Buscar</button>
    </div>
</form>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th class="text-end">Accion</th>
        </tr>
    </thead>
    <tbody>
        @forelse($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td><span class="badge-estado" style="background: rgba(79,124,255,0.12); border: 1px solid rgba(79,124,255,0.3); color: #4f7cff;">{{ ucfirst($usuario->rol) }}</span></td>
            <td>
                @if($usuario->activo)
                    <span class="badge-estado" style="background: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.4); color: #059669;"><i class="bi bi-check-circle"></i> Activo</span>
                @else
                    <span class="badge-estado" style="background: rgba(244,63,94,0.15); border: 1px solid rgba(244,63,94,0.4); color: #be123c;"><i class="bi bi-x-circle"></i> Inactivo</span>

                @endif
            </td>
            <td class="text-end">
                @if($usuario->id !== auth()->id())
                    <form method="POST" action="{{ route('usuarios.alternarEstado', $usuario) }}" class="d-inline">
                        @csrf @method('PATCH')
                        @if($usuario->activo)
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desactivar a {{ $usuario->name }}? No podra iniciar sesion.')">Desactivar</button>

                        @else
                            <button class="btn btn-sm btn-outline-success">Activar</button>
                        @endif
                    </form>
                @else
                    <span class="text-muted small">Tu cuenta</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No se encontraron usuarios.</td></tr>

        @endforelse
    </tbody>
</table>

{{ $usuarios->links() }}
@endsection
