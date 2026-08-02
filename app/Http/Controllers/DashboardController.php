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

            $citasQuery = $doctor
                ? Cita::with('paciente')->where('doctor_id', $doctor->id)
                : Cita::whereRaw('0 = 1'); // sin perfil de doctor vinculado: no debe ver nada

            $citas = (clone $citasQuery)->orderBy('fecha')->orderBy('hora')->get();

            // Mismo desglose por estado que ve el admin, pero acotado a las
            // citas de este doctor unicamente.
            $estados = ['pendiente', 'confirmada', 'atendida', 'cancelada'];
            $citasPorEstado = collect($estados)->mapWithKeys(function ($estado) use ($citasQuery) {
                return [$estado => (clone $citasQuery)->where('estado', $estado)->count()];
            });

            // En vez de "citas por especialidad" (que no aplica a un doctor
            // individual), el panel derecho muestra sus proximas citas
            // (pendientes o confirmadas, desde hoy en adelante).
            $proximasCitas = (clone $citasQuery)
                ->where('fecha', '>=', now()->toDateString())
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->orderBy('fecha')->orderBy('hora')
                ->take(6)
                ->get();

            return view('dashboard', [
                'citas' => $citas,
                'totalCitas' => $citas->count(),
                'citasPorEstado' => $citasPorEstado,
                'proximasCitas' => $proximasCitas,
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

        // admin / recepcionista: panorama general + estadisticas
        $estados = ['pendiente', 'confirmada', 'atendida', 'cancelada'];
        $citasPorEstado = collect($estados)->mapWithKeys(function ($estado) {
            return [$estado => Cita::where('estado', $estado)->count()];
        });

        $citasPorEspecialidad = Doctor::withCount('citas')
            ->get()
            ->groupBy('especialidad')
            ->map(fn ($doctores) => $doctores->sum('citas_count'))
            ->sortDesc();

        return view('dashboard', [
            'totalDoctores' => Doctor::count(),
            'totalPacientes' => Paciente::count(),
            'totalCitas' => Cita::count(),
            'citasPendientes' => Cita::where('estado', 'pendiente')->count(),
            'citasHoy' => Cita::whereDate('fecha', now()->toDateString())->count(),
            'citasPorEstado' => $citasPorEstado,
            'citasPorEspecialidad' => $citasPorEspecialidad,
        ]);
    }
}
