<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Seeder;

class CitasSeeder extends Seeder
{
    /**
     * Genera un pequeño historial de citas de ejemplo:
     * cada uno de los 5 doctores atiende varios pacientes
     * en fechas distintas, con distintos estados.
     */
    public function run(): void
    {
        $doctores = Doctor::all();
        $pacientes = Paciente::all();
        $recepcionista = User::where('rol', User::ROL_RECEPCIONISTA)->first();

        $estados = ['pendiente', 'confirmada', 'atendida', 'cancelada'];
        $motivos = [
            'Consulta general', 'Control periodico', 'Dolor abdominal',
            'Chequeo pediatrico', 'Control de presion arterial',
            'Revision dermatologica', 'Consulta ginecologica', 'Seguimiento',
        ];

        $horaBase = 8;
        $contador = 0;

        foreach ($pacientes as $index => $paciente) {
            $doctor = $doctores[$index % $doctores->count()];
            $hora = $horaBase + ($contador % 8); // 08:00 a 15:00

            Cita::create([
                'paciente_id' => $paciente->id,
                'doctor_id' => $doctor->id,
                'creado_por' => $recepcionista?->id,
                'fecha' => now()->addDays(($index % 10) + 1)->format('Y-m-d'),
                'hora' => sprintf('%02d:00:00', $hora),
                'motivo' => $motivos[$index % count($motivos)],
                'estado' => $estados[$index % count($estados)],
                'observaciones' => null,
            ]);

            $contador++;
        }
    }
}
