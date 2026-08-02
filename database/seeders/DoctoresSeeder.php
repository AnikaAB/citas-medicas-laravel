<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DoctoresSeeder extends Seeder
{
    /**
     * Perfiles de doctores, ligados a su user_id (9 al 13, creados en UsersSeeder).
     */
    public function run(): void
    {
        $now = Carbon::now();

        $doctores = [
            [9,  'Ricardo',  'Alvarado Pena',  'Medicina General', '0991234561', 'ralvarado@clinica.com'],
            [10, 'Sofia',    'Mendoza Cruz',   'Pediatria',        '0991234562', 'smendoza@clinica.com'],
            [11, 'Miguel',   'Torres Chavez',  'Cardiologia',      '0991234563', 'mtorres@clinica.com'],
            [12, 'Paola',    'Ibarra Salinas', 'Ginecologia',      '0991234564', 'pibarra@clinica.com'],
            [13, 'Fernando', 'Rojas Delgado',  'Dermatologia',     '0991234565', 'frojas@clinica.com'],
        ];

        foreach ($doctores as [$userId, $nombre, $apellido, $especialidad, $telefono, $email]) {
            DB::table('doctores')->insert([
                'user_id' => $userId,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'especialidad' => $especialidad,
                'telefono' => $telefono,
                'email' => $email,
                'horario_inicio' => '08:00:00',
                'horario_fin' => '17:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
