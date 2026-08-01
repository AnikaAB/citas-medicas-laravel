<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'cedula' => ['required', 'digits:10', 'unique:pacientes,cedula'],
            'telefono' => ['required', 'digits:10'],
            'fecha_nacimiento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:pacientes,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'cedula.digits' => 'La cédula debe tener exactamente 10 dígitos.',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento no puede ser hoy ni una fecha futura.',
            'fecha_nacimiento.after' => 'Ingresa una fecha de nacimiento válida.',
        ]);

        $usuario = DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['nombre'].' '.$datos['apellido'],
                'email' => $datos['email'],
                'password' => Hash::make($datos['password']),
                'rol' => User::ROL_PACIENTE,
            ]);

            Paciente::create([
                'user_id' => $usuario->id,
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'cedula' => $datos['cedula'],
                'telefono' => $datos['telefono'],
                'email' => $datos['email'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'direccion' => $datos['direccion'] ?? null,
            ]);

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('exito', 'Cuenta creada correctamente. ¡Bienvenido!');
    }
}
