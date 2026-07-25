<?php

namespace Tests\Feature\Auth;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas FUNCIONALES y de VALIDACION del registro de nuevos pacientes.
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(array $overrides = []): array
    {
        return array_merge([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => '0701234599',
            'telefono' => '0991234567',
            'fecha_nacimiento' => '1995-05-10',
            'direccion' => 'Av. Siempre Viva',
            'email' => 'nuevo@correo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_un_visitante_puede_registrarse_como_paciente(): void
    {
        $response = $this->post(route('register.store'), $this->datosValidos());

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@correo.com',
            'rol' => User::ROL_PACIENTE,
        ]);
        $this->assertDatabaseHas('pacientes', [
            'cedula' => '0701234599',
            'email' => 'nuevo@correo.com',
        ]);
        $this->assertAuthenticated();
    }

    public function test_no_permite_registrar_sin_campos_obligatorios(): void
    {
        $response = $this->post(route('register.store'), []);

        $response->assertSessionHasErrors([
            'nombre', 'apellido', 'cedula', 'telefono', 'fecha_nacimiento', 'email', 'password',
        ]);
        $this->assertGuest();
    }

    public function test_no_permite_registrar_con_correo_ya_utilizado(): void
    {
        User::factory()->create(['email' => 'repetido@correo.com']);

        $response = $this->post(route('register.store'), $this->datosValidos([
            'email' => 'repetido@correo.com',
        ]));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('pacientes', 0);
    }

    public function test_no_permite_registrar_con_cedula_duplicada(): void
    {
        Paciente::factory()->create(['cedula' => '0701234599']);

        $response = $this->post(route('register.store'), $this->datosValidos());

        $response->assertSessionHasErrors('cedula');
    }

    public function test_no_permite_password_menor_a_8_caracteres(): void
    {
        $response = $this->post(route('register.store'), $this->datosValidos([
            'password' => '123',
            'password_confirmation' => '123',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_no_permite_fecha_de_nacimiento_futura(): void
    {
        $response = $this->post(route('register.store'), $this->datosValidos([
            'fecha_nacimiento' => now()->addYear()->toDateString(),
        ]));

        $response->assertSessionHasErrors('fecha_nacimiento');
    }
}
