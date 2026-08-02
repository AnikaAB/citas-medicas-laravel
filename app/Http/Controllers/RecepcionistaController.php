<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * CRUD exclusivo de recepcionistas, con el mismo nivel de completitud
 * que DoctorController/PacienteController (index, create, store, edit,
 * update, alternarEstado). Vive aparte del modulo generico "Usuarios"
 * (UserController), que sirve para supervisar/activar-desactivar
 * cualquier cuenta del sistema sin importar el rol.
 *
 * Regla de seguridad: crear() y actualizar() SIEMPRE fuerzan
 * rol=recepcionista, sin importar que venga otra cosa en el request,
 * para que este modulo no pueda usarse para crear o ascender a un admin.
 */
class RecepcionistaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('rol', User::ROL_RECEPCIONISTA);

        if ($request->filled('buscar')) {
            $texto = $request->input('buscar');
            $query->where(function ($q) use ($texto) {
                $q->where('name', 'like', "%{$texto}%")
                  ->orWhere('email', 'like', "%{$texto}%");
            });
        }

        $recepcionistas = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('recepcionistas.index', compact('recepcionistas'));
    }

    public function create()
    {
        return view('recepcionistas.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => User::ROL_RECEPCIONISTA,
            'activo' => true,
        ]);

        return redirect()->route('recepcionistas.index')->with('exito', 'Recepcionista registrada correctamente.');
    }

    public function edit(User $recepcionista)
    {
        abort_unless($recepcionista->esRecepcionista(), 404);

        return view('recepcionistas.edit', ['recepcionista' => $recepcionista]);
    }

    public function update(Request $request, User $recepcionista)
    {
        abort_unless($recepcionista->esRecepcionista(), 404);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$recepcionista->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $recepcionista->name = $datos['name'];
        $recepcionista->email = $datos['email'];

        if (! empty($datos['password'])) {
            $recepcionista->password = Hash::make($datos['password']);
        }

        $recepcionista->save();

        return redirect()->route('recepcionistas.index')->with('exito', 'Recepcionista actualizada correctamente.');
    }

    /**
     * Recepcionista no tiene tabla de perfil ni citas propias asociadas
     * directamente (solo 'creado_por', que es SET NULL al borrar), asi
     * que en teoria si se podria eliminar fisicamente. Preferimos, igual
     * que con doctores/pacientes, no perder el rastro de quien registro
     * cada cita: por eso "eliminar" aqui desactiva la cuenta en vez de
     * borrar la fila.
     */
    public function destroy(User $recepcionista)
    {
        abort_unless($recepcionista->esRecepcionista(), 404);

        if ($recepcionista->id === Auth::id()) {
            return redirect()->route('recepcionistas.index')->withErrors([
                'recepcionista' => 'No puedes desactivar tu propia cuenta.',
            ]);
        }

        $recepcionista->update(['activo' => false]);

        return redirect()->route('recepcionistas.index')->with('exito', 'Recepcionista desactivada. Su historial de citas registradas se conserva.');
    }

    public function activar(User $recepcionista)
    {
        abort_unless($recepcionista->esRecepcionista(), 404);

        $recepcionista->update(['activo' => true]);

        return redirect()->route('recepcionistas.index')->with('exito', 'Recepcionista activada nuevamente.');
    }
}
