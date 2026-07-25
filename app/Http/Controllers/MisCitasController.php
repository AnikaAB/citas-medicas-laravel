<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador exclusivo para el rol "paciente".
 *
 * A diferencia de CitaController (que usa Route::resource y es solo para
 * admin/recepcionista/doctor), aqui el paciente:
 *  - SOLO puede ver, agendar, cancelar o reprogramar SUS PROPIAS citas.
 *  - Nunca puede tocar una cita de otro paciente (se valida en cada metodo).
 *  - Cancelar/reprogramar solo esta permitido con 24h+ de anticipacion
 *    (regla de negocio centralizada en Cita::puedeModificarse()).
 */
class MisCitasController extends Controller
{
    /**
     * Listado de las citas del paciente autenticado, separadas en
     * "proximas" (pendiente/confirmada, aun no pasan) e "historial"
     * (atendidas, canceladas, o que ya pasaron su fecha).
     */
    public function index()
    {
        $paciente = Auth::user()->paciente;

        $todas = $paciente
            ? Cita::with('doctor')
                ->where('paciente_id', $paciente->id)
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get()
            : collect();

        $proximas = $todas->filter(fn (Cita $cita) => ! $cita->yaPaso() && $cita->estado !== Cita::ESTADO_CANCELADA)
            ->values();

        $historial = $todas->filter(fn (Cita $cita) => $cita->yaPaso() || $cita->estado === Cita::ESTADO_CANCELADA)
            ->sortByDesc(fn (Cita $cita) => $cita->fechaHora())
            ->values();

        return view('mis-citas.index', compact('proximas', 'historial'));
    }

    /**
     * Formulario para que el paciente agende una cita nueva para si mismo.
     */
    public function create()
    {
        $doctores = Doctor::orderBy('nombre')->get();

        return view('mis-citas.create', compact('doctores'));
    }

    /**
     * Guarda la cita nueva. El paciente_id SIEMPRE se toma del usuario
     * autenticado (nunca del formulario), para que un paciente jamas
     * pueda agendar una cita a nombre de otro.
     */
    public function store(Request $request)
    {
        $paciente = Auth::user()->paciente;

        if (! $paciente) {
            abort(403, 'Tu cuenta no tiene un perfil de paciente asociado.');
        }

        $datos = $request->validate([
            'doctor_id' => ['required', 'exists:doctores,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['required', 'string', 'max:255'],
        ]);

        // 'after_or_equal:today' solo revisa la FECHA. Si el paciente elige
        // el dia de hoy, todavia falta validar que la HORA no haya pasado ya.
        if ($this->esFechaHoraPasada($datos['fecha'], $datos['hora'])) {
            return back()->withErrors([
                'hora' => 'No puedes agendar una cita en una hora que ya paso. Elige una hora futura.',
            ])->withInput();
        }

        $doctor = Doctor::findOrFail($datos['doctor_id']);

        // La hora elegida debe caer dentro del horario de atencion del doctor.
        if ($datos['hora'] < substr($doctor->horario_inicio, 0, 5) || $datos['hora'] > substr($doctor->horario_fin, 0, 5)) {
            return back()->withErrors([
                'hora' => "El Dr. {$doctor->nombre} atiende de " . substr($doctor->horario_inicio, 0, 5) . ' a ' . substr($doctor->horario_fin, 0, 5) . '.',
            ])->withInput();
        }

        // IMPORTANTE: 'fecha' se guarda en la BD con marca de tiempo completa
        // (cast 'date' de Eloquent -> "2026-07-27 00:00:00"), pero aqui solo
        // tenemos el string crudo del formulario ("2026-07-27"). Comparar con
        // where('fecha', ...) nunca hace match y deja pasar duplicados que
        // luego truenan contra el indice unico de la base de datos. whereDate()
        // compara solo la parte de fecha sin importar el formato almacenado.
        $choque = Cita::where('doctor_id', $datos['doctor_id'])
            ->whereDate('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->exists();

        if ($choque) {
            return back()->withErrors([
                'hora' => 'El doctor seleccionado ya tiene una cita agendada en esa fecha y hora, elige otra.',
            ])->withInput();
        }

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $datos['doctor_id'],
            'creado_por' => Auth::id(),
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'motivo' => $datos['motivo'],
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        return redirect()->route('mis-citas.index')->with('exito', 'Tu cita fue agendada. Queda pendiente de confirmacion.');
    }

    /**
     * Cancela una cita propia.
     * Reglas: debe ser del paciente logueado, no puede estar ya
     * atendida/cancelada, y deben faltar 24 horas o mas.
     */
    public function cancelar(Cita $cita)
    {
        $this->verificarPropiedad($cita);

        if (! $cita->puedeModificarse()) {
            return back()->withErrors(['estado' => $cita->motivoNoModificable()]);
        }

        $cita->update(['estado' => Cita::ESTADO_CANCELADA]);

        return redirect()->route('mis-citas.index')->with('exito', 'Tu cita fue cancelada correctamente.');
    }

    /**
     * Formulario para reprogramar (cambiar fecha/hora) una cita propia.
     */
    public function editReprogramar(Cita $cita)
    {
        $this->verificarPropiedad($cita);

        if (! $cita->puedeModificarse()) {
            abort(403, $cita->motivoNoModificable());
        }

        return view('mis-citas.reprogramar', compact('cita'));
    }

    /**
     * Guarda la nueva fecha/hora de la cita.
     * Vuelve a validar el choque de horario del doctor y regresa el
     * estado a "pendiente" para que la recepcion la vuelva a confirmar.
     */
    public function reprogramar(Request $request, Cita $cita)
    {
        $this->verificarPropiedad($cita);

        if (! $cita->puedeModificarse()) {
            abort(403, $cita->motivoNoModificable());
        }

        $datos = $request->validate([
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
        ]);

        if ($this->esFechaHoraPasada($datos['fecha'], $datos['hora'])) {
            return back()->withErrors([
                'hora' => 'No puedes reprogramar a una hora que ya paso. Elige una hora futura.',
            ])->withInput();
        }

        // Mismo fix que en store(): comparar por whereDate(), no por igualdad
        // de string, porque 'fecha' se guarda con marca de tiempo completa.
        $choque = Cita::where('doctor_id', $cita->doctor_id)
            ->whereDate('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($choque) {
            return back()->withErrors([
                'hora' => 'El doctor ya tiene otra cita agendada en esa fecha y hora, elige otra.',
            ])->withInput();
        }

        $cita->update([
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        return redirect()->route('mis-citas.index')->with('exito', 'Tu cita fue reprogramada. Queda pendiente de confirmacion.');
    }

    /**
     * 'after_or_equal:today' de Laravel solo compara la FECHA (dia calendario),
     * no la hora. Esto significa que si alguien elige "hoy" como fecha,
     * podria colocar una hora que ya paso (ej: son las 18:00 y elige las 08:00).
     * Este metodo combina fecha+hora en un solo momento y lo compara contra
     * el reloj actual, para bloquear ese caso.
     */
    private function esFechaHoraPasada(string $fecha, string $hora): bool
    {
        $fechaHoraElegida = \Illuminate\Support\Carbon::parse($fecha . ' ' . $hora);

        return $fechaHoraElegida->isPast();
    }

    /**
     * Corta en seco si el paciente intenta tocar una cita que no es suya.
     */
    private function verificarPropiedad(Cita $cita): void
    {
        $paciente = Auth::user()->paciente;

        if (! $paciente || $cita->paciente_id !== $paciente->id) {
            abort(403, 'No puedes gestionar citas de otro paciente.');
        }
    }
}