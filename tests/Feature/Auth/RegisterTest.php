<?php

namespace Tests\Feature\Auth;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    private function datosValidos(array $override = []): array
    {
        return array_merge([
            'nombre' => 'Ana',
            'apellido' => 'Vasquez',
            'cedula' => '0712345678',
            'telefono' => '0991112233',
            'fecha_nacimiento' => '1995-05-20',
            'direccion' => 'Guayaquil, Ecuador',
            'email' => 'ana.vasquez@correo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $override);
    }

    public function test_formulario_de_registro_se_muestra_correctamente(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Crea tu cuenta');
    }

    public function test_un_visitante_puede_registrarse_como_paciente_y_queda_autenticado(): void
    {
        $response = $this->post('/register', $this->datosValidos());

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'ana.vasquez@correo.com',
            'rol' => User::ROL_PACIENTE,
        ]);

        $this->assertDatabaseHas('pacientes', [
            'cedula' => '0712345678',
            'email' => 'ana.vasquez@correo.com',
        ]);
    }

    public function test_no_se_puede_registrar_con_cedula_ya_existente(): void
    {
        $this->crearPacienteConUsuario(['cedula' => '0712345678']);

        $response = $this->from('/register')->post('/register', $this->datosValidos([
            'email' => 'otro.correo@correo.com',
        ]));

        $response->assertSessionHasErrors('cedula');
        $this->assertGuest();
    }

    public function test_no_se_puede_registrar_con_email_ya_existente(): void
    {
        $this->crearAdmin(['email' => 'ana.vasquez@correo.com']);

        $response = $this->from('/register')->post('/register', $this->datosValidos());

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_no_se_puede_registrar_si_la_confirmacion_de_password_no_coincide(): void
    {
        $response = $this->from('/register')->post('/register', $this->datosValidos([
            'password' => 'password123',
            'password_confirmation' => 'otra-clave',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseCount('pacientes', 0);
    }
}
