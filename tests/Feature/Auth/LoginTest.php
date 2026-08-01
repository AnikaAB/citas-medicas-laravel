<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreaUsuariosDePrueba;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, CreaUsuariosDePrueba;

    public function test_formulario_de_login_se_muestra_correctamente(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Bienvenido de nuevo');
    }

    public function test_usuario_puede_iniciar_sesion_con_credenciales_correctas(): void
    {
        $admin = $this->crearAdmin(['email' => 'admin@clinica.com']);

        $response = $this->post('/login', [
            'email' => 'admin@clinica.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_no_se_puede_iniciar_sesion_con_password_incorrecta(): void
    {
        $this->crearAdmin(['email' => 'admin@clinica.com']);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@clinica.com',
            'password' => 'clave-equivocada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_usuario_desactivado_no_puede_iniciar_sesion(): void
    {
        $this->crearAdmin([
            'email' => 'inactivo@clinica.com',
            'activo' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'inactivo@clinica.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_usuario_autenticado_puede_cerrar_sesion(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
