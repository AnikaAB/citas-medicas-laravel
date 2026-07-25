<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PacientesSeeder extends Seeder
{
    public function run(): void
    {
        $pacientes = [
            ['Juan', 'Perez Gomez', '0701234501', '0987654301', 'jperez@correo.com', '1990-03-12'],
            ['Maria', 'Gonzalez Ruiz', '0701234502', '0987654302', 'mgonzalez@correo.com', '1985-07-23'],
            ['Luis', 'Martinez Soto', '0701234503', '0987654303', 'lmartinez@correo.com', '1998-11-02'],
            ['Ana', 'Lopez Vera', '0701234504', '0987654304', 'alopez@correo.com', '1975-01-19'],
            ['Pedro', 'Sanchez Diaz', '0701234505', '0987654305', 'psanchez@correo.com', '2000-05-30'],
            ['Carmen', 'Ramirez Ortiz', '0701234506', '0987654306', 'cramirez@correo.com', '1993-09-14'],
            ['Jose', 'Torres Leon', '0701234507', '0987654307', 'jtorres@correo.com', '1988-02-27'],
            ['Rosa', 'Flores Castillo', '0701234508', '0987654308', 'rflores@correo.com', '1979-12-05'],
            ['Manuel', 'Vargas Rios', '0701234509', '0987654309', 'mvargas@correo.com', '1995-06-18'],
            ['Isabel', 'Castro Mora', '0701234510', '0987654310', 'icastro@correo.com', '1982-04-09'],
            ['Francisco', 'Morales Peña', '0701234511', '0987654311', 'fmorales@correo.com', '1991-08-22'],
            ['Elena', 'Jimenez Cordova', '0701234512', '0987654312', 'ejimenez@correo.com', '1997-10-11'],
            ['Alberto', 'Reyes Salas', '0701234513', '0987654313', 'areyes@correo.com', '1986-03-03'],
            ['Patricia', 'Suarez Vega', '0701234514', '0987654314', 'psuarez@correo.com', '1992-07-07'],
            ['Roberto', 'Cruz Herrera', '0701234515', '0987654315', 'rcruz@correo.com', '1978-11-25'],
            ['Sandra', 'Ortega Paredes', '0701234516', '0987654316', 'sortega@correo.com', '1999-01-30'],
            ['Ricardo', 'Delgado Nuñez', '0701234517', '0987654317', 'rdelgado@correo.com', '1984-05-16'],
            ['Monica', 'Aguilar Rojas', '0701234518', '0987654318', 'maguilar@correo.com', '1996-09-08'],
            ['Eduardo', 'Silva Campos', '0701234519', '0987654319', 'esilva@correo.com', '1989-12-20'],
            ['Veronica', 'Paredes Luna', '0701234520', '0987654320', 'vparedes@correo.com', '1994-02-14'],
        ];

        foreach ($pacientes as [$nombre, $apellido, $cedula, $telefono, $email, $fechaNacimiento]) {
            $user = User::create([
                'name' => "$nombre $apellido",
                'email' => $email,
                'password' => Hash::make('password'),
                'rol' => User::ROL_PACIENTE,
            ]);

            Paciente::create([
                'user_id' => $user->id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula,
                'telefono' => $telefono,
                'email' => $email,
                'fecha_nacimiento' => $fechaNacimiento,
                'direccion' => 'Ciudad, Ecuador',
            ]);
        }
    }
}
