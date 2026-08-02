<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Password de TODOS los usuarios de prueba: "password"
     */
    public function run(): void
    {
        $pass = Hash::make('password');
        $now = Carbon::now();

        // 1 Administrador (id 1)
        DB::table('users')->insert([
            'name' => 'Administrador General',
            'email' => 'admin@clinica.com',
            'password' => $pass,
            'rol' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 7 Recepcionistas (id 2 al 8)
        $recepcionistas = [
            ['Maria Fernanda Lopez Torres', 'mlopez@clinica.com'],
            ['Carlos Andres Ramirez Vera', 'cramirez@clinica.com'],
            ['Gabriela Isabel Suarez Mora', 'gsuarez@clinica.com'],
            ['Jorge Luis Castillo Reyes', 'jcastillo@clinica.com'],
            ['Andrea Paola Jimenez Cruz', 'ajimenez@clinica.com'],
            ['Diego Fernando Ortiz Salas', 'dortiz@clinica.com'],
            ['Valentina Cordova Pena', 'vcordova@clinica.com'],
        ];

        foreach ($recepcionistas as [$name, $email]) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => $pass,
                'rol' => 'recepcionista',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 5 Doctores - usuarios de login (id 9 al 13)
        $doctores = [
            ['Dr. Ricardo Alvarado Pena', 'ralvarado@clinica.com'],
            ['Dr. Sofia Mendoza Cruz', 'smendoza@clinica.com'],
            ['Dr. Miguel Torres Chavez', 'mtorres@clinica.com'],
            ['Dr. Paola Ibarra Salinas', 'pibarra@clinica.com'],
            ['Dr. Fernando Rojas Delgado', 'frojas@clinica.com'],
        ];

        foreach ($doctores as [$name, $email]) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => $pass,
                'rol' => 'doctor',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 20 Pacientes - usuarios de login (id 14 al 33)
        $pacientes = [
            ['Juan Perez Gomez', 'jperez@correo.com'],
            ['Maria Gonzalez Ruiz', 'mgonzalez@correo.com'],
            ['Luis Martinez Soto', 'lmartinez@correo.com'],
            ['Ana Lopez Vera', 'alopez@correo.com'],
            ['Pedro Sanchez Diaz', 'psanchez@correo.com'],
            ['Carmen Ramirez Ortiz', 'cramirez2@correo.com'],
            ['Jose Torres Leon', 'jtorres@correo.com'],
            ['Rosa Flores Castillo', 'rflores@correo.com'],
            ['Manuel Vargas Rios', 'mvargas@correo.com'],
            ['Isabel Castro Mora', 'icastro@correo.com'],
            ['Francisco Morales Pena', 'fmorales@correo.com'],
            ['Elena Jimenez Cordova', 'ejimenez@correo.com'],
            ['Alberto Reyes Salas', 'areyes@correo.com'],
            ['Patricia Suarez Vega', 'psuarez@correo.com'],
            ['Roberto Cruz Herrera', 'rcruz@correo.com'],
            ['Sandra Ortega Paredes', 'sortega@correo.com'],
            ['Ricardo Delgado Nunez', 'rdelgado@correo.com'],
            ['Monica Aguilar Rojas', 'maguilar@correo.com'],
            ['Eduardo Silva Campos', 'esilva@correo.com'],
            ['Veronica Paredes Luna', 'vparedes@correo.com'],
        ];

        foreach ($pacientes as [$name, $email]) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => $pass,
                'rol' => 'paciente',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
