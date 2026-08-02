<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Autogestion de citas para el rol "paciente".
 *
 * Se mantiene separado de CitaController (usado por admin/recepcionista/doctor)
 * a proposito: aqui las reglas son distintas (el paciente solo ve y agenda
 * SUS propias citas, nunca elige a nombre de quien agenda) y mezclarlo con
 * el controlador administrativo habria complicado los permisos de cada accion.
 */
class PacienteCitaController extends Controller
{
    /**
     * Tamano del bloque de horario, en minutos, para calcular horas libres.
     */
    private const DURACION_BLOQUE_MINUTOS = 30;

    /**
     * READ - "Mis citas" del paciente autenticado.
     */
    public function index()
    {
        $paciente = $this->pacienteAutenticado();

        $citas = Cita::with('doctor')
            ->where('paciente_id', $paciente->id)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->paginate(10);

        return view('paciente.citas.index', compact('citas'));
    }

    /**
     * CREATE - Formulario de autoagendamiento.
     * Solo se listan las especialidades; el doctor y la hora se cargan
     * de forma dependiente via JS (ver doctoresPorEspecialidad/horariosDisponibles).
     */
    public function create()
    {
        $especialidades = Doctor::orderBy('especialidad')
            ->pluck('especialidad')
            ->unique()
            ->values();

        return view('paciente.citas.create', compact('especialidades'));
    }

    /**
     * CREATE - Guardar cita propia.
     *
     * Regla de seguridad clave: paciente_id SIEMPRE sale del usuario
     * autenticado (Auth::user()->paciente->id), nunca de un input del
     * formulario. Asi, aunque alguien manipule el POST a mano, jamas
     * puede agendar una cita a nombre de otro paciente.
     */
    public function store(Request $request)
    {
        $paciente = $this->pacienteAutenticado();

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

        // Se revalida el horario en servidor (nunca confiar solo en el <select>
        // que llena el JS): si la hora elegida ya no esta libre, se rechaza.
        if (! in_array($datos['hora'], $this->horariosLibres($doctor, $datos['fecha']), true)) {
            return back()->withErrors([
                'hora' => 'Ese horario ya no esta disponible. Elige otro.',
            ])->withInput();
        }

        Cita::create([
            'paciente_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'creado_por' => Auth::id(),
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'motivo' => $datos['motivo'],
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        return redirect()->route('paciente.citas.index')->with('exito', 'Cita agendada correctamente.');
    }

    /**
     * UPDATE - Cancelar (no eliminar) una cita propia.
     * Coherente con la maquina de estados: solo cambia el estado a
     * "cancelada", nunca borra el registro.
     */
    public function cancelar(Cita $cita)
    {
        $this->verificarPropiedad($cita);

        if (! Cita::transicionValida($cita->estado, Cita::ESTADO_CANCELADA)) {
            return back()->withErrors([
                'estado' => "No se puede cancelar una cita en estado \"{$cita->estado}\".",
            ]);
        }

        if (! $cita->puedeModificarse()) {
            return back()->withErrors(['estado' => $cita->motivoNoModificable()]);
        }

        $cita->update(['estado' => Cita::ESTADO_CANCELADA]);

        return redirect()->route('paciente.citas.index')->with('exito', 'Cita cancelada correctamente.');
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

        return view('paciente.citas.reprogramar', compact('cita'));
    }

    /**
     * Guarda la nueva fecha/hora de la cita reprogramada.
     * Vuelve a validar disponibilidad y regresa el estado a "pendiente"
     * para que recepcion la confirme de nuevo.
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

        if (! in_array($datos['hora'], $this->horariosLibres($cita->doctor, $datos['fecha']), true)) {
            return back()->withErrors([
                'hora' => 'Ese horario ya no esta disponible. Elige otro.',
            ])->withInput();
        }

        $cita->update([
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);

        return redirect()->route('paciente.citas.index')->with('exito', 'Cita reprogramada. Queda pendiente de confirmacion.');
    }

    /**
     * Corta en seco si el paciente intenta gestionar una cita que no es suya.
     */
    private function verificarPropiedad(Cita $cita): void
    {
        $paciente = $this->pacienteAutenticado();

        if ($cita->paciente_id !== $paciente->id) {
            abort(403, 'No puedes gestionar una cita que no es tuya.');
        }
    }

    /**
     * Indica si una combinacion fecha+hora ya paso respecto de ahora mismo.
     */
    private function esFechaHoraPasada(string $fecha, string $hora): bool
    {
        return Carbon::parse($fecha . ' ' . $hora)->isPast();
    }

    /**
     * AJAX - Doctores de una especialidad, para el <select> dependiente
     * del formulario de agendar.
     */
    public function doctoresPorEspecialidad(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'especialidad' => ['required', 'string'],
        ]);

        $doctores = Doctor::where('especialidad', $datos['especialidad'])
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'apellido']);

        return response()->json(
            $doctores->map(fn (Doctor $doctor) => [
                'id' => $doctor->id,
                'nombre' => 'Dr. '.$doctor->nombre.' '.$doctor->apellido,
            ])->values()
        );
    }

    /**
     * AJAX - Horarios libres de un doctor en una fecha dada, para el
     * <select> de hora del formulario de agendar.
     */
    public function horariosDisponibles(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'doctor_id' => ['required', 'exists:doctores,id'],
            'fecha' => ['required', 'date'],
        ]);

        $doctor = Doctor::findOrFail($datos['doctor_id']);

        return response()->json($this->horariosLibres($doctor, $datos['fecha']));
    }

    /**
     * Calcula los horarios libres de un doctor en una fecha: todos los
     * bloques de 30 min entre su horario_inicio y horario_fin, quitando
     * los que ya tienen una cita ACTIVA (pendiente o confirmada) ese dia.
     * Una cita cancelada no bloquea el bloque (coherente con la Fase 4).
     *
     * @return array<int, string> horas en formato "H:i" (ej. "09:30")
     */
    private function horariosLibres(Doctor $doctor, string $fecha): array
    {
        $ocupadas = Cita::where('doctor_id', $doctor->id)
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', [Cita::ESTADO_PENDIENTE, Cita::ESTADO_CONFIRMADA])
            ->pluck('hora')
            ->map(fn ($hora) => Carbon::parse($hora)->format('H:i'))
            ->all();

        $inicio = Carbon::parse($doctor->horario_inicio);
        $fin = Carbon::parse($doctor->horario_fin);

        $libres = [];
        for ($cursor = $inicio->copy(); $cursor->lt($fin); $cursor->addMinutes(self::DURACION_BLOQUE_MINUTOS)) {
            $hora = $cursor->format('H:i');
            if (! in_array($hora, $ocupadas, true) && ! $this->esFechaHoraPasada($fecha, $hora)) {
                $libres[] = $hora;
            }
        }

        return $libres;
    }

    /**
     * Devuelve el perfil de paciente ligado al usuario autenticado,
     * o corta la ejecucion con 403 si el usuario (aunque tenga rol
     * "paciente") no tiene un registro en `pacientes` vinculado.
     */
    private function pacienteAutenticado()
    {
        $paciente = Auth::user()->paciente;

        if (! $paciente) {
            abort(403, 'Tu usuario no tiene un perfil de paciente asociado.');
        }

        return $paciente;
    }
}
