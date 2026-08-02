<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CitasSeeder extends Seeder
{
    /**
     * 20 citas de ejemplo.
     * paciente_id 1-20 (tabla pacientes), doctor_id 1-5 rotativo (tabla doctores),
     * creado_por = 2 (user_id de la recepcionista Maria Fernanda Lopez Torres).
     */
    public function run(): void
    {
        $now = Carbon::now();

        $citas = [
            [1, 1, '2026-07-25', '08:00:00', 'Consulta general', 'pendiente'],
            [2, 2, '2026-07-26', '09:00:00', 'Control periodico', 'confirmada'],
            [3, 3, '2026-07-27', '10:00:00', 'Dolor abdominal', 'atendida'],
            [4, 4, '2026-07-28', '11:00:00', 'Chequeo pediatrico', 'cancelada'],
            [5, 5, '2026-07-29', '12:00:00', 'Control de presion arterial', 'pendiente'],
            [6, 1, '2026-07-30', '13:00:00', 'Revision dermatologica', 'confirmada'],
            [7, 2, '2026-07-31', '14:00:00', 'Consulta ginecologica', 'atendida'],
            [8, 3, '2026-08-01', '15:00:00', 'Seguimiento', 'cancelada'],
            [9, 4, '2026-08-02', '08:00:00', 'Consulta general', 'pendiente'],
            [10, 5, '2026-08-03', '09:00:00', 'Control periodico', 'confirmada'],
            [11, 1, '2026-07-25', '10:00:00', 'Dolor abdominal', 'atendida'],
            [12, 2, '2026-07-26', '11:00:00', 'Chequeo pediatrico', 'cancelada'],
            [13, 3, '2026-07-27', '12:00:00', 'Control de presion arterial', 'pendiente'],
            [14, 4, '2026-07-28', '13:00:00', 'Revision dermatologica', 'confirmada'],
            [15, 5, '2026-07-29', '14:00:00', 'Consulta ginecologica', 'atendida'],
            [16, 1, '2026-07-30', '15:00:00', 'Seguimiento', 'cancelada'],
            [17, 2, '2026-07-31', '08:00:00', 'Consulta general', 'pendiente'],
            [18, 3, '2026-08-01', '09:00:00', 'Control periodico', 'confirmada'],
            [19, 4, '2026-08-02', '10:00:00', 'Dolor abdominal', 'atendida'],
            [20, 5, '2026-08-03', '11:00:00', 'Chequeo pediatrico', 'cancelada'],
        ];

        foreach ($citas as [$pacienteId, $doctorId, $fecha, $hora, $motivo, $estado]) {
            DB::table('citas')->insert([
                'paciente_id' => $pacienteId,
                'doctor_id' => $doctorId,
                'creado_por' => 2,
                'fecha' => $fecha,
                'hora' => $hora,
                'motivo' => $motivo,
                'estado' => $estado,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
