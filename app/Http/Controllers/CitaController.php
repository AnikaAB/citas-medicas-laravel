<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * READ - Listado de citas (con filtros basicos).
     */
    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'doctor']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->input('fecha'));
        }

        $citas = $query->orderBy('fecha')->orderBy('hora')->paginate(10)->withQueryString();

        return view('citas.index', compact('citas'));
    }

    /**
     * CREATE - Formulario de nueva cita.
     */
    public function create()
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $doctores = Doctor::orderBy('nombre')->get();

        return view('citas.create', compact('pacientes', 'doctores'));
    }

    /**
     * CREATE - Guardar nueva cita.
     * Reglas de negocio clave:
     *  - Un doctor no puede tener dos citas en la misma fecha y hora
     *    (validado con 'unique' compuesto y reforzado a nivel de base
     *    de datos con indice unico).
     *  - No se puede agendar una cita en una fecha/hora que ya paso.
     *    'after_or_equal:today' solo compara la FECHA, por eso si se
     *    elige el dia de hoy hay que revisar tambien que la HORA no
     *    haya pasado ya (mismo criterio que MisCitasController).
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctores,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'in:pendiente,confirmada,cancelada,atendida'],
            'observaciones' => ['nullable', 'string'],
        ]);

        if ($this->esFechaHoraPasada($datos['fecha'], $datos['hora'])) {
            return back()->withErrors([
                'hora' => 'No puedes agendar una cita en una hora que ya paso. Elige una hora futura.',
            ])->withInput();
        }

        // IMPORTANTE: 'fecha' se guarda en la BD con marca de tiempo completa
        // (cast 'date' de Eloquent -> "2026-07-27 00:00:00"), pero aqui solo
        // tenemos el string crudo del formulario ("2026-07-27"). Comparar con
        // where('fecha', ...) nunca hace match y deja pasar duplicados que
        // luego truenan contra el indice unico de la base de datos. whereDate()
        // compara solo la parte de fecha sin importar el formato almacenado.
        $existe = Cita::where('doctor_id', $datos['doctor_id'])
            ->whereDate('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'hora' => 'El doctor seleccionado ya tiene una cita agendada en esa fecha y hora.',
            ])->withInput();
        }

        $datos['creado_por'] = Auth::id();

        Cita::create($datos);

        return redirect()->route('citas.index')->with('exito', 'Cita registrada correctamente.');
    }

    /**
     * READ - Detalle de una cita.
     */
    public function show(Cita $cita)
    {
        $cita->load(['paciente', 'doctor', 'creadoPor']);

        return view('citas.show', compact('cita'));
    }

    /**
     * UPDATE - Formulario de edicion.
     */
    public function edit(Cita $cita)
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $doctores = Doctor::orderBy('nombre')->get();

        return view('citas.edit', compact('cita', 'pacientes', 'doctores'));
    }

    /**
     * UPDATE - Persistir cambios.
     *
     * Nota: a diferencia de store(), aqui NO se bloquea fecha/hora pasada
     * a proposito, ya que el admin/recepcion suele editar una cita despues
     * de que ocurrio (por ejemplo, para marcarla como "atendida" o agregar
     * observaciones). Si tambien quieres bloquear esto al editar, avisame.
     */
    public function update(Request $request, Cita $cita)
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctores,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'in:pendiente,confirmada,cancelada,atendida'],
            'observaciones' => ['nullable', 'string'],
        ]);

        // Mismo fix que en store(): comparar por whereDate(), no por igualdad
        // de string, porque 'fecha' se guarda con marca de tiempo completa.
        $existe = Cita::where('doctor_id', $datos['doctor_id'])
            ->whereDate('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'hora' => 'El doctor seleccionado ya tiene una cita agendada en esa fecha y hora.',
            ])->withInput();
        }

        $cita->update($datos);

        return redirect()->route('citas.index')->with('exito', 'Cita actualizada correctamente.');
    }

    /**
     * DELETE - Eliminar (cancelar definitivamente) una cita.
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('exito', 'Cita eliminada correctamente.');
    }

    /**
     * 'after_or_equal:today' de Laravel solo compara la FECHA (dia calendario),
     * no la hora. Esto significa que si alguien elige "hoy" como fecha,
     * podria colocar una hora que ya paso (ej: son las 18:00 y elige las 08:00).
     * Este metodo combina fecha+hora en un solo momento y lo compara contra
     * el reloj actual, para bloquear ese caso. (Misma logica que
     * MisCitasController::esFechaHoraPasada()).
     */
    private function esFechaHoraPasada(string $fecha, string $hora): bool
    {
        $fechaHoraElegida = \Illuminate\Support\Carbon::parse($fecha . ' ' . $hora);

        return $fechaHoraElegida->isPast();
    }
}