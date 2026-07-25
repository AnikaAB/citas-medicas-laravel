<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::query();

        if ($request->filled('buscar')) {
            $texto = $request->input('buscar');
            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'like', "%{$texto}%")
                  ->orWhere('apellido', 'like', "%{$texto}%")
                  ->orWhere('cedula', 'like', "%{$texto}%");
            });
        }

        $pacientes = $query->orderBy('nombre')->paginate(10)->withQueryString();

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'cedula' => ['required', 'string', 'max:15', 'unique:pacientes,cedula'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:pacientes,email', 'unique:users,email'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $datos['nombre'].' '.$datos['apellido'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => User::ROL_PACIENTE,
        ]);

        Paciente::create([
            'user_id' => $user->id,
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'cedula' => $datos['cedula'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'direccion' => $datos['direccion'] ?? null,
        ]);

        return redirect()->route('pacientes.index')->with('exito', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load('citas.doctor');

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'cedula' => ['required', 'string', 'max:15', 'unique:pacientes,cedula,'.$paciente->id],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:pacientes,email,'.$paciente->id],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $paciente->update($datos);

        return redirect()->route('pacientes.index')->with('exito', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('exito', 'Paciente eliminado correctamente.');
    }
}
