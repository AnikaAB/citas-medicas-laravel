<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Cada rol ve un resumen distinto (principio de menor privilegio / UI adaptada al rol)
        if ($usuario->esDoctor()) {
            $doctor = $usuario->doctor;
            $citas = $doctor
                ? Cita::with('paciente')->where('doctor_id', $doctor->id)
                    ->orderBy('fecha')->orderBy('hora')->get()
                : collect();

            return view('dashboard', [
                'citas' => $citas,
                'totalCitas' => $citas->count(),
            ]);
        }

        if ($usuario->esPaciente()) {
            $paciente = $usuario->paciente;
            $citas = $paciente
                ? Cita::with('doctor')->where('paciente_id', $paciente->id)
                    ->orderBy('fecha')->orderBy('hora')->get()
                : collect();

            return view('dashboard', [
                'citas' => $citas,
                'totalCitas' => $citas->count(),
            ]);
        }

        // admin / recepcionista: panorama general
        return view('dashboard', [
            'totalDoctores' => Doctor::count(),
            'totalPacientes' => Paciente::count(),
            'totalCitas' => Cita::count(),
            'citasPendientes' => Cita::where('estado', 'pendiente')->count(),
            'citasHoy' => Cita::whereDate('fecha', now()->toDateString())->count(),
        ]);
    }
}
