<?php

namespace Tests\Concerns;

use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Helpers para crear usuarios de cada rol en los tests, evitando repetir
 * el mismo boilerplate (crear User + su perfil enlazado) en cada archivo.
 */
trait CreaUsuariosDePrueba
{
    protected function crearAdmin(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'rol' => User::ROL_ADMIN,
            'password' => Hash::make('password'),
        ], $attrs));
    }

    protected function crearRecepcionista(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'rol' => User::ROL_RECEPCIONISTA,
            'password' => Hash::make('password'),
        ], $attrs));
    }

    protected function crearDoctorConUsuario(array $doctorAttrs = []): Doctor
    {
        $user = User::factory()->create([
            'rol' => User::ROL_DOCTOR,
            'password' => Hash::make('password'),
        ]);

        return Doctor::create(array_merge([
            'user_id' => $user->id,
            'nombre' => 'Ricardo',
            'apellido' => 'Alvarado',
            'especialidad' => 'Medicina General',
            'telefono' => '0991234567',
            'email' => $user->email,
            'horario_inicio' => '08:00:00',
            'horario_fin' => '17:00:00',
        ], $doctorAttrs));
    }

    protected function crearPacienteConUsuario(array $pacienteAttrs = []): Paciente
    {
        $user = User::factory()->create([
            'rol' => User::ROL_PACIENTE,
            'password' => Hash::make('password'),
        ]);

        return Paciente::create(array_merge([
            'user_id' => $user->id,
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => (string) random_int(1000000000, 9999999999),
            'telefono' => '0987654321',
            'email' => $user->email,
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Ciudad, Ecuador',
        ], $pacienteAttrs));
    }
}
