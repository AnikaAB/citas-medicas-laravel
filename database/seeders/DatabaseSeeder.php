<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Orden importante por las llaves foraneas:
     * 1) Users primero (doctores y pacientes dependen de user_id)
     * 2) Doctores y Pacientes (citas depende de ambos)
     * 3) Citas al final
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            DoctoresSeeder::class,
            PacientesSeeder::class,
            CitasSeeder::class,
        ]);
    }
}
