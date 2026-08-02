<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_CONFIRMADA = 'confirmada';
    public const ESTADO_CANCELADA = 'cancelada';
    public const ESTADO_ATENDIDA = 'atendida';

    /**
     * Mapa de transiciones de estado permitidas.
     * Clave = estado actual, valor = estados a los que puede pasar.
     */
    public const TRANSICIONES = [
        self::ESTADO_PENDIENTE => [self::ESTADO_CONFIRMADA, self::ESTADO_CANCELADA],
        self::ESTADO_CONFIRMADA => [self::ESTADO_ATENDIDA, self::ESTADO_CANCELADA],
        self::ESTADO_ATENDIDA => [],
        self::ESTADO_CANCELADA => [],
    ];

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'creado_por',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            // Formato explicito Y-m-d (sin hora): evita que Eloquent serialice
            // "fecha" con un timestamp completo (00:00:00) al guardar, lo cual
            // rompia comparaciones exactas de string en SQLite (usado en tests)
            // y en assertDatabaseHas.
            'fecha' => 'date:Y-m-d',
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Indica si es valido pasar del estado $actual al estado $nuevo
     * segun el mapa de transiciones definido en TRANSICIONES.
     */
    public static function transicionValida(string $actual, string $nuevo): bool
    {
        // Permite "transicionar" al mismo estado (no-op) sin romper flujos existentes.
        if ($actual === $nuevo) {
            return true;
        }

        return in_array($nuevo, self::TRANSICIONES[$actual] ?? [], true);
    }

    /**
     * Indica si la cita ya llego a un estado final (no permite mas cambios).
     */
    public function estaFinalizada(): bool
    {
        return $this->estado === self::ESTADO_ATENDIDA;
    }

    /**
     * Indica si el paciente todavia puede cancelar o reprogramar esta cita:
     * debe estar pendiente/confirmada y faltar 24 horas o mas.
     */
    public function puedeModificarse(): bool
    {
        if (! in_array($this->estado, [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA], true)) {
            return false;
        }

        $fechaHora = \Illuminate\Support\Carbon::parse(
            $this->fecha->format('Y-m-d') . ' ' . $this->hora
        );

        return now()->diffInHours($fechaHora, false) >= 24;
    }

    /**
     * Mensaje explicando por que no se puede modificar, para mostrar en pantalla.
     */
    public function motivoNoModificable(): string
    {
        if (! in_array($this->estado, [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA], true)) {
            return "No se puede modificar una cita en estado \"{$this->estado}\".";
        }

        return 'Ya no se puede modificar: faltan menos de 24 horas para la cita.';
    }
}