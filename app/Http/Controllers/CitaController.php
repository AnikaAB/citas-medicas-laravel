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

        $usuario = $request->user();

        // Un doctor solo puede ver su propia agenda.
        if ($usuario->esDoctor()) {
            // Si el usuario tiene rol doctor pero no tiene perfil vinculado
            // (p. ej. su registro en `doctores` fue eliminado), no debe ver nada.
            $query->where('doctor_id', $usuario->doctor->id ?? 0);
        }

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
     * Regla de negocio clave: un doctor no puede tener dos citas
     * activas (no canceladas) en la misma fecha y hora. La validacion
     * vive solo aqui en la app; no hay indice unico en BD para no
     * bloquear un horario que quedo libre por cancelacion.
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

        // El formulario envia la hora como "H:i" (ej. "10:00"), pero se
        // normaliza siempre a "H:i:s" antes de comparar/guardar. Sin esto,
        // "10:00" y "10:00:00" se tratan como valores distintos al comparar
        // strings (pasa en SQLite, usado en los tests), lo que dejaba pasar
        // citas duplicadas para el mismo doctor/fecha/hora.
        $datos['hora'] = \Illuminate\Support\Carbon::parse($datos['hora'])->format('H:i:s');

        $existe = Cita::where('doctor_id', $datos['doctor_id'])
            ->where('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
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
    public function show(Request $request, Cita $cita)
    {
        $usuario = $request->user();

        if ($usuario->esDoctor() && $cita->doctor_id !== ($usuario->doctor->id ?? 0)) {
            abort(403, 'No tienes permisos para ver esta cita.');
        }

        $cita->load(['paciente', 'doctor', 'creadoPor']);

        return view('citas.show', compact('cita'));
    }

    /**
     * UPDATE - Formulario de edicion.
     */
    public function edit(Request $request, Cita $cita)
    {
        // Defensa en profundidad: aunque las rutas de escritura ya estan
        // restringidas a admin/recepcionista en web.php, se revisa el rol
        // tambien aqui para que este controlador nunca dependa unicamente
        // de como esten armadas las rutas.
        if (! $request->user()->esAdmin() && ! $request->user()->esRecepcionista()) {
            abort(403, 'No tienes permisos para editar citas.');
        }

        if ($cita->estaFinalizada()) {
            return redirect()->route('citas.index')->withErrors([
                'estado' => 'No se puede modificar una cita ya atendida.',
            ]);
        }

        $pacientes = Paciente::orderBy('nombre')->get();
        $doctores = Doctor::orderBy('nombre')->get();

        return view('citas.edit', compact('cita', 'pacientes', 'doctores'));
    }

    /**
     * UPDATE - Persistir cambios.
     */
    public function update(Request $request, Cita $cita)
    {
        if (! $request->user()->esAdmin() && ! $request->user()->esRecepcionista()) {
            abort(403, 'No tienes permisos para editar citas.');
        }

        if ($cita->estaFinalizada()) {
            return redirect()->route('citas.index')->withErrors([
                'estado' => 'No se puede modificar una cita ya atendida.',
            ]);
        }

        $esAdmin = Auth::user()->esAdmin();

        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'exists:doctores,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['required', 'string', 'max:255'],
            // Para admin, "estado" es irrelevante: se acepta cualquier cosa (o nada)
            // en la validacion porque de todas formas se descarta mas abajo.
            'estado' => $esAdmin
                ? ['sometimes', 'in:pendiente,confirmada,cancelada,atendida']
                : ['required', 'in:pendiente,confirmada,cancelada,atendida'],
            'observaciones' => ['nullable', 'string'],
        ]);

        // Misma normalizacion que en store(): la hora siempre se compara y
        // guarda en formato "H:i:s".
        $datos['hora'] = \Illuminate\Support\Carbon::parse($datos['hora'])->format('H:i:s');

        $existe = Cita::where('doctor_id', $datos['doctor_id'])
            ->where('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'hora' => 'El doctor seleccionado ya tiene una cita agendada en esa fecha y hora.',
            ])->withInput();
        }

        if ($esAdmin) {
            // El admin no gestiona el flujo clinico de la cita: se ignora
            // cualquier valor de "estado" que venga en el formulario, aunque
            // se manipule manualmente (curl, devtools, etc.).
            unset($datos['estado']);
        } elseif (isset($datos['estado']) && ! Cita::transicionValida($cita->estado, $datos['estado'])) {
            // Maquina de estados: solo se permiten las transiciones definidas
            // en Cita::TRANSICIONES (p. ej. no se puede pasar de "cancelada" a "atendida").
            return back()->withErrors([
                'estado' => "No se puede pasar de \"{$cita->estado}\" a \"{$datos['estado']}\".",
            ])->withInput();
        }

        $cita->update($datos);

        return redirect()->route('citas.index')->with('exito', 'Cita actualizada correctamente.');
    }

    /**
     * DELETE - Eliminar (cancelar definitivamente) una cita.
     */
    public function destroy(Request $request, Cita $cita)
    {
        if (! $request->user()->esAdmin() && ! $request->user()->esRecepcionista()) {
            abort(403, 'No tienes permisos para eliminar citas.');
        }

        if ($cita->estaFinalizada()) {
            return redirect()->route('citas.index')->withErrors([
                'estado' => 'No se puede eliminar una cita ya atendida.',
            ]);
        }

        $cita->delete();

        return redirect()->route('citas.index')->with('exito', 'Cita eliminada correctamente.');
    }
}
