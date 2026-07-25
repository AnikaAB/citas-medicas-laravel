<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Crea 1 administrador, 7 recepcionistas y las cuentas base
     * para los 5 doctores (login de doctor). Los usuarios de los
     * 20 pacientes se crean junto con PacientesSeeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador General',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_ADMIN,
        ]);

        $recepcionistas = [
            ['Maria Fernanda', 'Lopez Torres', 'mlopez@clinica.com'],
            ['Carlos Andres', 'Ramirez Vera', 'cramirez@clinica.com'],
            ['Gabriela Isabel', 'Suarez Mora', 'gsuarez@clinica.com'],
            ['Jorge Luis', 'Castillo Reyes', 'jcastillo@clinica.com'],
            ['Andrea Paola', 'Jimenez Cruz', 'ajimenez@clinica.com'],
            ['Diego Fernando', 'Ortiz Salas', 'dortiz@clinica.com'],
            ['Valentina', 'Cordova Peña', 'vcordova@clinica.com'],
        ];

        foreach ($recepcionistas as [$nombre, $apellido, $email]) {
            User::create([
                'name' => "$nombre $apellido",
                'email' => $email,
                'password' => Hash::make('password'),
                'rol' => User::ROL_RECEPCIONISTA,
            ]);
        }
    }
}
