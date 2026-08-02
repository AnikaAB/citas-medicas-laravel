<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctores = Doctor::orderBy('nombre')->paginate(10);

        return view('doctores.index', compact('doctores'));
    }

    public function create()
    {
        return view('doctores.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]{7,10}$/'],
            'email' => ['required', 'email', 'unique:doctores,email', 'unique:users,email'],
            'horario_inicio' => ['required', 'date_format:H:i'],
            'horario_fin' => ['required', 'date_format:H:i', 'after:horario_inicio'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'telefono.regex' => 'El telefono debe tener entre 7 y 10 digitos numericos, sin espacios ni guiones.',
        ]);

        $user = User::create([
            'name' => 'Dr. '.$datos['nombre'].' '.$datos['apellido'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => User::ROL_DOCTOR,
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'especialidad' => $datos['especialidad'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'],
            'horario_inicio' => $datos['horario_inicio'],
            'horario_fin' => $datos['horario_fin'],
        ]);

        return redirect()->route('doctores.index')->with('exito', 'Doctor registrado correctamente.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('citas.paciente');

        return view('doctores.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctores.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]{7,10}$/'],
            'email' => ['required', 'email', 'unique:doctores,email,'.$doctor->id],
            'horario_inicio' => ['required', 'date_format:H:i'],
            'horario_fin' => ['required', 'date_format:H:i', 'after:horario_inicio'],
        ], [
            'telefono.regex' => 'El telefono debe tener entre 7 y 10 digitos numericos, sin espacios ni guiones.',
        ]);

        $doctor->update($datos);

        return redirect()->route('doctores.index')->with('exito', 'Doctor actualizado correctamente.');
    }

    public function destroy(Doctor $doctor)
    {
        // No se puede eliminar un doctor que todavia tiene citas activas
        // (pendientes o confirmadas): se perderia la trazabilidad de esas
        // citas y el paciente se quedaria con una cita "huerfana".
        $tieneCitasActivas = $doctor->citas()
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        if ($tieneCitasActivas) {
            return redirect()->route('doctores.index')->withErrors([
                'doctor' => 'No se puede eliminar al doctor: tiene citas pendientes o confirmadas. Cancélalas o reasígnalas primero.',
            ]);
        }

        $doctor->delete();

        return redirect()->route('doctores.index')->with('exito', 'Doctor eliminado correctamente.');
    }
}