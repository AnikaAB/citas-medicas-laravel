<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctoresSeeder extends Seeder
{
    public function run(): void
    {
        $doctores = [
            ['Ricardo', 'Alvarado Peña', 'Medicina General', '0991234561', 'ralvarado@clinica.com'],
            ['Sofia', 'Mendoza Cruz', 'Pediatria', '0991234562', 'smendoza@clinica.com'],
            ['Miguel', 'Torres Chavez', 'Cardiologia', '0991234563', 'mtorres@clinica.com'],
            ['Paola', 'Ibarra Salinas', 'Ginecologia', '0991234564', 'pibarra@clinica.com'],
            ['Fernando', 'Rojas Delgado', 'Dermatologia', '0991234565', 'frojas@clinica.com'],
        ];

        foreach ($doctores as [$nombre, $apellido, $especialidad, $telefono, $email]) {
            $user = User::create([
                'name' => "Dr. $nombre $apellido",
                'email' => $email,
                'password' => Hash::make('password'),
                'rol' => User::ROL_DOCTOR,
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'especialidad' => $especialidad,
                'telefono' => $telefono,
                'email' => $email,
                'horario_inicio' => '08:00:00',
                'horario_fin' => '17:00:00',
            ]);
        }
    }
}
