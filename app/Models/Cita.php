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
            'fecha' => 'date',
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
     * Combina fecha + hora en un solo Carbon, para poder comparar
     * contra "ahora" (util para la regla de las 24 horas y para
     * saber si una cita ya paso).
     */
    public function fechaHora(): \Illuminate\Support\Carbon
    {
        return \Illuminate\Support\Carbon::parse(
            $this->fecha->format('Y-m-d') . ' ' . $this->hora
        );
    }

    /**
     * Una cita ya "paso" si su fecha/hora es anterior al momento actual.
     */
    public function yaPaso(): bool
    {
        return $this->fechaHora()->isPast();
    }

    /**
     * Regla de negocio pedida por el paciente: solo se puede cancelar
     * o reprogramar una cita si:
     *  - todavia no fue atendida ni esta ya cancelada, Y
     *  - faltan mas de 24 horas para la hora de la cita.
     */
    public function puedeModificarse(): bool
    {
        if (in_array($this->estado, [self::ESTADO_ATENDIDA, self::ESTADO_CANCELADA], true)) {
            return false;
        }

        return now()->diffInHours($this->fechaHora(), false) >= 24;
    }

    /**
     * Mensaje explicando por que no se puede modificar (usado en las vistas).
     */
    public function motivoNoModificable(): ?string
    {
        if ($this->estado === self::ESTADO_ATENDIDA) {
            return 'Esta cita ya fue atendida.';
        }

        if ($this->estado === self::ESTADO_CANCELADA) {
            return 'Esta cita ya esta cancelada.';
        }

        if (now()->diffInHours($this->fechaHora(), false) < 24) {
            return 'Solo se puede cancelar o reprogramar con al menos 24 horas de anticipacion.';
        }

        return null;
    }
}
