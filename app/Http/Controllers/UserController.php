<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion basica de usuarios, exclusiva para el rol admin.
 *
 * Alcance deliberadamente reducido: el admin puede ver el listado de
 * todos los usuarios del sistema (cualquier rol) y activar/desactivar
 * su acceso. No permite crear usuarios sueltos ni cambiar el rol desde
 * aqui: los doctores y pacientes se siguen creando desde sus propios
 * modulos (DoctorController / PacienteController / registro publico),
 * que ya validan y crean su perfil asociado correctamente.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('rol') && in_array($request->input('rol'), [
            User::ROL_ADMIN, User::ROL_RECEPCIONISTA, User::ROL_DOCTOR, User::ROL_PACIENTE,
        ], true)) {
            $query->where('rol', $request->input('rol'));
        }

        if ($request->filled('buscar')) {
            $texto = $request->input('buscar');
            $query->where(function ($q) use ($texto) {
                $q->where('name', 'like', "%{$texto}%")
                  ->orWhere('email', 'like', "%{$texto}%");
            });
        }

        $usuarios = $query->orderBy('rol')->orderBy('name')->paginate(15)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    public function alternarEstado(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')->withErrors([
                'usuario' => 'No puedes desactivar tu propia cuenta.',
            ]);
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        $mensaje = $usuario->activo
            ? 'Usuario activado correctamente.'
            : 'Usuario desactivado correctamente.';

        return redirect()->route('usuarios.index')->with('exito', $mensaje);
    }
}
