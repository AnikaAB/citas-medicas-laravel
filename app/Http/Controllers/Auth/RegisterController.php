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
            'cedula' => ['required', 'string', 'max:15', 'unique:pacientes,cedula'],
            'telefono' => ['required', 'string', 'max:20'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:pacientes,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
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
