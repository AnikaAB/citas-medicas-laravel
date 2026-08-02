<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Autogestion del perfil del paciente autenticado.
 *
 * Deliberadamente NO se permite editar aqui: la cedula (evita duplicados
 * y problemas de identidad si se escribe mal) ni el email (es el usuario
 * de login; cambiarlo se deja fuera de alcance de esta fase).
 */
class PerfilController extends Controller
{
    public function edit()
    {
        $paciente = $this->pacienteAutenticado();

        return view('paciente.perfil.edit', compact('paciente'));
    }

    public function update(Request $request)
    {
        $paciente = $this->pacienteAutenticado();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]{7,10}$/'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ], [
            'telefono.regex' => 'El telefono debe tener entre 7 y 10 digitos numericos, sin espacios ni guiones.',
        ]);

        $paciente->update($datos);

        // Mantiene sincronizado el nombre mostrado en la barra de navegacion
        // (users.name) con el nombre/apellido reales del paciente.
        Auth::user()->update([
            'name' => $datos['nombre'].' '.$datos['apellido'],
        ]);

        return redirect()->route('perfil.edit')->with('exito', 'Tus datos se actualizaron correctamente.');
    }

    private function pacienteAutenticado()
    {
        $paciente = Auth::user()->paciente;

        if (! $paciente) {
            abort(403, 'Tu usuario no tiene un perfil de paciente asociado.');
        }

        return $paciente;
    }
}
